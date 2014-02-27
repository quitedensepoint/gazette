#!/usr/bin/perl -w

## Standard Modules
use strict;

use lib ('/usr/local/community/scoring'); 
use Playnet::Database;

INIT {

  my $num_args = $#ARGV + 1;
  die "Need to enter last campaign number" unless $num_args == 1;
	
	if(!&addDatabase('community_db',"dbi:mysql:database=csr_community;host=localhost",'community','fun4all')){ #CP111713 changed csr to localhost
		die "Unable to connect to ScoringDB";
	}
	
	#########################################
	# Queries
	#########################################
	
	&addStatement('community_db','lock_tables',q{
		LOCK TABLES scoring_career_adversaries WRITE,
			scoring_career_mcversus WRITE,
			scoring_career_personas WRITE,
			scoring_career_spawns WRITE,
			scoring_vehicle_spawns_history WRITE,
			scoring_career_versus WRITE,
			scoring_vehicle_versus_history WRITE
	});
	
	&addStatement('community_db','unlock_tables',q{
		UNLOCK TABLES 
	});
	
	&addStatement('community_db','new_campaign_insert',q{
		INSERT INTO scoring_campaigns (campaign_id, title, start_time)
		SELECT campaign_id, 'Blitzkrieg', start_time
		FROM paper_campaigns
		WHERE status = 'Running'
		LIMIT 1
	});
	
	&addStatement('community_db','campaign_countries_insert',q{
		INSERT INTO scoring_campaign_countries (campaign_id, country_id, modified)
		SELECT ca.campaign_id, co.country_id, NOW()
		FROM paper_campaigns ca, paper_countries co
		WHERE ca.status = 'Running'
	});
	
	#&addStatement('community_db','campaign_adversaries_select',q{
	#	SELECT * FROM scoring_campaign_adversaries LIMIT 0, 1000
	#});

	#&addStatement('community_db','campaign_mcversus_select',q{
	#	SELECT * FROM scoring_campaign_mcversus LIMIT 0, 1000
	#});

	#&addStatement('community_db','campaign_personas_select',q{
	#	SELECT * FROM scoring_campaign_personas LIMIT 0, 1000
	#});
	
	#&addStatement('community_db','campaign_versus_select',q{
	#	SELECT * FROM scoring_campaign_versus LIMIT 0, 1000
	#});

	#&addStatement('community_db','campaign_spawns_select',q{
	#	SELECT * FROM scoring_campaign_spawns LIMIT 0, 1000
	#});

	&addStatement('community_db','vehicle_versus_select',q{
		SELECT * FROM scoring_vehicle_versus_campaign
	});

	&addStatement('community_db','vehicle_spawns_select',q{
		SELECT * FROM scoring_vehicle_spawns_campaign
	});
	
	&addStatement('community_db','career_campaigns_insert',q{
		INSERT INTO scoring_career_campaigns
		SELECT * FROM scoring_campaign_personas
	});
	
	&addStatement('community_db','career_adversary_insert',q{
		INSERT INTO scoring_career_adversaries (persona_id, opponent_persona_id, kills, deaths, kd, added, modified)
		VALUES (?,?,?,?,0,NOW(),NOW())
	});
	
	&addStatement('community_db','career_adversary_update',q{
		UPDATE scoring_career_adversaries
		SET kills = kills + ?,
			deaths = deaths + ?,
			modified = NOW()
		WHERE persona_id = ? AND opponent_persona_id = ?
		LIMIT 1
	});
	
	&addStatement('community_db','career_adversary_kd_update',q{
		UPDATE scoring_career_adversaries
		SET kd = if(deaths > 0, kills / deaths, kills)
		WHERE persona_id = ? AND opponent_persona_id = ?
		LIMIT 1
	});

	&addStatement('community_db','career_mcversus_insert',q{
		INSERT INTO scoring_career_mcversus (driver_id,gunner_id,vehicle_id,opponent_vehicle_id,kills,deaths,kd,modified)
		VALUES (?,?,?,?,?,?,0,NOW())
	});
	
	&addStatement('community_db','career_mcversus_update',q{
		UPDATE scoring_career_mcversus
		SET kills = ?,
			deaths = ?,
			modified = NOW()
		WHERE driver_id = ?
			AND gunner_id = ?
			AND vehicle_id = ?
			AND opponent_vehicle_id = ?
		LIMIT 1
	});
	
	&addStatement('community_db','career_mcversus_kd_update',q{
		UPDATE scoring_career_mcversus
		SET kd = if(deaths > 0, round(kills / deaths, 2), kills),
			modified = NOW()
		WHERE driver_id = ?
			AND gunner_id = ?
			AND vehicle_id = ?
			AND opponent_vehicle_id = ?
		LIMIT 1
	});
	
	&addStatement('community_db','career_persona_insert',q{
		INSERT INTO scoring_career_personas (persona_id, sorties, kills, deaths, hits, captures, successes, rtbs, rescues, mias, kias, tom, modified)
		VALUES (?,?,?,?,?,?,?,?,?,?,?,?,NOW())
	});
	
	&addStatement('community_db','career_persona_update',q{
		UPDATE scoring_career_personas
		SET sorties = sorties + ?,
			kills = kills + ?,
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
		WHERE persona_id = ?
		LIMIT 1
	});
	
	&addStatement('community_db','career_persona_kd_update',q{
		UPDATE scoring_career_personas
		SET kd = if(deaths > 0, round(kills / deaths, 2), kills)
		WHERE persona_id = ?
		LIMIT 1
	});
	
	&addStatement('community_db','career_spawn_insert',q{
		INSERT INTO scoring_career_spawns (persona_id, vehicle_id, sorties, rtbs, rescues, mias, kias, tom, modified)
		VALUES (?,?,?,?,?,?,?,?,NOW())
	});
	
	&addStatement('community_db','career_spawn_update',q{
		UPDATE scoring_career_spawns
		SET sorties = sorties + ?,
			rtbs = rtbs + ?,
			rescues = rescues + ?,
			mias = mias + ?,
			kias = kias + ?,
			tom = tom + ?,
			modified = NOW()
		WHERE persona_id = ? AND vehicle_id = ?
		LIMIT 1
	});
	
	## Spawns
	&addStatement('community_db','vehicle_spawn_insert',q{
		INSERT INTO scoring_vehicle_spawns_history (vehicle_id, sorties, rtbs, rescues, mias, kias, tom, modified)
		VALUES (?,?,?,?,?,?,?,NOW())
	});
	
	&addStatement('community_db','vehicle_spawn_update',q{
		UPDATE scoring_vehicle_spawns_history
		SET sorties = sorties + ?,
			rtbs = rtbs + ?,
			rescues = rescues + ?,
			mias = mias + ?,
			kias = kias + ?,
			tom = tom + ?,
			modified = NOW()
		WHERE vehicle_id = ?
		LIMIT 1
	
	});
	
	&addStatement('community_db','career_versus_insert',q{
		INSERT INTO scoring_career_versus (persona_id, vehicle_id, opponent_vehicle_id, kills, deaths, kd, modified)
		VALUES (?,?,?,?,?,0,NOW())
	});
	
	&addStatement('community_db','career_versus_update',q{
		UPDATE scoring_career_versus
		SET kills = kills + ?,
			deaths = deaths + ?,
			modified = NOW()
		WHERE persona_id = ? AND vehicle_id = ? AND opponent_vehicle_id = ?
		LIMIT 1
	});
	
	&addStatement('community_db','career_versus_kd_update',q{
		UPDATE scoring_career_versus
		SET kd = if(deaths > 0, kills / deaths, kills)
		WHERE persona_id = ? AND vehicle_id = ? AND opponent_vehicle_id = ?
		LIMIT 1
	});
	
	## Versus
	&addStatement('community_db','vehicle_versus_insert',q{
		INSERT INTO scoring_vehicle_versus_history (vehicle_id, opponent_vehicle_id, kills, deaths, kd, modified)
		VALUES (?,?,?,?,0,NOW())
	});
	
	&addStatement('community_db','vehicle_versus_update',q{
		UPDATE scoring_vehicle_versus_history
		SET kills = kills + ?,
			deaths = deaths + ?,
			modified = NOW()
		WHERE vehicle_id = ? AND opponent_vehicle_id = ?
		LIMIT 1
	});
	
	&addStatement('community_db','vehicle_versus_kd_update',q{
		UPDATE scoring_vehicle_versus_history
		SET kd = if(deaths > 0, kills / deaths, kills)
		WHERE vehicle_id = ? AND opponent_vehicle_id = ?
		LIMIT 1
	});
	
}

