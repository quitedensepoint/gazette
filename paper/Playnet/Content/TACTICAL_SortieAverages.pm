package Playnet::Content::TACTICAL_SortieAverages;

use strict;

use lib ('/usr/local/community/paper'); 
use Playnet::Database;
use Playnet::Misc;

INIT {
	
	### add handler
	$main::source_vars{'11'} = \&handleContent;
	
	### add statements
	&addStatement('game_db','11_sorties_select',q{select UNIX_TIMESTAMP(spawn_time) as spawned, UNIX_TIMESTAMP(return_time) as returned,kills,rtb,mission_id,mission_type,successful from wwii_sortie order by added DESC limit 1000});
	
}

## SORTIES
## AVERAGE_TIME

sub handleContent(){
	
	my $vars = shift(@_);
	
	$vars->{'SORTIES_TOTAL'}	= 0;
	$vars->{'AVERAGE_TIME'}		= 0;
	$vars->{'AVERAGE_KILLS'}	= 0;
	$vars->{'AVERAGE_RTB'}		= 0;
	$vars->{'AVERAGE_RESCUED'}	= 0;
	$vars->{'AVERAGE_MIA'}		= 0;
	$vars->{'AVERAGE_KIA'}		= 0;
	$vars->{'AVERAGE_ATTACK'}	= 0;
	$vars->{'AVERAGE_DEFENSE'}	= 0;
	$vars->{'AVERAGE_SUCCESS'}	= 0;
	
	my $missions				= 0;
	
	my $sorties = &doSelect('11_sorties_select','hashref_all');
	
	foreach my $sortie (@{$sorties}){
		
		$vars->{'SORTIES_TOTAL'}		+= 1;
		$vars->{'AVERAGE_TIME'}			+= ($sortie->{'returned'} - $sortie->{'spawned'});
		$vars->{'AVERAGE_KILLS'}		+= $sortie->{'kills'};
		$vars->{'AVERAGE_SUCCESS'}		+= $sortie->{'successful'};
		
		if($sortie->{'mission_id'} > 0){
			$missions						+= 1;
			$vars->{'AVERAGE_ATTACK'}		+= ($sortie->{'mission_type'} == 0 or $sortie->{'mission_type'} == 2) ? 1: 0;
			$vars->{'AVERAGE_DEFENSE'}		+= ($sortie->{'mission_type'} == 1 or $sortie->{'mission_type'} == 3) ? 1: 0;
		}
		
		$vars->{'AVERAGE_RTB'}			+= ($sortie->{'rtb'} == 0) ? 1: 0;
		$vars->{'AVERAGE_RESCUED'}		+= ($sortie->{'rtb'} == 1) ? 1: 0;
		$vars->{'AVERAGE_MIA'}			+= ($sortie->{'rtb'} == 2) ? 1: 0;
		$vars->{'AVERAGE_KIA'}			+= ($sortie->{'rtb'} == 3) ? 1: 0;
	}
	
	$vars->{'AVERAGE_TIME'} 	= int(($vars->{'AVERAGE_TIME'} / $vars->{'SORTIES_TOTAL'}) / 60);
	$vars->{'AVERAGE_KILLS'} 	= sprintf("%.2f", $vars->{'AVERAGE_KILLS'} / $vars->{'SORTIES_TOTAL'});
	$vars->{'AVERAGE_SUCCESS'} 	= int(($vars->{'AVERAGE_SUCCESS'} / $vars->{'SORTIES_TOTAL'}) * 100);
	$vars->{'AVERAGE_ATTACK'} 	= int(($vars->{'AVERAGE_ATTACK'} / $missions) * 100);
	$vars->{'AVERAGE_DEFENSE'} 	= int(($vars->{'AVERAGE_DEFENSE'} / $missions) * 100);
	$vars->{'AVERAGE_RTB'} 		= int(($vars->{'AVERAGE_RTB'} / $vars->{'SORTIES_TOTAL'}) * 100);
	$vars->{'AVERAGE_RESCUED'} 	= int(($vars->{'AVERAGE_RESCUED'} / $vars->{'SORTIES_TOTAL'}) * 100);
	$vars->{'AVERAGE_MIA'} 		= int(($vars->{'AVERAGE_MIA'} / $vars->{'SORTIES_TOTAL'}) * 100);
	$vars->{'AVERAGE_KIA'} 		= int(($vars->{'AVERAGE_KIA'} / $vars->{'SORTIES_TOTAL'}) * 100);
	
	return 1;
	
}

1;
