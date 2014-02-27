package Playnet::Content::HEADLINE_VictoryEminent;

use strict;

use lib ('/usr/local/community/paper'); 
use Playnet::Database;
use Playnet::Misc;

INIT {
	
	### add handler
	$main::source_vars{'43'} = \&handleContent;
	
	### add statements
	&addStatement('game_db','43_ownership_select',q{select count(*) from strat_cp where cp_type != 5 and country in (1,3,4) and side = ?});
	&addStatement('game_db','43_total_select',q{select count(*) from strat_cp where cp_type != 5 and country in (1,3,4)});
	
}

sub handleContent(){
	
	return 0;
	my $vars = shift(@_);
	
	my $count = &doSelect('43_ownership_select','array_row',$vars->{'side_id'});
	my $total = &doSelect('43_total_select','array_row');
	
	$count		= ($vars->{'side_id'} == 1) ? $count - 6: $count - 3;
	$total		= $total - 9;
	
	my $owned = int(($count / $total) * 100);
	
	print "VICTORY IMINENT: Side ".$vars->{'side_id'}." owns $owned percent of the CPs.\n";
	
	if($total > 0 and ($owned > 90 and $owned < 94)){
		
		$vars->{'SIDE_ADJ'} 		= ($vars->{'side_id'} == 1) ? 'Allied': 'Axis';
		$vars->{'ENEMY_SIDE_ADJ'} 	= ($vars->{'side_id'} == 1) ? 'Axis': 'Allied';
		
		return 0;
	}
	
	return 0;


}

1;
