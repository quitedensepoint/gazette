package Playnet::Content::HEADLINE_CountryDiminished;

use strict;

use lib ('/usr/local/community/paper'); 
use Playnet::Database;
use Playnet::Misc;

INIT {
	
	### add handler
	$main::source_vars{'26'} = \&handleContent;
	
	### add statements
	&addStatement('game_db','26_ownership_select',q{select count(*) from strat_cp where cp_type != 5 and country = ?});
	&addStatement('game_db','26_total_select',q{select count(*) from strat_cp where cp_type != 5});

}

sub handleContent(){
	
	my $vars = shift(@_);
	
	my $count = &doSelect('26_ownership_select','array_row',$vars->{'country_id'});
	my $total = &doSelect('26_total_select','array_row');
		
	if($total > 0 and (($count / $total) * 100) < 10){
		return 1;
	}
	
	return 0;


}

1;
