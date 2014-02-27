package Playnet::Streaks;

use strict;

require Exporter;

use vars qw(@ISA @EXPORT);

use lib ('/usr/local/community/scoring');
use Playnet::Database;

@ISA 		= qw(Exporter);
@EXPORT 	= qw(updateStreaks saveStreaks);

my $inited 	= 0;
my %streaks	= ();

sub updateStreaks(){
	
	my $sortie = shift(@_);
	
	&initQueries();
	
	my $pid = $sortie->{'persona'}->{'persona_id'};
	
	if(!exists($streaks{$pid})){
		$streaks{$pid} = &doSelect('streaks_select', 'hashref_all', $pid, $pid, $pid);
	}
	
	foreach my $streak (@{$streaks{$pid}}){
		
		if($streak->{'streak_id'} == 1){
			$streak->{'current'} = $sortie->{'kill_count'}; 										## Max kills in a sortie
		}
		elsif($streak->{'streak_id'} == 2){
			$streak->{'current'} = $sortie->{'capture_count'}; 										## Max captures in a sortie
		}
		elsif($streak->{'streak_id'} == 3){
			$streak->{'current'} = $sortie->{'tom'}; 												## Longest sortie
		}
		elsif($streak->{'streak_id'} == 4){
			$streak->{'current'} = ($sortie->{'kill_count'} > 0) ? $streak->{'current'} + 1: 0; 	## Max consecutive sorties with 1 or more kills
		}
		elsif($streak->{'streak_id'} == 5){
			$streak->{'current'} = ($sortie->{'capture_count'} > 0) ? $streak->{'current'} + 1: 0; 	## Max consecutive sorties with 1 or more captures
		}
		elsif($streak->{'streak_id'} == 6){
			$streak->{'current'} = ($sortie->{'rtb'} == 0) ? $streak->{'current'} + 1: 0; 			## Max consecutive sorties with an RTB
		}
		elsif($streak->{'streak_id'} == 7){
			$streak->{'current'} = ($sortie->{'kill_count'} == 0) ? $streak->{'current'} + 1: 0; 	## Max consecutive sorties without a kill
		}
		elsif($streak->{'streak_id'} == 8){
			$streak->{'current'} = ($sortie->{'hit_count'} > 0) ? $streak->{'current'} + 1: 0; 		## Max consecutive sorties with a damaged enemy
		}
		elsif($streak->{'streak_id'} == 9){
			$streak->{'current'} = $sortie->{'hit_count'}; 											## Max hits in a sortie
		}
		elsif($streak->{'streak_id'} == 11){
			$streak->{'current'} = ($sortie->{'successful'} > 0) ? $streak->{'current'} + 1: 0; 	## Max consecutive successful sorties
		}
		
		if($streak->{'current'} > $streak->{'campaign_best'}){
			$streak->{'campaign_best'} 			= $streak->{'current'};
			$streak->{'campaign_best_achieved'}	= $sortie->{'start_time'};
			$streak->{'campaign_best_vehicle'}	= $sortie->{'vehicle'}->{'vehicle_id'};
			$streak->{'campaign_best_modified'} = 1;
		}
		
		if($streak->{'campaign_best'} > $streak->{'career_best'}){
			$streak->{'career_best'} 			= $streak->{'campaign_best'};
			$streak->{'career_best_achieved'}	= $sortie->{'start_time'};
			$streak->{'career_best_vehicle'}	= $sortie->{'vehicle'}->{'vehicle_id'};
			$streak->{'career_best_modified'} 	= 1;
		}
		
		$streak->{'current_achieved'} 	= $sortie->{'start_time'};
		$streak->{'current_vehicle'} 	= $sortie->{'vehicle'}->{'vehicle_id'};
		
	}
	
}

