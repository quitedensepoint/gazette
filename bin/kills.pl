#!/usr/bin/perl -w

## Standard Modules
use strict;

use lib ('/usr/local/community/scoring'); 
use Playnet::Database;

use Playnet::Personas;
use Playnet::Vehicles;

use Playnet::KillStats;
use Playnet::Adversaries;
use Playnet::Versus;
use Playnet::MultiCrews;

our $LOGFILE;

INIT
{
  if (0)
	{ open(our $LOGFILE, ">&STDOUT"); }
  else
	{ open(our $LOGFILE, '>>', "/usr/local/community/logs/killsd.log") || die "problem opening log file\n"; }
  &useLogFile($LOGFILE);

  if(!&addDatabase('community_db',"dbi:mysql:database=community;host=localhost",'community','fun4all')) #CP111713 changed csr to localhost
	{
		die "Unable to connect to ScoringDB";
	}
	
	## Temporary Connection
	#if(!&addDatabase('temp_db',"dbi:mysql:database=servicesTEMP;host=training.playnet.com",'tomcat','bearcat')){
	#	die "Unable to connect to TempDB";
	#}
	
	if(!&addDatabase('game_db',"dbi:mysql:database=wwiionline;host=gamedbu.wwiionline.com",'wwiiol','freebird'))
	{
		die "Unable to connect to GameDB";
	}
	
	#########################################
	# Services Queries
	#########################################
	
	&addStatement('community_db','campaign_select',q{
		SELECT campaign_id
		FROM scoring_campaigns
		WHERE ISNULL(stop_time)
		ORDER BY start_time DESC
		LIMIT 1
	});
	
	&addStatement('community_db','last_check_select',q{
		SELECT max(kill_id)
		FROM scoring_campaign_kills
	});
	
	#&addStatement('community_db','invalid_kill_select',q{
	#	SELECT count(*) FROM scoring_campaign_kills WHERE kill_id = ?
	#});
	
	&addStatement('community_db','campaign_kill_insert',q{
		INSERT INTO scoring_campaign_kills (kill_id,sortie_id,opponent_sortie_id,vehicle_id,opponent_vehicle_id,kill_time)
		VALUES (?,?,?,?,?,?)
	});
	
	&addStatement('community_db','kill_persona_insert',q{
		INSERT INTO scoring_campaign_crews (kill_id,persona_id,position,perspective)
		VALUES (?,?,?,?)
	});
	
	#########################################
	# Game Queries
	#########################################
	
	# old
    #&addStatement('game_db','kills_select',q{
	#	SELECT STRAIGHT_JOIN k.kill_id,
	#		k.weapon_id as sortie_id,
	#		k.killer_0 as driver_id,
	#		k.killer_1 as gunner_id,
	#		k.target_vehicle as death_id,
	#		k.kill_value as value,
	#		k.veh_country as kill_country,
	#		k.veh_cat as kill_category,
	#		k.veh_class as kill_class,
	#		k.veh_type as kill_type,
	#		d.crew_0 as odriver_id,
	#		d.crew_1 as ogunner_id,
	#		d.veh_country as death_country,
	#		d.veh_cat as death_category,
	#		d.veh_class as death_class,
	#		d.veh_type as death_type,
	#		d.kill_time,
	#		d.weapon_id as opponent_sortie_id,
	#		UNIX_TIMESTAMP(d.kill_time) as kill_stamp
	#	FROM wwii_kill k,
	#		wwii_vehicle d
	#	WHERE k.kill_id > ?
	#		AND k.target_vehicle = d.vehicle_id
	#	ORDER BY k.kill_id ASC
	#	LIMIT 1000
    #});
	
   &addStatement('game_db','kills_select',q{
		SELECT STRAIGHT_JOIN k.kill_id,
			k.killer_sortie_id as sortie_id, # was weapon_id
			kc.player_0 as driver_id,
			kc.persona_0 as driver_persona_id,
			kc.player_1 as gunner_id,
			kc.persona_1 as gunner_persona_id,
			k.killer_country as kill_country,
			k.killer_category as kill_category,
			k.killer_class as kill_class,
			k.killer_type as kill_type,
			kv.player_0 as odriver_id,
			kv.persona_0 as odriver_persona_id,
			kv.player_1 as ogunner_id,
			kv.persona_1 as ogunner_persona_id,
			k.victim_country as death_country,
			k.victim_category as death_category,
			k.victim_class as death_class,
			k.victim_type as death_type,
			k.kill_time,
			k.victim_sortie_id as opponent_sortie_id,
			UNIX_TIMESTAMP(k.kill_time) as kill_stamp
		FROM wwii.kills k
			JOIN wwii.v_sortie_crew as kc
			JOIN wwii.v_sortie_crew as kv
		WHERE k.kill_id > ?
			AND kc.sortie_id = k.killer_sortie_id
			AND kv.sortie_id = k.victim_sortie_id
		ORDER BY k.kill_id ASC
		LIMIT 1000
    });
}

