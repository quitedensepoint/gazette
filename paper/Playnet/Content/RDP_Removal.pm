package Playnet::Content::RDP_Removal;

use strict;

use lib ('/usr/local/community/paper'); 
use Playnet::Database;
use Playnet::Misc;

INIT
{
	$main::source_vars{'4'} = \&handleContent;
}

sub handleContent()
{
	my $vars = shift(@_);
	my $rules = &doSelect('rdp_rules_select','hashref_all', $vars->{'country_id'}, 'x');
	
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
				return 1;
			}
		}
	}
	
	return 0;
}

1;
