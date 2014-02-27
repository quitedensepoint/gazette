package Playnet::Content::STRATEGIC_FrontLines;

use strict;

use lib ('/usr/local/community/paper'); 
use Playnet::Database;
use Playnet::Misc;

INIT {
	
	### add handler
	$main::source_vars{'40'} = \&handleContent;
	
	### add statements
	&addStatement('map_db','40_frontlines_select',q{select * from frontlines where country_id = ? limit 1});
	
}

sub handleContent(){
	
	my $vars = shift(@_);
	
	my $frontline 	= &doSelect('40_frontlines_select','hashref_row',$vars->{'country_id'});
	
	if(defined($frontline) and $frontline->{'distance'} > 0){
		
		$vars->{'DISTANCE'} 	= $frontline->{'distance'};
		$vars->{'CHOKEPOINTS'} 	= $frontline->{'chokepoints'};
		
		return 1;
		
	}
	
	return 0;
	
}

1;
