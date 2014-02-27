package Playnet::Adversaries;

use strict;

require Exporter;

use vars qw(@ISA @EXPORT);

use lib ('/usr/local/community/scoring');
use Playnet::Database;

@ISA 		= qw(Exporter);
@EXPORT 	= qw(updateAdversaries saveAdversaries);

my $inited 		= 0;
my %adversaries = ();

sub updateAdversaries(){
	
	my $pid 	= shift(@_);
	my $kpid 	= shift(@_);
	my $vid 	= shift(@_);
	my $kvid 	= shift(@_);
	
	&initQueries();
	
	if(!exists($adversaries{$pid})){
		$adversaries{$pid} = {};
	}
	
	if(!exists($adversaries{$kpid})){
		$adversaries{$kpid} = {};
	}
	
	if(!exists($adversaries{$pid}->{$kpid})){
		$adversaries{$pid}->{$kpid} = {'kills' => 0, 'deaths' => 0, 'vehicle' => 0, 'ovehicle' => 0};
	}
	
	if(!exists($adversaries{$kpid}->{$pid})){
		$adversaries{$kpid}->{$pid} = {'kills' => 0, 'deaths' => 0, 'vehicle' => 0, 'ovehicle' => 0};
	}
	
	$adversaries{$pid}->{$kpid}->{'kills'} 		+= 1;
	$adversaries{$kpid}->{$pid}->{'deaths'} 	+= 1;
	
	$adversaries{$pid}->{$kpid}->{'vehicle'} 	= $vid;
	$adversaries{$pid}->{$kpid}->{'ovehicle'} 	= $kvid;
	
	$adversaries{$kpid}->{$pid}->{'vehicle'} 	= $kvid;
	$adversaries{$kpid}->{$pid}->{'ovehicle'} 	= $vid;
	
}

sub saveAdversaries(){
	
	foreach my $pid (keys(%adversaries)){
		
		foreach my $aid (keys(%{$adversaries{$pid}})){
			
			#print "     Adversary $pid/$aid/".$adversaries{$pid}->{$aid}->{'kills'}."/".$adversaries{$pid}->{$aid}->{'deaths'}."\n";
			
			my $adversary = $adversaries{$pid}->{$aid};
			
			if(!&doUpdate('campaign_adversary_update',$adversary->{'vehicle'},$adversary->{'ovehicle'},$adversary->{'kills'},$adversary->{'deaths'},$pid,$aid)){
				&doUpdate('campaign_adversary_insert',$pid,$aid,$adversary->{'vehicle'},$adversary->{'ovehicle'},$adversary->{'kills'},$adversary->{'deaths'});
			}
			
			&doUpdate('campaign_adversary_kd_update',$pid,$aid);
			
		}
	}

}

sub initQueries(){
	
	if(!$inited){
		
		&addStatement('community_db','campaign_adversary_insert',q{
			INSERT INTO scoring_campaign_adversaries (persona_id, opponent_persona_id, vehicle_id, opponent_vehicle_id, kills, deaths, kd, added, modified)
			VALUES (?,?,?,?,?,?,0,NOW(),NOW())
		});
		
		&addStatement('community_db','campaign_adversary_update',q{
			UPDATE scoring_campaign_adversaries
			SET vehicle_id = ?,
				opponent_vehicle_id = ?,
				kills = kills + ?,
				deaths = deaths + ?,
				modified = NOW()
			WHERE persona_id = ? AND opponent_persona_id = ?
			LIMIT 1
		});
		
		&addStatement('community_db','campaign_adversary_kd_update',q{
			UPDATE scoring_campaign_adversaries
			SET kd = if(deaths > 0, kills / deaths, kills)
			WHERE persona_id = ? AND opponent_persona_id = ?
			LIMIT 1
		});
		
		$inited = 1;
		
	}
}

1;