my %sysvars = &startScoring();

print "Dump current state (Y/n): ";

my $answer = <STDIN>;

if($answer =~ /y/i){
	print "Dumping database...\n";
	`/usr/local/mysql/bin/mysqldump --opt -ucommunity -pfun4all community | gzip > ./community.sql.gz`;
}

&doUpdate('lock_tables');

&truncateTable('scoring_campaign_aars');

&truncateTable('scoring_campaign_activity');

#&mergeAdversaries();

&truncateTable('scoring_campaign_captures');

print "Add new campaign country inserts...";

if(&doUpdate('campaign_countries_insert')){
	print "campaign_countries_insert: SUCCESS\n";
}
else{
	print "campaign_countries_insert: FAILURE\n";
}

&truncateTable('scoring_campaign_crews');

&truncateTable('scoring_campaign_kills');

&mergeMCVersus();

&mergePersonas();

&truncateTable('scoring_campaign_sorties');

&mergeSpawns();

&truncateTable('scoring_campaign_streaks');

&truncateTable('scoring_campaign_streak_bests');

&mergeVersus();

print "Add new campaign insert...";

if(&doUpdate('new_campaign_insert')){
	print "SUCCESS\n";
}
else{
	print "FAILURE\n";
}

&truncateTable('scoring_top_personas');

