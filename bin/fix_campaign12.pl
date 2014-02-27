#!/usr/bin/perl -w

## Standard Modules
use strict;

use lib ('/usr/local/community/scoring'); 
use Playnet::Database;

INIT
{
	if(!&addDatabase('game_db',"dbi:mysql:database=wwiionline;host=gamedbs.wwiionline.com",'wwiiol','freebird'))
	{
		die "Unable to connect to GameDB";
	}
	
	#########################################
	# Services Queries
	#########################################
	
	&addStatement('game_db','sorties_select',q{
		SELECT sortie_id
		FROM wwii_sortie_campaign12
		WHERE sortie_id > 0
			AND sortie_id < 1100000
		ORDER BY sortie_id ASC
	});
	
	&addStatement('game_db','sortie_select',q{
		SELECT sortie_id,
			origin_id,
			origin_ox,
			origin_oy,
			vcountry,
			vcategory,
			vclass,
			vtype
		FROM wwii_sortie_campaign12
		WHERE sortie_id = ?
		ORDER BY spawn_time ASC
	});
	
	&addStatement('game_db','sortie_update',q{
		UPDATE wwii_sortie_campaign12
		SET sortie_id = ?
		WHERE sortie_id = ?
			AND origin_id = ?
			AND origin_ox = ?
			AND origin_oy = ?
			AND vcountry = ?
			AND vcategory = ?
			AND vclass = ?
			AND vtype = ?
	});
	
	&addStatement('game_db','kills_update',q{
		UPDATE wwii_kill_campaign12
		SET weapon_id = ?
		WHERE weapon_id = ?
			AND veh_country = ?
			AND veh_cat = ?
			AND veh_class = ?
			AND veh_type = ?
	});
	
	&addStatement('game_db','vehicle_update',q{
		UPDATE wwii_vehicle_campaign12
		SET weapon_id = ?
		WHERE weapon_id = ?
			AND veh_country = ?
			AND veh_cat = ?
			AND veh_class = ?
			AND veh_type = ?
	});
}

my %sysvars = &startScoring();

my $all 	= &doSelect('sorties_select','hashref_all');
my $total 	= scalar(@{$all});

foreach my $a (@{$all})
{
	if($a->{'sortie_id'} != $sysvars{'last'})
	{
		my $sorties = &doSelect('sortie_select', 'hashref_all', $a->{'sortie_id'});
		
		if(scalar(@{$sorties}) > 1)
		{
			my $last = undef;
			
			foreach my $sortie (@{$sorties})
			{
				if(&duplicate($last, $sortie))
				{
					if($sysvars{'first'} == 0)
					{
						$sysvars{'first'} = $sortie->{'sortie_id'};
						
						print "          First Duplicate found at sortie # ".$sortie->{'sortie_id'}."\n";
					}
					
					$sysvars{'last'} = $sortie->{'sortie_id'};
					
					# do updates
					
					if(&doUpdate('sortie_update',
						$sysvars{'sortie_id'},
						$sortie->{'sortie_id'},
						$sortie->{'origin_id'},
						$sortie->{'origin_ox'},
						$sortie->{'origin_oy'},
						$sortie->{'vcountry'},
						$sortie->{'vcategory'},
						$sortie->{'vclass'},
						$sortie->{'vtype'}))
					{
						&doUpdate('kills_update',
							$sysvars{'sortie_id'},
							$sortie->{'sortie_id'},
							$sortie->{'vcountry'},
							$sortie->{'vcategory'},
							$sortie->{'vclass'},
							$sortie->{'vtype'}
						);
						
						&doUpdate('vehicle_update',
							$sysvars{'sortie_id'},
							$sortie->{'sortie_id'},
							$sortie->{'vcountry'},
							$sortie->{'vcategory'},
							$sortie->{'vclass'},
							$sortie->{'vtype'}
						);
					}
					
					$sysvars{'sortie_id'}++;
					$sysvars{'duplicates'}++;
					
					last;
				}
				
				$last = $sortie;
			}
		}
	}
	
	$sysvars{'processed'}++;
	
	if(($sysvars{'processed'} % 10000) == 0)
	{
		print "     Checked ".$sysvars{'processed'}." of $total sorties (".$sysvars{'duplicates'}." duplicates)\n";
		$sysvars{'duplicates'} = 0;
	}
}

print "Found ".($sysvars{'sortie_id'} - 10000000)." duplicate sorties.\n";

&freeDatabases();

exit(0);

sub startScoring()
{
	my %vars = ();
	
	$vars{'sortie_id'} 	= 10000000;
	$vars{'processed'} 	= 0;
	$vars{'first'} 		= 0;
	$vars{'last'} 		= 0;
	$vars{'duplicates'}	= 0;
	
	return %vars;
}

sub duplicate()
{
	my $last 	= shift;
	my $current 	= shift;
	
	if(defined($last) and defined($current))
	{
		if($last->{'origin_id'} != $current->{'origin_id'} or
		   $last->{'origin_ox'} != $current->{'origin_ox'} or
		   $last->{'origin_oy'} != $current->{'origin_oy'} or
		   $last->{'vcountry'}  != $current->{'vcountry'} or
		   $last->{'vcategory'} != $current->{'vcategory'} or
		   $last->{'vclass'}  	!= $current->{'vclass'} or
		   $last->{'vtype'} 	!= $current->{'vtype'})
		{
			return 1;
		}
	}
	
	return 0;
}
