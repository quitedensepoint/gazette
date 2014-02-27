package Playnet::Content::PAPER_CasualtyReporter;

use strict;

use lib ('/usr/local/community/paper'); 
use Playnet::Database;
use Playnet::Misc;

INIT {
	
	### add handler
	$main::source_vars{'35'} = \&handleContent;
	
	### add statements
	&addStatement('game_db','35_casualties_select',q{
		SELECT k.victim_country,
			k.victim_category,
			k.victim_class,
			k.victim_type
		FROM wwii_sortie s, 
			wwii.kills k,
			wwii_country c
		WHERE s.added >= FROM_UNIXTIME(UNIX_TIMESTAMP() - 86400)
			AND k.victim_sortie_id = s.sortie_id
			AND k.victim_country = c.countryid
			AND c.sideid = ?
	});
	
	#&addStatement('beta_db','35_casualties_select',q{select * from wwii_kill where added >= FROM_UNIXTIME(?)});
	
	&addStatement('community_db','35_vehicles_select',q{
		SELECT *
		FROM paper_vehicles v,
			paper_countries c
		WHERE v.country_id = c.country_id
			AND c.side_id = ?
	});
	
}

sub handleContent(){
	
	my $vars = shift(@_);
	
	my $kills 		= &doSelect('35_casualties_select','hashref_all', $vars->{'side_id'});
	my $vehicles 	= &doSelect('35_vehicles_select','hashref_all', $vars->{'side_id'});
	
	$vars->{'ARMY_CASUALTIES'} 	= 0;
	$vars->{'AIR_CASUALTIES'} 	= 0;
	$vars->{'NAVY_CASUALTIES'} 	= 0;
	
	foreach my $k (@{$kills})
	{
		foreach my $vehicle (@{$vehicles})
		{
			if($k->{'victim_country'} == $vehicle->{'country_id'} and $k->{'victim_category'} == $vehicle->{'category_id'} and $k->{'victim_class'} == $vehicle->{'class_id'} and $k->{'victim_type'} == $vehicle->{'type_id'})
			{
				if($vehicle->{'branch_id'} == 1)
				{
					$vars->{'ARMY_CASUALTIES'}++;
				}
				elsif($vehicle->{'branch_id'} == 2)
				{
					$vars->{'AIR_CASUALTIES'}++;
				}
				elsif($vehicle->{'branch_id'} == 3)
				{
					$vars->{'NAVY_CASUALTIES'}++;
				}
			}
		}
	}
	
	return 1;

}

1;