&mergeVehicleSpawns();

&mergeVehicleVersus();

#&doUpdate('unlock_tables');

&freeDatabases();

exit(0);

sub startScoring(){
	
  my %vars = ();
	
	$vars{'report'} 		= 0;
	$vars{'processed'} 		= 0;
	$vars{'campaign_id'} 	= $ARGV[0];
	
	chomp($vars{'campaign_id'});
	
	return %vars;
	
}

sub truncateTable(){
	
	my $table = shift;
	
	print "Truncating $table...";
	
	&doQuery('community_db', "truncate_$table", "truncate $table");
	
	print "SUCCESS\n";
}

sub mergeAdversaries(){
	
	print "Merging Adversaries...\n";
	
	&doQuery('community_db', 'adversaries_lock', 'LOCK TABLES scoring_campaign_adversaries READ, scoring_career_adversaries WRITE');
	&doQuery('community_db', 'adversaries_disable_keys', 'ALTER TABLE scoring_career_adversaries DISABLE KEYS');
	
	my $acount = 0;
	
	while(my $adversaries = &doQuery('community_db', 'campaign_adversaries_select', "SELECT * FROM scoring_campaign_adversaries LIMIT $acount, 1000", 'hashref_all')){
		
		if(scalar(@{$adversaries}) == 0){
			last;
		}
		
		foreach my $a (@{$adversaries}){
			
			if(!&doUpdate('career_adversary_update',$a->{'kills'},$a->{'deaths'},$a->{'persona_id'},$a->{'opponent_persona_id'})){
				if(&doUpdate('career_adversary_insert',$a->{'persona_id'},$a->{'opponent_persona_id'},$a->{'kills'},$a->{'deaths'})){
					$acount++;
				}
			}
			else{
				$acount++;
			}
			
			&doUpdate('career_adversary_kd_update',$a->{'persona_id'},$a->{'opponent_persona_id'});
			
			if(($acount % 10000) == 0){
				print "     $acount merged\n";
			}
		}
		
		&freeStatement('campaign_adversaries_select');
	}
	
	&doQuery('community_db', 'adversaries_enable_keys', 'ALTER TABLE scoring_career_adversaries ENABLE KEYS');
	
	&freeStatement('adversaries_disable_keys');
	&freeStatement('adversaries_enable_keys');
	&freeStatement('adversaries_lock');
	&doUpdate('unlock_tables');
	
	print "$acount merged\n";
	
	&truncateTable('scoring_campaign_adversaries');
}

sub mergeMCVersus(){
	
	print "Merging MC Versus...\n";
	
	&doQuery('community_db', 'mcversus_lock', 'LOCK TABLES scoring_campaign_mcversus READ, scoring_career_mcversus WRITE');
	
	my $mccount = 0;
	
	while(my $mcversus = &doQuery('community_db', 'campaign_mcversus_select', "SELECT * FROM scoring_campaign_mcversus LIMIT $mccount, 1000", 'hashref_all')){
		
		if(scalar(@{$mcversus}) == 0){
			last;
		}
		
		foreach my $mc (@{$mcversus}){
			
			if(!&doUpdate('career_mcversus_update',$mc->{'kills'},$mc->{'deaths'},$mc->{'driver_id'},$mc->{'gunner_id'},$mc->{'vehicle_id'},$mc->{'opponent_vehicle_id'})){
				if(&doUpdate('career_mcversus_insert',$mc->{'driver_id'},$mc->{'gunner_id'},$mc->{'vehicle_id'},$mc->{'opponent_vehicle_id'},$mc->{'kills'},$mc->{'deaths'})){
					$mccount++;
				}
			}
			else{
				$mccount++;
			}
			
			&doUpdate('career_mcversus_kd_update',$mc->{'driver_id'},$mc->{'gunner_id'},$mc->{'vehicle_id'},$mc->{'opponent_vehicle_id'});
			
			if(($mccount % 1000) == 0){
				print "     $mccount merged\n";
			}
		}
		
		&freeStatement('campaign_mcversus_select');
	}
	
	&freeStatement('mcversus_lock');
	&doUpdate('unlock_tables');
	
	print "$mccount merged\n";
	
	&truncateTable('scoring_campaign_mcversus');
}

