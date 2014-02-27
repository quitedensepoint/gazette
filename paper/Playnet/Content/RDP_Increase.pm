package Playnet::Content::RDP_Increase;

use strict;

use lib ('/usr/local/community/paper'); 
use Playnet::Database;
use Playnet::Misc;

INIT
{
	$main::source_vars{'6'} = \&handleContent;
}

sub handleContent(){
	
	my $vars = shift(@_);
	my $rules = &doSelect('rdp_rules_select','hashref_all', $vars->{'country_id'}, '>');
	
	if($rules)
	{
		foreach my $rule (@{$rules})
		{
			my $vehicle = &doSelect('template_vehicle_select',
										'hashref_row',
										$rule->{'country_id'},
										$rule->{'veh_category_id'},
										$rule->{'veh_class_id'},
										$rule->{'veh_type_id'},
										$vars->{'template_id'});
			
			if($vehicle)
			{
				&hashMerge($vars, $rule);
				&hashMerge($vars, $vehicle);
				
				$vars->{'data'}					= $vars->{'next_capacity'} - $vars->{'current_capacity'};
				$vars->{'spawns'}				= $vars->{'current_capacity'};
				$vars->{'QUANTITY_INCREASE'} 	= $vars->{'data'};
				$vars->{'PERCENTAGE_INCREASE'} 	= int($vars->{'data'} / $vars->{'spawns'} * 100);
				$vars->{'INCREASE_ADJ'} 		= &getRDPChangeAdj($vars->{'PERCENTAGE_INCREASE'});
				
				return 1;
			}
		}
	}
	
	return 0;
}

1;
