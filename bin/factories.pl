#!/usr/bin/perl -w

## Standard Modules
use strict;
use Fcntl ':flock';

use lib ('/usr/local/community/paper'); 
use Playnet::Database;
use Playnet::Misc;

use constant MAX_FACTORY_DAMAGE => 0;

INIT {
	
	&addDatabase('community_db',"dbi:mysql:database=community;host=localhost",'community','fun4all'); #CP111713 changed csr to localhost
	&addDatabase('game_db',"dbi:mysql:database=wwiionline;host=gamedbu.wwiionline.com",'wwiiol','freebird');

	&addStatement('game_db','intermission_state_select',q{
		select value from wwii_config where variableName = 'arena.intermission' limit 1
	});
	
	&addStatement('community_db','reset_select',q{
		select count(*) as count from paper_factories
	});
	
	&addStatement('community_db','factories_select',q{
		select *,UNIX_TIMESTAMP(checked) as last_checked from paper_factories
	});
	
	&addStatement('community_db','cycle_update',q{
		update paper_countries co,
			paper_cycles cy
		set cy.produced = cy.produced + ?
		where co.country_id = ?
			and co.cycle_id = cy.cycle_id
	});
	
	&addStatement('community_db','factory_update',q{
		update paper_factories
		set damaged=?,
			country_id=?,
			side_id=?,
			status=?,
			checked=FROM_UNIXTIME(?)
		where factory_id=?
		limit 1
	});
	
	&addStatement('community_db','factory_insert',q{
		insert into paper_factories (factory_id,facility_oid,object_oid,name,chokepoint,country_id,side_id,ocountry_id,oside_id,damaged,status,checked)
		values (NULL,?,?,?,?,?,?,?,?,'0','Producing',NOW())
	});
	
	&addStatement('game_db','outputs_select',q{
		select *, UNIX_TIMESTAMP(output_time) as output_stamp
		from strat_factory_outputs
		where facility_oid = ?
			and output_time > FROM_UNIXTIME(?)
		order by output_time ASC
	});
	
	&addStatement('game_db','reset_factories_select',q{
		select f.facility_oid,
			f.name,
			f.country,
			f.side,
			f.originalcountry,
			f.originalside,
			c.name as chokepoint,
			o.object_oid
		from strat_facility f
			JOIN strat_cp c
			JOIN strat_object o
		where f.facility_type = 2
			and f.facility_subtype = 7
			and f.cp_oid = c.cp_oid
			and f.facility_oid = o.facility_oid
			and o.object_type = 2
			and o.objectname = 'factoryblock1'
	});
}

my $intermission = &doSelect("intermission_state_select",'array_row');
print("intermission = $intermission\n") ;
if($intermission && $intermission > 0)
{
	print "Game is in intermission mode - leaving factories alone.\n";
	&freeDatabases();
	exit(0);
}

my $lock_file = '/usr/local/community/run/factories.lock';

open(LOCK, $lock_file) or die "Failed to open lock file!";

if(flock(LOCK, LOCK_EX | LOCK_NB))
{
	print "Factory file locked.\n";
	
	my $last_id 	= <LOCK>;
	my $new_last_id = 0;
	
	chomp($last_id);
	
	if($last_id =~ /^$/)
	{
		$last_id = 0;
	}
	
	my $reset_factories = &doSelect("reset_select",'array_row');
	
	print "Running: $reset_factories\n";
	
	if(!$reset_factories)
	{
		print "Resetting.\n";
		
		my $reset_factory = &doSelect("reset_factories_select",'hashref_all');
		
		foreach my $rf (@{$reset_factory})
		{
			&doUpdate
			(
				'factory_insert',
				$rf->{'facility_oid'},
				$rf->{'object_oid'},
				$rf->{'name'},
				$rf->{'chokepoint'},
				$rf->{'country'},
				$rf->{'side'},
				$rf->{'originalcountry'},
				$rf->{'originalside'}
			);
		}
	}
	
	my $fcount			= 0;
	my %cycles			= ();
	my $factories 		= &doSelect("factories_select",'hashref_all');
	
	foreach my $factory (@{$factories})
	{
		print "### Checking Factory ".$factory->{'factory_id'}."\n";
		
		my $ocount 	= 0;
		my $outputs = &doSelect
		(
			'outputs_select',
			'hashref_all',
			$factory->{'facility_oid'},
			$factory->{'last_checked'}
		);
		
		foreach my $output (@{$outputs})
		{
			if($output->{'outputUID'} > $last_id)
			{
				print "     Processing ".$output->{'outputUID'}."/";
				
				if($output->{'country'} == $factory->{'ocountry_id'})
				{
					my $produced = (100 - (MAX_FACTORY_DAMAGE * ($output->{'damage_pctg'} * .01)));
					$cycles{$output->{'country'}} += $produced;
					print $produced;
				}
				else
				{
					print "0";
				}
				
				print "\n";
				
				$factory->{'last'} 	= $output;
				$new_last_id 		= $output->{'outputUID'};
				$ocount				+= 1;
			}
		}
		
		if(exists($factory->{'last'}))
		{
			$factory->{'damaged'} 		= $factory->{'last'}->{'damage_pctg'};
			$factory->{'country_id'} 	= $factory->{'last'}->{'country'};
			$factory->{'side_id'} 		= $factory->{'last'}->{'side'};
			$factory->{'checked'} 		= $factory->{'last'}->{'output_stamp'};
			
			if($factory->{'country_id'} != $factory->{'ocountry_id'})
			{
				$factory->{'status'} = 'Captured';
			}
			elsif($factory->{'damaged'} >= 100)
			{
				$factory->{'status'} = 'Under Repairs';
			}
			elsif($factory->{'damaged'} > 0)
			{
				$factory->{'status'} = 'Reduced Production';
			}
			else
			{
				$factory->{'status'} = 'Full Production';
			}
			
			&doUpdate
			(
				'factory_update',
				$factory->{'damaged'},
				$factory->{'country_id'},
				$factory->{'side_id'},
				$factory->{'status'},
				$factory->{'checked'},
				$factory->{'factory_id'}
			);
		}
		
		$fcount++;
		
		#print "     ".int($cycles{$country} / 100)." outputs processed\n";
	}
	
	print "Cycled over $fcount factories.\n";
	
	foreach my $country (keys(%cycles))
	{
		print "Output: $country/$cycles{$country}/".int($cycles{$country} / 100)."\n";
		&doUpdate("cycle_update", int($cycles{$country} / 100), $country);
	}
	
	if($new_last_id > $last_id)
	{
		`echo $new_last_id > $lock_file`;
	}
	
	flock(LOCK, LOCK_UN);
}
else
{
	print "Unable to get a lock on the Factory file.\n";
}

close(LOCK);

&freeDatabases();

exit(0);

