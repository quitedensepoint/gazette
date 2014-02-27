package Playnet::Content::PLAYER_BestRecentSortie;

use strict;

use lib ('/usr/local/community/paper'); 
use Playnet::Database;
use Playnet::Misc;

INIT {
	
	### add handler
	$main::source_vars{'2'} = \&handleContent;
	
	### add statements
	#&addStatement('game_db','player_best_sortie_select',q{SELECT s.*,UNIX_TIMESTAMP(s.spawn_time) as spawned, UNIX_TIMESTAMP(s.return_time) as returned, p.callsign FROM wwii_sortie s, wwii_player p WHERE s.added > FROM_UNIXTIME(?) and s.player_id = p.playerid ORDER BY s.score DESC LIMIT 1});
	&addStatement('game_db','2_sorties_select',q{SELECT s.*,UNIX_TIMESTAMP(s.spawn_time) as spawned,UNIX_TIMESTAMP(s.return_time) as returned,p.callsign,p.customerid FROM wwii_sortie s, wwii_player p WHERE s.added > FROM_UNIXTIME(?) and s.captures > 0 and s.mission_id > 0 and kills > 2 and s.player_id = p.playerid ORDER BY s.score DESC});
	&addStatement('game_db','2_capture_select',q{select * from strat_captures where missionid = ? and customerid = ? and cust_country = ? limit 1});
	&addStatement('game_db','2_facility_select',q{select name from strat_facility where facility_id = ? and facility_ox = ? and facility_oy = ? limit 1});
	
}

## PLAYER
## RTB
## KILLS
## HITS
## DURATION

sub handleContent(){
	
	my $vars 	= shift(@_);
	
	my $sorties 	= &doSelect('2_sorties_select','hashref_all', (time - 7200));
	
	foreach my $sortie (@{$sorties}){
		
		my $capture = &doSelect('2_capture_select','hashref_row',$sortie->{'mission_id'},$sortie->{'player_id'},$vars->{'country_id'});
		
		if(defined($capture)){
			
			$vars->{'USER_ID'} 	= $sortie->{'customerid'};
			$vars->{'PLAYER'} 	= ucfirst(lc($sortie->{'callsign'}));
			$vars->{'RTB'} 		= &getRtb($sortie->{'rtb'});
			$vars->{'KILLS'}	= $sortie->{'kills'};
			$vars->{'HITS'}		= $sortie->{'vehicles_hit'};
			$vars->{'DURATION'}	= int(($sortie->{'returned'} - $sortie->{'spawned'}) / 60);
			$vars->{'CAPTURED'}	= &doSelect('2_facility_select','array_row',$capture->{'facility_id'},$capture->{'facility_ox'},$capture->{'facility_oy'});
			
			if(!defined($vars->{'CAPTURED'}) or $vars->{'CAPTURED'} =~ /^$/){
				$vars->{'CAPTURED'} = 'an enemy facility';
			}
			
			return 1;
		}
		
	}
	
	return 0;
	
}

sub getRtb(){
	
	my $rtb = shift(@_);
	
	if($rtb == 1){
		return 'Returned to Base';
	}
	elsif($rtb == 2){
		return 'Rescued';
	}
	elsif($rtb == 3){
		return 'Missing in Action';
	}
	else{
		return 'Killed in Action';
	}
}

1;
