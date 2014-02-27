package Playnet::Content::STRATEGIC_OffensiveCountry;

use strict;

use lib ('/usr/local/community/paper'); 
use Playnet::Database;
use Playnet::Misc;

INIT {
	
	### add handler
	$main::source_vars{'32'} = \&handleContent;
	
	### add statements
	&addStatement('community_db','32_countries_select',q{select * from paper_countries});
	&addStatement('game_db','32_firebases_select',q{select count(facility_oid) as count from strat_facility where facility_type = 7 and open = 1 and country = ?});
	
}

sub handleContent(){
	
	my $vars = shift(@_);
	
	my $countries 	= &doSelect('32_countries_select','hashref_all');
	my $offensive	= undef;
	
	foreach my $country (@{$countries}){
		
		$country->{'count'} = &doSelect('32_firebases_select','array_row', $country->{'country_id'});
		
		if(!defined($offensive) or $country->{'count'} > $offensive->{'count'}){
			$offensive = $country;
		}
		
	}
	
	if(defined($offensive)){
		
		$vars->{'OFFENSIVE_COUNTRY'} 		= $offensive->{'name'};
		$vars->{'OFFENSIVE_COUNTRY_ADJ'} 	= $offensive->{'adjective'};
		$vars->{'OFFENSIVE_SIDE'} 			= $offensive->{'side'};
		
		return 1;
		
	}
		
	return 0;
	
}

1;
