package Playnet::Content::HEADLINE_CountryCaptured;

use strict;

use lib ('/usr/local/community/paper'); 
use Playnet::Database;
use Playnet::Misc;

INIT {
	
	### add handler
	$main::source_vars{'25'} = \&handleContent;
	
	### add statements
	&addStatement('game_db','25_ownership_select',q{select count(*) from strat_cp where cp_type != 5 and country = ?});

}

sub handleContent(){
	
	my $vars = shift(@_);
	
	my $count = &doSelect('25_ownership_select','array_row',$vars->{'country_id'});
		
	if($count == 0){
		return 1;
	}
	
	return 0;

}

1;
