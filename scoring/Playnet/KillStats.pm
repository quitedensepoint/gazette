package Playnet::KillStats;

use strict;

require Exporter;

use vars qw(@ISA @EXPORT);

use lib ('/usr/local/community/scoring');
use Playnet::Database;

@ISA 		= qw(Exporter);
@EXPORT 	= qw(updateKillStats saveKillStats);

my $inited 		= 0;
my %stats		= ();
my %global		= ('campaign' => 0);

sub updateKillStats(){
	
	my $pid = shift(@_);
	my $cid = shift(@_);
	
	&initQueries();
	
	if(!exists($stats{$pid})){
		$stats{$pid} = 0;
	}
	
	if(!exists($global{$cid})){
		$global{$cid} = 0;
	}
	
	$stats{$pid}		+= 1;
	$global{'campaign'}	+= 1;
	$global{$cid}		+= 1;
	
}

sub saveKillStats(){
	
	my $cid = shift(@_);
	
	foreach my $pid (keys(%stats)){
		
		#print "     Stat $pid/".$stats{$pid}->{'sorties'}."\n";
		
		if(!&doUpdate('persona_kills_update', $stats{$pid}, $cid, $pid)){
			&doUpdate('persona_kills_insert', $cid, $pid, $stats{$pid});
		}
		
		&doUpdate('persona_kills_kd_update', $cid, $pid);
		
	}
	
	## Might want to move this outside of the loop and accumulate campaign stats during the save phase
	&doUpdate('campaign_kills_update', $global{'campaign'}, $cid);
	
	foreach my $country (keys(%global)){
		
		if($country ne 'campaign'){
			
			if(!&doUpdate('country_kills_update', $global{$country}, $cid, $country)){
				&doUpdate('country_kills_insert', $cid, $country, $global{$country});
			}
			
			&doUpdate('country_kills_kd_update', $cid, $country);
			
		}
	}
	
	&doUpdate('campaign_kills_kd_update', $cid);
	
}

sub initQueries(){
	
	if(!$inited){
		
		&addStatement('community_db','persona_kills_insert',q{
			INSERT INTO scoring_campaign_personas (campaign_id, persona_id, kills, modified)
			VALUES (?,?,?,NOW())
		});
		
		&addStatement('community_db','persona_kills_update',q{
			UPDATE scoring_campaign_personas
			SET kills = kills + ?,
				modified = NOW()
			WHERE campaign_id = ? AND persona_id = ?
			LIMIT 1
		});
		
		&addStatement('community_db','country_kills_insert',q{
			INSERT INTO scoring_campaign_countries (campaign_id, country_id, kills, modified)
			VALUES (?,?,?,NOW())
		});
		
		&addStatement('community_db','country_kills_update',q{
			UPDATE scoring_campaign_countries
			SET kills = kills + ?,
				modified = NOW()
			WHERE campaign_id = ? AND country_id = ?
			LIMIT 1
		});
		
		&addStatement('community_db','campaign_kills_update',q{
			UPDATE scoring_campaigns
			SET kills = kills + ?
			WHERE campaign_id = ?
			LIMIT 1
		});
		
		&addStatement('community_db','persona_kills_kd_update',q{
			UPDATE scoring_campaign_personas
			SET kd = if(deaths > 0, round(kills / deaths, 2), kills)
			WHERE campaign_id = ? AND persona_id = ?
			LIMIT 1
		});

		&addStatement('community_db','country_kills_kd_update',q{
			UPDATE scoring_campaign_countries
			SET kd = if(deaths > 0, round(kills / deaths, 2), kills)
			WHERE campaign_id = ? AND country_id = ?
			LIMIT 1
		});
		
		&addStatement('community_db','campaign_kills_kd_update',q{
			UPDATE scoring_campaigns
			SET kd = if(deaths > 0, round(kills / deaths, 2), kills)
			WHERE campaign_id = ?
			LIMIT 1
		});
		
		$inited = 1;
		
	}
}

1;

