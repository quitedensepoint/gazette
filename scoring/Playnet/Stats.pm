package Playnet::Stats;

use strict;

require Exporter;

use vars qw(@ISA @EXPORT);

use lib ('/usr/local/community/scoring');
use Playnet::Database;

@ISA 		= qw(Exporter);
@EXPORT 	= qw(updateStats saveStats);

my $inited 		= 0;
my %stats		= ();
my %global		= ('campaign' => &getStatsTemplate());

sub updateStats(){
	
	my $sortie = shift(@_);
	
	&initQueries();
	
	my $pid = $sortie->{'persona'}->{'persona_id'};
	
	if(!exists($stats{$pid})){
		$stats{$pid} = &getStatsTemplate();
	}
	
	$stats{$pid}->{'sorties'} 	+= 1;
	$stats{$pid}->{'deaths'} 	+= $sortie->{'deaths'};
	$stats{$pid}->{'hits'} 		+= $sortie->{'hit_count'};
	$stats{$pid}->{'captures'} 	+= $sortie->{'capture_count'};
	$stats{$pid}->{'successes'} += $sortie->{'successful'};
	$stats{$pid}->{'rtbs'} 		+= (($sortie->{'rtb'} == 0) ? 1: 0);
	$stats{$pid}->{'rescues'} 	+= (($sortie->{'rtb'} == 1) ? 1: 0);
	$stats{$pid}->{'mias'} 		+= (($sortie->{'rtb'} == 2) ? 1: 0);
	$stats{$pid}->{'kias'} 		+= (($sortie->{'rtb'} == 3) ? 1: 0);
	$stats{$pid}->{'tom'} 		+= $sortie->{'tom'};
	
	$global{'campaign'}->{'sorties'} 	+= 1;
	$global{'campaign'}->{'deaths'} 	+= $sortie->{'deaths'};
	$global{'campaign'}->{'hits'} 		+= $sortie->{'hit_count'};
	$global{'campaign'}->{'captures'} 	+= $sortie->{'capture_count'};
	$global{'campaign'}->{'successes'} 	+= $sortie->{'successful'};
	$global{'campaign'}->{'rtbs'} 		+= (($sortie->{'rtb'} == 0) ? 1: 0);
	$global{'campaign'}->{'rescues'} 	+= (($sortie->{'rtb'} == 1) ? 1: 0);
	$global{'campaign'}->{'mias'} 		+= (($sortie->{'rtb'} == 2) ? 1: 0);
	$global{'campaign'}->{'kias'} 		+= (($sortie->{'rtb'} == 3) ? 1: 0);
	$global{'campaign'}->{'tom'} 		+= $sortie->{'tom'};
	
	if(!exists($global{$sortie->{'vehicle'}->{'country_id'}})){
		$global{$sortie->{'vehicle'}->{'country_id'}} = &getStatsTemplate();
	}
	
	$global{$sortie->{'vehicle'}->{'country_id'}}->{'sorties'} 		+= 1;
	$global{$sortie->{'vehicle'}->{'country_id'}}->{'deaths'} 		+= $sortie->{'deaths'};
	$global{$sortie->{'vehicle'}->{'country_id'}}->{'hits'} 		+= $sortie->{'hit_count'};
	$global{$sortie->{'vehicle'}->{'country_id'}}->{'captures'} 	+= $sortie->{'capture_count'};
	$global{$sortie->{'vehicle'}->{'country_id'}}->{'successes'} 	+= $sortie->{'successful'};
	$global{$sortie->{'vehicle'}->{'country_id'}}->{'rtbs'} 		+= (($sortie->{'rtb'} == 0) ? 1: 0);
	$global{$sortie->{'vehicle'}->{'country_id'}}->{'rescues'} 		+= (($sortie->{'rtb'} == 1) ? 1: 0);
	$global{$sortie->{'vehicle'}->{'country_id'}}->{'mias'} 		+= (($sortie->{'rtb'} == 2) ? 1: 0);
	$global{$sortie->{'vehicle'}->{'country_id'}}->{'kias'} 		+= (($sortie->{'rtb'} == 3) ? 1: 0);
	$global{$sortie->{'vehicle'}->{'country_id'}}->{'tom'} 			+= $sortie->{'tom'};
	
}