sub mergePersonas(){
	
	print "Merging Persona Stats...\n";
	
	&doQuery('community_db', 'personas_lock', 'LOCK TABLES scoring_campaign_personas READ, scoring_career_personas WRITE');
	
	my $pcount = 0;
	
	while(my $personas = &doQuery('community_db', 'campaign_personas_select', "SELECT * FROM scoring_campaign_personas LIMIT $pcount, 1000", 'hashref_all')){
		
		if(scalar(@{$personas}) == 0){
			last;
		}
		
		foreach my $p (@{$personas}){
			
			if(!&doUpdate('career_persona_update',$p->{'sorties'},$p->{'kills'},$p->{'deaths'},$p->{'hits'},$p->{'captures'},$p->{'successes'},$p->{'rtbs'},$p->{'rescues'},$p->{'mias'},$p->{'kias'},$p->{'tom'},$p->{'persona_id'})){
				if(&doUpdate('career_persona_insert',$p->{'persona_id'},$p->{'sorties'},$p->{'kills'},$p->{'deaths'},$p->{'hits'},$p->{'captures'},$p->{'successes'},$p->{'rtbs'},$p->{'rescues'},$p->{'mias'},$p->{'kias'},$p->{'tom'})){
					$pcount++;
				}
			}
			else{
				$pcount++;
			}
			
			&doUpdate('career_persona_kd_update',$p->{'persona_id'});
			
			if(($pcount % 1000) == 0){
				print "     $pcount merged\n";
			}
		}
		
		&freeStatement('campaign_personas_select');
	}
	
	&freeStatement('personas_lock');
	&doUpdate('unlock_tables');
	
	print "$pcount merged\n";
	
	print "Saving career campaigns...";
	
	if(&doUpdate('career_campaigns_insert')){
		print "SUCCESS\n";
	}
	else{
		print "FAILURE\n";
	}
	
	&truncateTable('scoring_campaign_personas');
}

sub mergeSpawns(){
	
	print "Merging Persona Spawns...\n";
	
	&doQuery('community_db', 'spawns_lock', 'LOCK TABLES scoring_campaign_spawns READ, scoring_career_spawns WRITE');
	&doQuery('community_db', 'spawns_disable_keys', 'ALTER TABLE scoring_career_spawns DISABLE KEYS');
	
	my $pscount = 0;
	
	while(my $spawns = &doQuery('community_db', 'campaign_spawns_select', "SELECT * FROM scoring_campaign_spawns LIMIT $pscount, 1000", 'hashref_all')){
		
		if(scalar(@{$spawns}) == 0){
			last;
		}
		
		foreach my $ps (@{$spawns}){
			
			if(!&doUpdate('career_spawn_update',$ps->{'sorties'},$ps->{'rtbs'},$ps->{'rescues'},$ps->{'mias'},$ps->{'kias'},$ps->{'tom'},$ps->{'persona_id'},$ps->{'vehicle_id'})){
				if(&doUpdate('career_spawn_insert',$ps->{'persona_id'},$ps->{'vehicle_id'},$ps->{'sorties'},$ps->{'rtbs'},$ps->{'rescues'},$ps->{'mias'},$ps->{'kias'},$ps->{'tom'})){
					$pscount++;
				}
			}
			else{
				$pscount++;
			}
			
			if(($pscount % 10000) == 0){
				print "     $pscount merged\n";
			}
		}
		
		&freeStatement('campaign_spawns_select');
	}
	
	&doQuery('community_db', 'spawns_enable_keys', 'ALTER TABLE scoring_career_spawns ENABLE KEYS');
	
	&freeStatement('spawns_disable_keys');
	&freeStatement('spawns_enable_keys');
	&freeStatement('spawns_lock');
	&doUpdate('unlock_tables');
	
	print "$pscount merged\n";
	
	&truncateTable('scoring_campaign_spawns');
}

