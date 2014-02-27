package Playnet::Content::HEADLINE_CycleStarted;

use strict;

use lib ('/usr/local/community/paper'); 
use Playnet::Database;
use Playnet::Misc;

INIT {
	
	### add handler
	$main::source_vars{'39'} = \&handleContent;
	
	### add statements
	&addStatement('community_db','39_cycles_select',q{select count(*) from paper_countries c, paper_cycles cy where c.country_id = ? and c.cycle_id = cy.cycle_id and (UNIX_TIMESTAMP() - UNIX_TIMESTAMP(cy.start_time) < 10800)});

}

sub handleContent(){
	
	return 0;
	my $vars = shift(@_);
	
	my $cycles = &doSelect('39_cycles_select','array_row',$vars->{'country_id'});
	
	return ($cycles > 0) ? 1: 0;

}

1;
