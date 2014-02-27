package Playnet::Content::STRATEGIC_AirfieldsOwned;

use strict;

use lib ('/usr/local/community/paper'); 
use Playnet::Database;
use Playnet::Misc;

INIT {
	
	### add handler
	$main::source_vars{'30'} = \&handleContent;
	
	### add statements
	&addStatement('community_db','30_countries_select',q{select * from paper_countries});
	&addStatement('game_db','30_airfields_select',q{select count(*) from strat_facility where country = ? and facility_type = 8});

}

sub handleContent(){
	
	my $vars = shift(@_);
	
	my $countries 	= &doSelect('30_countries_select','hashref_all');
	my $rank		= 1;
	my $total		= 0;
	
	foreach my $country (@{$countries}){
		$country->{'count'} = &doSelect('30_airfields_select','array_row',$country->{'country_id'});
		$total				+= $country->{'count'};
	}
	
	my @sorted = sort {$b->{'count'} <=> $a->{'count'}} (@{$countries});
	
	foreach my $country (@sorted){
		
		my $name 	= uc($country->{'name'});
		my $side 	= uc($country->{'side'});
		my $count 	= $country->{'count'};
		my $percent = int(($count / $total) * 100);
		
		$vars->{$side.'_COUNT'} 	+= $count;
		$vars->{$side.'_PERCENT'} 	+= $percent;
		
		$vars->{$name.'_COUNT'} 	= $count;
		$vars->{$name.'_PERCENT'} 	= $percent;
		
		$vars->{&getPlace($rank).'_COUNT'} 		= $count;
		$vars->{&getPlace($rank).'_PERCENT'} 	= $percent;
		$vars->{&getPlace($rank).'_COUNTRY'} 	= $country->{'name'};
		
		if($country->{'country_id'} == $vars->{'country_id'}){
			$vars->{'SUBJECT_COUNT'} 	= $count;
			$vars->{'SUBJECT_PERCENT'} 	= $percent;
			$vars->{'SUBJECT_PLACE'} 	= lc(&getPlace($rank));
		}
		
		
		$rank++;
		
	}
	
	return 1;


}

sub getPlace(){
	
	my $rank = shift(@_);
	
	if($rank == 1){
		return '1ST';
	}
	elsif($rank == 2){
		return '2ND'
	}
	
	return '3RD';
}

1;
