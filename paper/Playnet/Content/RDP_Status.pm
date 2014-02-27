package Playnet::Content::RDP_Status;

use strict;

use lib ('/usr/local/community/paper'); 
use Playnet::Database;
use Playnet::Misc;

use constant OUTPUT_PERIOD 	=> 600;
use constant DAY_LENGTH 	=> 86400;

INIT
{
	### add handler
	$main::source_vars{'16'} = \&handleContent;
	
	### add statements
	&addStatement('community_db','16_factories_select',q{select * from paper_factories where ocountry_id = ?});
	&addStatement('community_db','16_cycle_select',q{select FLOOR((cy.produced / cy.goal) * 100) as completed, cy.produced, cy.goal from paper_countries co, paper_cycles cy where co.country_id = ? and co.cycle_id = cy.cycle_id limit 1});
	
	#&addStatement('game_db','16_outputs_select',q{select sum(contributing) as produced from strat_factory_outputs where facility_oid = ? and output_time between ? and ?});
}

sub handleContent()
{
	my $vars 		= shift(@_);
	my $factories 	= &doSelect('16_factories_select','hashref_all',$vars->{'country_id'});
	my $status 		= &doSelect('16_cycle_select','hashref_row',$vars->{'country_id'});
	
	my $producers 	= 0;
	my $health		= 0;
	my $damage		= 0;
	
	foreach my $factory (@{$factories})
	{
		print $factory->{'name'}.": ".$factory->{'status'}."\n";
		
		$producers	+= 1;
		$health		+= 100;
		
		if($factory->{'status'} eq 'Captured')
		{
			$damage		+= 100;
		}
		else
		{
			$damage		+= $factory->{'damaged'};
		}
	}
	
	$vars->{'OUTPUT'} 		= int(($health - $damage) / $producers);
	$vars->{'OUTPUT'}		= ($vars->{'OUTPUT'} > 100) ? 100: $vars->{'OUTPUT'};
	$vars->{'COMPLETION'}	= ($status->{'completed'} > 100) ? 100: $status->{'completed'};
	
	$vars->{'GOAL'} 		= $status->{'goal'};
	$vars->{'PRODUCED'} 	= $status->{'produced'};
	$vars->{'BALANCE'} 		= $vars->{'GOAL'} - $vars->{'PRODUCED'};
	$vars->{'ETA'} 			= &estimateCompletion($vars->{'BALANCE'}, int($producers * ($vars->{'OUTPUT'} * .01)));
	
	return 1;
}

1;

sub estimateCompletion()
{
	my $goal 		= shift;
	my $producers	= shift;
	
	if($producers > 0)
	{
		my $seconds = int(($goal / $producers) * OUTPUT_PERIOD);
		
		if($seconds > 60)
		{
			my $minutes	= int($seconds / 60);
			
			if($minutes > 60)
			{
				my $hours = int($minutes / 60);
				
				if($hours > 24)
				{
					my $days = int($hours / 24);
					
					return $days."d".($hours - ($days * 24))."h";
				}
				
				return $hours."h".($minutes - ($hours * 60))."m";
			}
			
			return $minutes."m".($seconds - ($minutes * 60))."s";
		}
		
		return $seconds."s";
	}
	
	return "Unknown";
}