sub saveStats(){
	
	my $cid = shift(@_);
	
	foreach my $pid (keys(%stats)){
		
		#print "     Stat $pid/".$stats{$pid}->{'sorties'}."\n";
		
		if(!&doUpdate('campaign_persona_update',$stats{$pid}->{'sorties'},$stats{$pid}->{'deaths'},$stats{$pid}->{'hits'},$stats{$pid}->{'captures'},$stats{$pid}->{'successes'},$stats{$pid}->{'rtbs'},$stats{$pid}->{'rescues'},$stats{$pid}->{'mias'},$stats{$pid}->{'kias'},$stats{$pid}->{'tom'},$cid,$pid)){
			&doUpdate('campaign_persona_insert',$cid,$pid,$stats{$pid}->{'sorties'},$stats{$pid}->{'deaths'},$stats{$pid}->{'hits'},$stats{$pid}->{'captures'},$stats{$pid}->{'successes'},$stats{$pid}->{'rtbs'},$stats{$pid}->{'rescues'},$stats{$pid}->{'mias'},$stats{$pid}->{'kias'},$stats{$pid}->{'tom'});
		}
		
		&doUpdate('campaign_persona_kd_update',$cid,$pid);
		
	}
	
	## Might want to move this outside of the loop and accumulate campaign stats during the save phase
	&doUpdate('campaign_update',$global{'campaign'}->{'sorties'},$global{'campaign'}->{'deaths'},$global{'campaign'}->{'hits'},$global{'campaign'}->{'captures'},$global{'campaign'}->{'successes'},$global{'campaign'}->{'rtbs'},$global{'campaign'}->{'rescues'},$global{'campaign'}->{'mias'},$global{'campaign'}->{'kias'},$global{'campaign'}->{'tom'},$cid);
	
	foreach my $country (keys(%global)){
		
		if($country ne 'campaign'){
			
			if(!&doUpdate('campaign_country_update',$global{$country}->{'sorties'},$global{$country}->{'deaths'},$global{$country}->{'hits'},$global{$country}->{'captures'},$global{$country}->{'successes'},$global{$country}->{'rtbs'},$global{$country}->{'rescues'},$global{$country}->{'mias'},$global{$country}->{'kias'},$global{$country}->{'tom'},$cid,$country)){
				&doUpdate('campaign_country_insert',$cid,$country,$global{$country}->{'sorties'},$global{$country}->{'deaths'},$global{$country}->{'hits'},$global{$country}->{'captures'},$global{$country}->{'successes'},$global{$country}->{'rtbs'},$global{$country}->{'rescues'},$global{$country}->{'mias'},$global{$country}->{'kias'},$global{$country}->{'tom'});
			}
			
			&doUpdate('campaign_country_kd_update', $cid, $country);
			
		}
	}
	
	&doUpdate('campaign_kd_update', $cid);
	
}

sub initQueries(){
	
	if(!$inited){
		
		&addStatement('community_db','campaign_persona_insert',q{
			INSERT INTO scoring_campaign_personas (campaign_id, persona_id, sorties, deaths, hits, captures, successes, rtbs, rescues, mias, kias, tom, modified)
			VALUES (?,?,?,?,?,?,?,?,?,?,?,?,NOW())
		});
		
		&addStatement('community_db','campaign_persona_update',q{
			UPDATE scoring_campaign_personas
			SET sorties = sorties + ?,
				deaths = deaths + ?,
				hits = hits + ?,
				captures = captures + ?,
				successes = successes + ?,
				rtbs = rtbs + ?,
				rescues = rescues + ?,
				mias = mias + ?,
				kias = kias + ?,
				tom = tom + ?,
				modified = NOW()
			WHERE campaign_id = ? AND persona_id = ?
			LIMIT 1
		});
		
		&addStatement('community_db','campaign_country_insert',q{
			INSERT INTO scoring_campaign_countries (campaign_id, country_id, sorties, deaths, hits, captures, successes, rtbs, rescues, mias, kias, tom, modified)
			VALUES (?,?,?,?,?,?,?,?,?,?,?,?,NOW())
		});
		
		&addStatement('community_db','campaign_country_update',q{
			UPDATE scoring_campaign_countries
			SET sorties = sorties + ?,
				deaths = deaths + ?,
				hits = hits + ?,
				captures = captures + ?,
				successes = successes + ?,
				rtbs = rtbs + ?,
				rescues = rescues + ?,
				mias = mias + ?,
				kias = kias + ?,
				tom = tom + ?,
				modified = NOW()
			WHERE campaign_id = ? AND country_id = ?
			LIMIT 1
		});
		
		&addStatement('community_db','campaign_update',q{
			UPDATE scoring_campaigns
			SET sorties = sorties + ?,
				deaths = deaths + ?,
				hits = hits + ?,
				captures = captures + ?,
				successes = successes + ?,
				rtbs = rtbs + ?,
				rescues = rescues + ?,
				mias = mias + ?,
				kias = kias + ?,
				tom = tom + ?
			WHERE campaign_id = ?
			LIMIT 1
		});
		
		&addStatement('community_db','campaign_persona_kd_update',q{
			UPDATE scoring_campaign_personas
			SET kd = if(deaths > 0, round(kills / deaths, 2), kills)
			WHERE campaign_id = ? AND persona_id = ?
			LIMIT 1
		});

		&addStatement('community_db','campaign_country_kd_update',q{
			UPDATE scoring_campaign_countries
			SET kd = if(deaths > 0, round(kills / deaths, 2), kills)
			WHERE campaign_id = ? AND country_id = ?
			LIMIT 1
		});
		
		&addStatement('community_db','campaign_kd_update',q{
			UPDATE scoring_campaigns
			SET kd = if(deaths > 0, round(kills / deaths, 2), kills)
			WHERE campaign_id = ?
			LIMIT 1
		});
		
		$inited = 1;
		
	}
}

sub getStatsTemplate(){

	my %template = ();
	
	$template{'sorties'} 	= 0;
	$template{'deaths'} 	= 0;
	$template{'hits'} 		= 0;
	$template{'captures'} 	= 0;
	$template{'successes'} 	= 0;
	$template{'rtbs'} 		= 0;
	$template{'rescues'} 	= 0;
	$template{'mias'} 		= 0;
	$template{'kias'} 		= 0;
	$template{'tom'} 		= 0;
	
	return \%template;
	
}


1;

