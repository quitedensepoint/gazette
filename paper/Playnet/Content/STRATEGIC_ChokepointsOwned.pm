package Playnet::Content::STRATEGIC_ChokepointsOwned;

use strict;

use lib ('/usr/local/community/paper'); 
use Playnet::Database;
use Playnet::Misc;

INIT {
	
	### add handler
	$main::source_vars{'10'} = \&handleContent;
	
	### add statements
	&addStatement('community_db','10_countries_select',q{select * from paper_countries});
	&addStatement('game_db','10_cps_select',q{select count(*) from strat_cp where country = ? and cp_type != 5});
	&addStatement('game_db','10_total_select',q{select count(*) from strat_cp where cp_type != 5});

}

sub handleContent(){
	
	my $vars = shift(@_);
	
	my $countries 	= &doSelect('10_countries_select','hashref_all');
	my $total 		= &doSelect('10_total_select','array_row');
	my $rank		= 1;
	
	foreach my $country (@{$countries}){
		$country->{'count'} = &doSelect('10_cps_select','array_row',$country->{'country_id'});
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