my %sysvars = &startScoring();

print $LOGFILE "Processing Kills starting at $sysvars{'last_check'}...\n";

my $kills_awarded 	= 0;
my $deaths_awarded 	= 0;

my $mckills_awarded 	= 0;
my $mcdeaths_awarded 	= 0;

my $participants		= 0;

my $kills = &doSelect('kills_select','hashref_all', $sysvars{'last_check'});

foreach my $k (@{$kills})
{
	#print $LOGFILE "Kill ".$k->{'kill_id'}." ...\n";
	
	#my $invalid = &doSelect('invalid_kill_select','array_row',$k->{'kill_id'});
	
	#if(!$invalid){
		
		#print $LOGFILE "VALID\n";
		
		# adjust kill_id
		
		$k->{'old_kill_id'} = $k->{'kill_id'};
		$k->{'kill_id'}		= $k->{'kill_id'} + $sysvars{'kill_id_offset'};
		
		my $driver		= {};
		my $gunner		= {};
		my $vehicle 	= {};
		
		my $odriver		= {};
		my $ogunner		= {};
		my $ovehicle 	= {};
		
		my $death1		= 0;
		my $death2		= 0;
		
		&loadVehicle($vehicle, $k->{'kill_country'}, $k->{'kill_category'}, $k->{'kill_class'}, $k->{'kill_type'});
		&loadVehicle($ovehicle, $k->{'death_country'}, $k->{'death_category'}, $k->{'death_class'}, $k->{'death_type'});
		
		if(!exists($vehicle->{'vehicle'}))
		{
			&pauseForInput('Killing Vehicle not found.');
		}
		
		if(!exists($ovehicle->{'vehicle'}))
		{
			&pauseForInput('Victim Vehicle not found.');
		}
		
		if($k->{'driver_persona_id'} > 0)
		{
			&loadPersonaByID($driver, $k->{'driver_persona_id'});
			
			if(!exists($driver->{'persona'}))
			{
				&pauseForInput('Killer Driver expected ('.$k->{'driver_persona_id'}.'), no persona found.');
			}
		}
		
		if($k->{'gunner_persona_id'} and $k->{'gunner_persona_id'} > 0)
		{
			&loadPersonaByID($gunner, $k->{'gunner_persona_id'});
			
			if(!exists($gunner->{'persona'}))
			{
				&pauseForInput('Killer Gunner expected ('.$k->{'gunner_persona_id'}.'), no persona found.');
			}
		}
		
		if($k->{'odriver_persona_id'} > 0)
		{
			&loadPersonaByID($odriver, $k->{'odriver_persona_id'});
			
			if(!exists($odriver->{'persona'}))
			{
				&pauseForInput('Victim Driver expected ('.$k->{'odriver_persona_id'}.'), no persona found.');
			}
		}
		
		if($k->{'ogunner_persona_id'} and $k->{'ogunner_persona_id'} > 0)
		{
			&loadPersonaByID($ogunner, $k->{'ogunner_persona_id'});
			
			if(!exists($ogunner->{'persona'}))
			{
				&pauseForInput('Victim Gunner expected ('.$k->{'ogunner_persona_id'}.'), no persona found.');
			}
		}
		
		if(exists($driver->{'persona'}))
		{
			&updateKillStats($driver->{'persona'}->{'persona_id'}, $k->{'kill_country'});
			
			if(exists($odriver->{'persona'}))
			{
				$kills_awarded++;
				$deaths_awarded++;
				&updateAdversaries($driver->{'persona'}->{'persona_id'}, $odriver->{'persona'}->{'persona_id'}, $vehicle->{'vehicle'}->{'vehicle_id'}, $ovehicle->{'vehicle'}->{'vehicle_id'});
				&updatePersonaVersus($driver->{'persona'}->{'persona_id'}, $odriver->{'persona'}->{'persona_id'}, $vehicle->{'vehicle'}->{'vehicle_id'}, $ovehicle->{'vehicle'}->{'vehicle_id'}, 0);
				$death1 = 1;
			}
			
			if(exists($ogunner->{'persona'}))
			{
				$kills_awarded++;
				$deaths_awarded++;
				&updateAdversaries($driver->{'persona'}->{'persona_id'}, $ogunner->{'persona'}->{'persona_id'}, $vehicle->{'vehicle'}->{'vehicle_id'}, $ovehicle->{'vehicle'}->{'vehicle_id'});
				&updatePersonaVersus($driver->{'persona'}->{'persona_id'}, $ogunner->{'persona'}->{'persona_id'}, $vehicle->{'vehicle'}->{'vehicle_id'}, $ovehicle->{'vehicle'}->{'vehicle_id'}, 0);
				$death2 = 1;
			}
			
		}
		
		if(exists($gunner->{'persona'}))
		{
			&updateKillStats($gunner->{'persona'}->{'persona_id'}, $k->{'kill_country'});
			
			if(exists($odriver->{'persona'}))
			{
				$kills_awarded++;
				
				if(!$death1)
				{
					$deaths_awarded++;
				}
				
				&updateAdversaries($gunner->{'persona'}->{'persona_id'}, $odriver->{'persona'}->{'persona_id'}, $vehicle->{'vehicle'}->{'vehicle_id'}, $ovehicle->{'vehicle'}->{'vehicle_id'});
				&updatePersonaVersus($gunner->{'persona'}->{'persona_id'}, $odriver->{'persona'}->{'persona_id'}, $vehicle->{'vehicle'}->{'vehicle_id'}, $ovehicle->{'vehicle'}->{'vehicle_id'}, $death1);
			}
			
			if(exists($ogunner->{'persona'}))
			{
				$kills_awarded++;
				
				if(!$death2)
				{
					$deaths_awarded++;
				}
				
				&updateAdversaries($gunner->{'persona'}->{'persona_id'}, $ogunner->{'persona'}->{'persona_id'}, $vehicle->{'vehicle'}->{'vehicle_id'}, $ovehicle->{'vehicle'}->{'vehicle_id'});
				&updatePersonaVersus($gunner->{'persona'}->{'persona_id'}, $ogunner->{'persona'}->{'persona_id'}, $vehicle->{'vehicle'}->{'vehicle_id'}, $ovehicle->{'vehicle'}->{'vehicle_id'}, $death2);
			}
		}
		
		&updateGlobalVersus($vehicle->{'vehicle'}->{'vehicle_id'}, $ovehicle->{'vehicle'}->{'vehicle_id'});
		
		if(exists($driver->{'persona'}) and exists($gunner->{'persona'}))
		{
			$mckills_awarded++;
			&updateMultiCrews($driver->{'persona'}->{'persona_id'}, $gunner->{'persona'}->{'persona_id'}, $vehicle->{'vehicle'}->{'vehicle_id'}, $ovehicle->{'vehicle'}->{'vehicle_id'}, 'kill');
		}
		
		if(exists($odriver->{'persona'}) and exists($ogunner->{'persona'}))
		{
			$mcdeaths_awarded++;
			&updateMultiCrews($odriver->{'persona'}->{'persona_id'}, $ogunner->{'persona'}->{'persona_id'}, $ovehicle->{'vehicle'}->{'vehicle_id'}, $vehicle->{'vehicle'}->{'vehicle_id'}, 'death');
		}
		
		# lets actually record the kill
		if(&doUpdate('campaign_kill_insert',$k->{'kill_id'},$k->{'sortie_id'},$k->{'opponent_sortie_id'},$vehicle->{'vehicle'}->{'vehicle_id'},$ovehicle->{'vehicle'}->{'vehicle_id'},$k->{'kill_time'}))
		{
			# lets add participants
			
			if(exists($driver->{'persona'}))
			{
				$participants++;
				&doUpdate('kill_persona_insert',$k->{'kill_id'},$driver->{'persona'}->{'persona_id'},'Driver','Killer');
			}
			
			if(exists($gunner->{'persona'}))
			{
				$participants++;
				&doUpdate('kill_persona_insert',$k->{'kill_id'},$gunner->{'persona'}->{'persona_id'},'Gunner','Killer');
			}
			
			if(exists($odriver->{'persona'}))
			{
				$participants++;
				&doUpdate('kill_persona_insert',$k->{'kill_id'},$odriver->{'persona'}->{'persona_id'},'Driver','Victim');
			}
			
			if(exists($ogunner->{'persona'}))
			{
				$participants++;
				&doUpdate('kill_persona_insert',$k->{'kill_id'},$ogunner->{'persona'}->{'persona_id'},'Gunner','Victim');
			}
		}
		
	#}
	#else{
		#print $LOGFILE "INVALID\n";
		
		#&pauseForInput('This sortie has already been scored');
		
	#}
	
	#&pauseForInput('In sortie loop, pausing for review.');
	
	$sysvars{'processed'}++;
	
}