sub mergeVersus(){
	
	print "Merging Persona Versus...\n";
	
	&doQuery('community_db', 'versus_lock', 'LOCK TABLES scoring_campaign_versus READ, scoring_career_versus WRITE');
	&doQuery('community_db', 'versus_disable_keys', 'ALTER TABLE scoring_career_versus DISABLE KEYS');
	
	my $pvcount = 0;
	
	while(my $versus = &doQuery('community_db', 'campaign_versus_select', "SELECT * FROM scoring_campaign_versus LIMIT $pvcount, 1000", 'hashref_all')){
		
		if(scalar(@{$versus}) == 0){
			last;
		}
		
		foreach my $pv (@{$versus}){
			
			if(!&doUpdate('career_versus_update',$pv->{'kills'},$pv->{'deaths'},$pv->{'persona_id'},$pv->{'vehicle_id'},$pv->{'opponent_vehicle_id'})){
				if(&doUpdate('career_versus_insert',$pv->{'persona_id'},$pv->{'vehicle_id'},$pv->{'opponent_vehicle_id'},$pv->{'kills'},$pv->{'deaths'})){
					$pvcount++;
				}
			}
			else{
				$pvcount++;
			}
			
			&doUpdate('career_versus_kd_update',$pv->{'persona_id'},$pv->{'vehicle_id'},$pv->{'opponent_vehicle_id'});
			
			if(($pvcount % 10000) == 0){
				print "     $pvcount merged\n";
			}
		}
		
		&freeStatement('campaign_versus_select');
	}
	
	&doQuery('community_db', 'versus_enable_keys', 'ALTER TABLE scoring_career_versus ENABLE KEYS');
	
	&freeStatement('versus_disable_keys');
	&freeStatement('versus_enable_keys');
	&freeStatement('versus_lock');
	&doUpdate('unlock_tables');
	
	print "$pvcount merged\n";
	
	&truncateTable('scoring_campaign_versus');
}

sub mergeVehicleSpawns(){
	
	print "Merging Vehicle Spawns...\n";
	
	my $vspawns		= &doSelect('vehicle_spawns_select', 'hashref_all');
	my $vstotal		= scalar(@{$vspawns});
	my $vscount		= 0;
	
	foreach my $vs (@{$vspawns}){
		
		if(!&doUpdate('vehicle_spawn_update',$vs->{'sorties'},$vs->{'rtbs'},$vs->{'rescues'},$vs->{'mias'},$vs->{'kias'},$vs->{'tom'},$vs->{'vehicle_id'})){
			if(&doUpdate('vehicle_spawn_insert',$vs->{'vehicle_id'},$vs->{'sorties'},$vs->{'rtbs'},$vs->{'rescues'},$vs->{'mias'},$vs->{'kias'},$vs->{'tom'})){
				$vscount++;
			}
		}
		else{
			$vscount++;
		}
		
		if(($vscount % 1000) == 0){
			print "     $vscount of $vstotal merged\n";
		}
	}
	
	print "$vscount of $vstotal merged\n";
	
	&truncateTable('scoring_vehicle_spawns_campaign');
}

sub mergeVehicleVersus(){
	
	print "Merging Vehicle Versus...\n";
	
	my $vversus 	= &doSelect('vehicle_versus_select', 'hashref_all');
	my $vvtotal		= scalar(@{$vversus});
	my $vvcount		= 0;
	
	foreach my $vv (@{$vversus}){
		
		if(!&doUpdate('vehicle_versus_update',$vv->{'kills'},$vv->{'deaths'},$vv->{'vehicle_id'},$vv->{'opponent_vehicle_id'})){
			if(&doUpdate('vehicle_versus_insert',$vv->{'vehicle_id'},$vv->{'opponent_vehicle_id'},$vv->{'kills'},$vv->{'deaths'})){
				$vvcount++;
			}
		}
		else{
			$vvcount++;
		}
		
		&doUpdate('vehicle_versus_kd_update',$vv->{'vehicle_id'},$vv->{'opponent_vehicle_id'});
		
		if(($vvcount % 1000) == 0){
			print "     $vvcount of $vvtotal merged\n";
		}
	}
	
	print "$vvcount of $vvtotal merged\n";
	
	&truncateTable('scoring_vehicle_versus_campaign');
}

