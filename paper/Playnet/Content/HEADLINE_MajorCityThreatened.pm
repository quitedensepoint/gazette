package Playnet::Content::HEADLINE_MajorCityThreatened;

use strict;

use lib ('/usr/local/community/paper'); 
use Playnet::Database;
use Playnet::Misc;

INIT {
	
	### add handler
	$main::source_vars{'29'} = \&handleContent;
	
	### add statements
	&addStatement('community_db','29_enemy_side_select',q{select side from paper_countries where side_id != ? limit 1});
	&addStatement('game_db','29_cities_select',q{select c.cp_oid,c.name,count(f.facility_oid) as facilities from strat_cp c, strat_facility f where c.cp_type != 5 and c.country = ? and c.cp_oid = f.cp_oid group by c.cp_oid order by facilities DESC});
	&addStatement('map_db','29_links_select',q{select c.controlling_side from links l, chokepoints c where l.start_cpid = ? and l.end_cpid = c.cpid});

}

sub handleContent(){
	
	my $vars = shift(@_);
	
	my $cities = &doSelect('29_cities_select','hashref_all',$vars->{'country_id'});
	
	foreach my $city (@{$cities}){
		
		if($city->{'facilities'} > 10){
			
			if(&getRandomNumber(1,100) > 50){
				next;
			}
			
			my $links = &doSelect('29_links_select','hashref_all',$city->{'cp_oid'});
			my $total = 0;
			my $enemy = 0;
			
			foreach my $l (@{$links}){
				
				if($l->{'controlling_side'} ne $vars->{'side'}){
					$enemy++;
				}
				
				$total++;
				
			}
			
			if($enemy > 0){
				
				$vars->{'CITY'} 		= uc($city->{'name'});
				$vars->{'THREATS'} 		= $enemy;
				
				return 1;
				
			}
		}
		else{
			last;
		}
		
	}
	
	
	return 0;

}

1;