## Lets add the collected stats

print $LOGFILE "Saving Counters...\n";

#&pauseForInput('Saving Versus Stats');
&saveKillStats($sysvars{'campaign_id'});

#&pauseForInput('Saving Versus Stats');
&saveVersus();

#&pauseForInput('Saving Adversaries');
&saveAdversaries();

#&pauseForInput('Saving Adversaries');
&saveMultiCrews();

print $LOGFILE "Processed ".$sysvars{'processed'}." kills this run.\n";

print $LOGFILE "$kills_awarded kills awarded.\n";
print $LOGFILE "$deaths_awarded deaths awarded.\n";
print $LOGFILE "$mckills_awarded MC kills awarded.\n";
print $LOGFILE "$mcdeaths_awarded MC deaths awarded.\n";
print $LOGFILE "$participants participants found.\n";

&freeDatabases();
close(LOGFILE);

exit(0);

sub startScoring()
{
	my %vars = ();
	
	$vars{'kill_id_offset'} = 0; # 1000000
	$vars{'report'} 		= 0;
	$vars{'cutoff'} 		= 14400;
	$vars{'processed'} 		= 0;
	$vars{'campaign_id'} 	= &doSelect('campaign_select','array_row');
	$vars{'last_check'} 	= &doSelect('last_check_select','array_row');
	
	if(!defined($vars{'last_check'}))
	{
		$vars{'last_check'} = 0;
	}
	
	if($vars{'kill_id_offset'} > 0)
	{
		$vars{'last_check'} = $vars{'last_check'} - $vars{'kill_id_offset'};
	}
	
	if($vars{'last_check'} < 0)
	{
		$vars{'last_check'} = 0;
	}
	
	return %vars;
	
}

sub pauseForInput()
{
	return;
	
	my $message = shift(@_);
	
	print "\nDebug Pause: $message\n\n";
	
	print "[PRESS ENTER TO CONTINUE]\n";
	
	<STDIN>;
	
}

sub makeDateString()
{
	my @stamp = localtime(shift(@_));
	
	#  0    1    2     3     4    5     6     7     8
    #($sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst) =
	#					localtime(time);  
	
	#'2003-01-25 04:55:34'
	
	return ($stamp[5] + 1900).'-'.($stamp[4] + 1).'-'.$stamp[3].' '.$stamp[2].':'.$stamp[1].':'.$stamp[0];
}