sub saveStreaks(){
	
	foreach my $pid (keys(%streaks)){
		
		foreach my $streak (@{$streaks{$pid}}){
			
			#print "     Streak $pid/".$streak->{'streak_id'}."/".$streak->{'career_best'}."/".$streak->{'campaign_best'}."/".$streak->{'current'}."\n";
			
			if(!&doUpdate('campaign_streak_update',$streak->{'current'},$streak->{'current_vehicle'},$streak->{'current_achieved'},$pid,$streak->{'streak_id'})){
				&doUpdate('campaign_streak_insert',$pid,$streak->{'streak_id'},$streak->{'current'},$streak->{'current_vehicle'},$streak->{'current_achieved'});
			}
			
			if(exists($streak->{'campaign_best_modified'})){
				
				if(!&doUpdate('campaign_best_streak_update',$streak->{'campaign_best'},$streak->{'campaign_best_vehicle'},$streak->{'campaign_best_achieved'},$pid,$streak->{'streak_id'})){
					&doUpdate('campaign_best_streak_insert',$pid,$streak->{'streak_id'},$streak->{'campaign_best'},$streak->{'campaign_best_vehicle'},$streak->{'campaign_best_achieved'});
				}
				
			}
			
			if(exists($streak->{'career_best_modified'})){
				
				if(!&doUpdate('career_streak_update',$streak->{'career_best'},$streak->{'career_best_vehicle'},$streak->{'career_best_achieved'},$pid,$streak->{'streak_id'})){
					&doUpdate('career_streak_insert',$pid,$streak->{'streak_id'},$streak->{'career_best'},$streak->{'career_best_vehicle'},$streak->{'career_best_achieved'});
				}
				
			}
		}
		
	}

}

sub initQueries(){
	
	if(!$inited){
		
		&addStatement('community_db','streaks_select',q{
			SELECT s.*,
				if(c.best,c.best,0) as career_best,
				if(cb.best,cb.best,0) as campaign_best,
				if(cc.current,cc.current,0) as current
			FROM scoring_streaks s
				LEFT JOIN scoring_career_streaks c ON (c.persona_id = ? AND c.streak_id = s.streak_id)
				LEFT JOIN scoring_campaign_streaks cc ON (cc.persona_id = ? AND cc.streak_id = s.streak_id)
				LEFT JOIN scoring_campaign_streak_bests cb ON (cb.persona_id = ? AND cb.streak_id = s.streak_id)
		});
		
		## Streaks
		&addStatement('community_db','career_streak_insert',q{
			INSERT INTO scoring_career_streaks (persona_id, streak_id, best, vehicle_id, achieved)
			VALUES (?,?,?,?,FROM_UNIXTIME(?))
		});
		
		&addStatement('community_db','career_streak_update',q{
			UPDATE scoring_career_streaks
			SET best = ?,
				vehicle_id = ?,
				achieved = FROM_UNIXTIME(?)
			WHERE persona_id = ? AND streak_id = ?
			LIMIT 1
		});
		
		&addStatement('community_db','campaign_best_streak_insert',q{
			INSERT INTO scoring_campaign_streak_bests (persona_id, streak_id, best, vehicle_id, achieved)
			VALUES (?,?,?,?,FROM_UNIXTIME(?))
		});
		
		&addStatement('community_db','campaign_best_streak_update',q{
			UPDATE scoring_campaign_streak_bests
			SET best = ?,
				vehicle_id = ?,
				achieved = FROM_UNIXTIME(?)
			WHERE persona_id = ?
				AND streak_id = ?
			LIMIT 1
		});
		
		&addStatement('community_db','campaign_streak_insert',q{
			INSERT INTO scoring_campaign_streaks (persona_id, streak_id, current, vehicle_id, achieved)
			VALUES (?,?,?,?,FROM_UNIXTIME(?))
		});
		
		&addStatement('community_db','campaign_streak_update',q{
			UPDATE scoring_campaign_streaks
			SET current = ?,
				vehicle_id = ?,
				achieved = FROM_UNIXTIME(?)
			WHERE persona_id = ?
				AND streak_id = ?
			LIMIT 1
		});
		
		$inited = 1;
		
	}
}

1;
