package Playnet::Content::PAPER_SpawnLister;

use strict;

use lib ('/usr/local/community/paper'); 
use Playnet::Database;
use Playnet::Misc;

INIT {
	
	### add handler
	$main::source_vars{'12'} = \&handleContent;
	
	### add statements
	&addStatement('game_db','spawnables_select',q{
		SELECT DISTINCT v.countryid,
			v.categoryid,
			v.classid,
			v.typeid
		FROM wwii_vehtype v,
			toe.vehicles tv,
			strat_hc_units h
		WHERE v.countryid = ?
			AND v.categoryid != 4
			AND v.classid != 9
			AND tv.vehtype_oid = v.vehtype_oid
			AND tv.capacity > 0
			AND h.toe_template = tv.id
		ORDER BY v.categoryid,v.classid,v.typeid
	});
	
	&addStatement('community_db','spawnable_select',q{
		SELECT short_name as name
		FROM paper_vehicles
		WHERE country_id = ?
			AND category_id = ?
			AND class_id = ?
			AND type_id = ?
			AND shown='True'
	});

}

sub handleContent()
{
	my $vars = shift(@_);
	
	$vars->{'LIST'} = 'Infantry<br>';
	
	my $vehicles = &doSelect('spawnables_select','hashref_all', $vars->{'country_id'});
	
	foreach my $vehicle (@{$vehicles})
	{
		my $name = &doSelect('spawnable_select','array_row',$vehicle->{'countryid'},$vehicle->{'categoryid'},$vehicle->{'classid'},$vehicle->{'typeid'});
		
		if($name and $name ne '')
		{
			$vars->{'LIST'} .= $name.'<br>';
		}
	}
	
	return 1;

}

1;
