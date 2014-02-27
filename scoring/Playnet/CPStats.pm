package Playnet::CPStats;

use strict;

require Exporter;

use vars qw(@ISA @EXPORT);

use lib ('/usr/local/community/scoring');
use Playnet::Database;

@ISA 		= qw(Exporter);
@EXPORT 	= qw(updateCPStats saveCPStats);

my $inited 			= 0;
my %cps 			= ();
my %hours 			= ();

sub updateCPStats(){
	
	my $sortie = shift(@_);
	
	&initQueries();
	
	my $cid = $sortie->{'cp_oid'};
	
	if(!exists($cps{$cid})){
		$cps{$cid} = &getCPStatsTemplate();
	}
	
	$cps{$cid}->{'sorties'} 								+= 1;
	$cps{$cid}->{'kills'} 									+= $sortie->{'kill_count'};
	$cps{$cid}->{'deaths'} 									+= $sortie->{'deaths'};
	$cps{$cid}->{'captures'} 								+= $sortie->{'capture_count'};
	$cps{$cid}->{'rtbs'} 									+= ($sortie->{'rtb'} == 0) ? 1: 0;
	$cps{$cid}->{'rescues'} 								+= ($sortie->{'rtb'} == 1) ? 1: 0;
	$cps{$cid}->{'mias'} 									+= ($sortie->{'rtb'} == 2) ? 1: 0;
	$cps{$cid}->{'kias'} 									+= ($sortie->{'rtb'} == 3) ? 1: 0;
	$cps{$cid}->{'tom'} 									+= $sortie->{'tom'};
	
	if($cps{$cid}->{'start'} == 0){
		$cps{$cid}->{'start'} = $sortie->{'start_time'};
	}
	
	$cps{$cid}->{'stop'} = $sortie->{'start_time'};
	
	my $hour = &calculateHour($sortie->{'start_time'});
	
	if(!exists($hours{$hour})){
		$hours{$hour} = &getCPStatsTemplate();
	}
	
	$hours{$hour}->{'sorties'} 								+= 1;
	$hours{$hour}->{'kills'} 								+= $sortie->{'kill_count'};
	$hours{$hour}->{'deaths'} 								+= $sortie->{'deaths'};
	$hours{$hour}->{'captures'} 							+= $sortie->{'capture_count'};
	$hours{$hour}->{'rtbs'} 								+= ($sortie->{'rtb'} == 0) ? 1: 0;
	$hours{$hour}->{'rescues'} 								+= ($sortie->{'rtb'} == 1) ? 1: 0;
	$hours{$hour}->{'mias'} 								+= ($sortie->{'rtb'} == 2) ? 1: 0;
	$hours{$hour}->{'kias'} 								+= ($sortie->{'rtb'} == 3) ? 1: 0;
	$hours{$hour}->{'tom'} 									+= $sortie->{'tom'};
	
}

sub saveCPStats(){
	
	foreach my $cid (keys(%cps)){
		
		#print "     Active CP $cid/".$cps{$cid}->{'sorties'}."/".$cps{$cid}->{'tom'}."\n";
		
		&doUpdate('campaign_activity_insert',$cid,$cps{$cid}->{'sorties'},$cps{$cid}->{'kills'},$cps{$cid}->{'deaths'},$cps{$cid}->{'captures'},$cps{$cid}->{'rtbs'},$cps{$cid}->{'rescues'},$cps{$cid}->{'mias'},$cps{$cid}->{'kias'},(($cps{$cid}->{'deaths'} > 0) ? ($cps{$cid}->{'kills'} / $cps{$cid}->{'deaths'}): $cps{$cid}->{'kills'}),$cps{$cid}->{'tom'},$cps{$cid}->{'start'},$cps{$cid}->{'stop'});
		
	}
	
	foreach my $hour (keys(%hours)){
		
		#print "     Active CP $cid/".$cps{$cid}->{'sorties'}."/".$cps{$cid}->{'tom'}."\n";
		
		if(!&doUpdate('hourly_activity_update',$hours{$hour}->{'sorties'},$hours{$hour}->{'kills'},$hours{$hour}->{'deaths'},$hours{$hour}->{'captures'},$hours{$hour}->{'rtbs'},$hours{$hour}->{'rescues'},$hours{$hour}->{'mias'},$hours{$hour}->{'kias'},$hours{$hour}->{'tom'},$hour)){
			&doUpdate('hourly_activity_insert',$hour,$hours{$hour}->{'sorties'},$hours{$hour}->{'kills'},$hours{$hour}->{'deaths'},$hours{$hour}->{'captures'},$hours{$hour}->{'rtbs'},$hours{$hour}->{'rescues'},$hours{$hour}->{'mias'},$hours{$hour}->{'kias'},$hours{$hour}->{'tom'});
		}
		
		&doUpdate('hourly_activity_kd_update',$hour);
		
	}
	
}

sub initQueries(){
	
	if(!$inited){
	
		## Spawns
		&addStatement('community_db','campaign_activity_insert',q{
			INSERT INTO scoring_campaign_activity (activity_id, cp_oid, sorties, kills, deaths, captures, rtbs, rescues, mias, kias, kd, tom, window_start, window_stop)
			VALUES (null,?,?,?,?,?,?,?,?,?,?,?,FROM_UNIXTIME(?),FROM_UNIXTIME(?))
		});
		
		&addStatement('community_db','hourly_activity_insert',q{
			INSERT INTO scoring_activity_hourly (hour_id, sorties, kills, deaths, captures, rtbs, rescues, mias, kias, tom)
			VALUES (?,?,?,?,?,?,?,?,?,?)
		});
		
		&addStatement('community_db','hourly_activity_update',q{
			UPDATE scoring_activity_hourly
			SET sorties = sorties + ?,
				kills = kills + ?,
				deaths = deaths + ?,
				captures = captures + ?,
				rtbs = rtbs + ?,
				rescues = rescues + ?,
				mias = mias + ?,
				kias = kias + ?,
				tom = tom + ?
			WHERE hour_id = ?
			LIMIT 1
		});
		
		&addStatement('community_db','hourly_activity_kd_update',q{
			UPDATE scoring_activity_hourly
			SET kd = if(deaths > 0, kills / deaths, kills),
				avg_spawn_rate = (sorties / ((FLOOR((UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(started)) / 86400) + 1) * 3600))
			WHERE hour_id = ?
			LIMIT 1
		});
		
		$inited = 1;
		
	}
}

sub getCPStatsTemplate(){

	my %template = ();
	
	$template{'sorties'} 	= 0;
	$template{'kills'} 		= 0;
	$template{'deaths'} 	= 0;
	$template{'captures'} 	= 0;
	$template{'rtbs'} 		= 0;
	$template{'rescues'} 	= 0;
	$template{'mias'} 		= 0;
	$template{'kias'} 		= 0;
	$template{'kd'} 		= 0;
	$template{'tom'} 		= 0;
	$template{'start'} 		= 0;
	$template{'stop'} 		= 0;
	
	return \%template;
	
}

sub calculateHour(){
	
	my @stamp = localtime(shift(@_));
	
	return $stamp[2];
	
}

1;
