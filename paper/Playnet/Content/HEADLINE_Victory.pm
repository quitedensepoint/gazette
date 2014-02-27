package Playnet::Content::HEADLINE_Victory;

use strict;

use lib ('/usr/local/community/paper'); 
use Playnet::Database;
use Playnet::Misc;

INIT {
	
	### add handler
	$main::source_vars{'44'} = \&handleContent;
	
	### add statements
	&addStatement('game_db','44_ownership_select',q{select count(*) from strat_cp where cp_type != 5 and country in (1,3,4) and side = ?});
	&addStatement('game_db','44_total_select',q{select count(*) from strat_cp where cp_type != 5 and country in (1,3,4)});
	
}

sub handleContent(){
	
	my $vars = shift(@_);
	
	my $count 	= &doSelect('44_ownership_select','array_row',$vars->{'side_id'});
	my $total 	= &doSelect('44_total_select','array_row');
	my $victory 	= &doSelect("paper_control_select",'array_row',"victory");

	#if($vars->{'side_id'} == 2)
	#{
	#	$vars->{'VICTORIOUS_SIDE'}      = 'Axis';
        #        $vars->{'SIDE_ADJ'}             = 'Axis';
        #        $vars->{'ENEMY_SIDE_ADJ'}       = 'Allied';
	#	
        #        return 1;
	#}
	#else
	#{
	#	return 0;
	#}
	
	$count		= ($vars->{'side_id'} == 1) ? $count - 6: $count - 3;
	$total		= $total - 9;
	
	my $owned = int(($count / $total) * 100);
	
	print "VICTORY: Side ".$vars->{'side_id'}." owns $owned percent of the CPs.\n";

	my $ownership = "";

	if($owned >= 50)
	{
		$ownership = ($vars->{'side_id'} == 1) ? "Allies own $owned percent": "Axis own $owned percent";
	}
	else
	{
		my $oowned = 100 - $owned;
		$ownership = ($vars->{'side_id'} == 1) ? "Axis own $oowned percent": "Allies own $oowned percent";
	}

	&doUpdate('paper_control_update','ownership',$ownership);
	
	if($total > 0 and $owned > int($victory)){
		
		print "Victory!\n";
		
		$vars->{'VICTORIOUS_SIDE'} 	= ($vars->{'side_id'} == 1) ? 'Allied': 'Axis';
		$vars->{'SIDE_ADJ'} 		= ($vars->{'side_id'} == 1) ? 'Allied': 'Axis';
		$vars->{'ENEMY_SIDE_ADJ'} 	= ($vars->{'side_id'} == 1) ? 'Axis': 'Allied';
		
		return 1;
	}
	elsif($total > 0 and $owned < (100 - int($victory))){
		
		print "Defeat!\n";
		
		$vars->{'VICTORIOUS_SIDE'} 	= ($vars->{'side_id'} == 1) ? 'Axis': 'Allied';
		$vars->{'SIDE_ADJ'} 		= ($vars->{'side_id'} == 1) ? 'Axis': 'Allied';
		$vars->{'ENEMY_SIDE_ADJ'} 	= ($vars->{'side_id'} == 1) ? 'Allied': 'Axis';
		
		return 1;
	}

	
	return 0;


}

1;
