package Playnet::Content::HEADLINE_MajorCityContested;

use strict;

use lib ('/usr/local/community/paper'); 
use Playnet::Database;
use Playnet::Misc;

INIT {
	
	### add handler
	$main::source_vars{'1'} = \&handleContent;
	
	### add statements
	&addStatement('community_db','1_enemy_side_select',q{select side from paper_countries where side_id != ? limit 1});
	&addStatement('game_db','1_contested_cities_select',q{select c.cp_oid,c.name,count(f.facility_oid) as facilities from strat_cp c, strat_facility f where c.cp_type != 5 and c.contention = 1 and c.country = ? and c.cp_oid = f.cp_oid group by c.cp_oid order by facilities DESC limit 1});

}

sub handleContent(){
	
	my $vars = shift(@_);
	
	my $city = &doSelect('1_contested_cities_select','hashref_row',$vars->{'country_id'});
	
	if($city->{'facilities'} > 10){
		
		if(&getRandomNumber(1,100) > 50){
			next;
		}
		
		$vars->{'CON_CITY'} = uc($city->{'name'});
		
		return 1;
	}
	
	return 0;

}

1;
