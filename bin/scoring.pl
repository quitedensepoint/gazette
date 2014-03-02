#!/usr/bin/perl -w

## Standard Modules
use strict;

use lib ('/usr/local/community/scoring'); 
use Playnet::Database;

use Playnet::Personas;
use Playnet::Vehicles;

use Playnet::Stats;
use Playnet::Spawns;
use Playnet::CPStats;
use Playnet::Streaks;

our $LOGFILE;

INIT
{
  if (0)
	{ open(our $LOGFILE, ">&STDOUT"); }
  else
	{ open(our $LOGFILE, '>>', "/usr/local/community/logs/scoringd.log") || die "problem opening log file\n"; }

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
	# DAC-2013-06-29 Is DESC correct? this will go from highest to lowest campaign
	&addStatement('community_db','campaign_select',q{
		SELECT campaign_id
		FROM scoring_campaigns
		WHERE ISNULL(stop_time)
		ORDER BY start_time DESC 
		LIMIT 1
	});
	
	&addStatement('community_db','last_check_select',q{
		SELECT added
		FROM scoring_campaign_sorties
		WHERE campaign_id = ?
		ORDER BY added DESC
		LIMIT 1
	});
	
	&addStatement('community_db','invalid_sortie_select',q{
		SELECT count(*)
		FROM scoring_campaign_sorties
		WHERE sortie_id = ?
			AND persona_id = ?
	});
	
	&addStatement('community_db','campaign_sortie_insert',q{
		INSERT INTO scoring_campaign_sorties (sortie_id,persona_id,vehicle_id,campaign_id,country_id,mission_id,mission_type,result,score,origin_cp,origin_fac,kills,hits,captures,rtb,criticals,tom,kd,sortie_start,sortie_stop,favorite,added)
		VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,FROM_UNIXTIME(?),FROM_UNIXTIME(?),'false',FROM_UNIXTIME(?))
	});
	
	&addStatement('community_db','campaign_capture_insert',q{
		INSERT INTO scoring_campaign_captures (sortie_id,capture_id,capture_cp,capture_fac,capture_from,capture_time)
		VALUES (?,?,?,?,?,FROM_UNIXTIME(?))
	});	
	
	&addStatement('community_db','campaign_damage_insert',q{
		INSERT INTO scoring_campaign_damages (event_id,sortie_id,cp_oid,facility_oid,object,type,country,maxdamage,joules,damage,effect,added)
		VALUES (?,?,?,?,?,?,?,?,?,?,?,?)
	});	
	
	#########################################
	# Game Queries
	#########################################
	
	&addStatement('game_db','sorties_select',q{
		SELECT s.sortie_id,
			s.player_id,
			s.personaid as persona_id,
			UNIX_TIMESTAMP(s.spawn_time) as start_time,
			UNIX_TIMESTAMP(s.return_time) as stop_time,
			s.spawn_time,
			s.return_time,
			s.mission_type,
			s.mission_id,
			s.rtb,
			s.kills as kill_count,
			s.strat_hits as strat_hit_count,
			s.vehicles_hit as hit_count,
			s.captures as capture_count,
			s.capture_assists as capture_assist_count,
			s.resupplies,
			s.alt_f4ed,
			s.guarding_kills,
			s.capper_kills,
			s.score,
			s.successful,
			s.vcountry,
			s.vcategory,
			s.vclass,
			s.vtype,
			s.facility_oid,
			UNIX_TIMESTAMP(s.added) as added_time,
			p.*,
			f.cp_oid
		FROM wwii_sortie s
			JOIN wwii_player p
			JOIN strat_facility f
		WHERE !ISNULL(s.return_time)
			AND s.return_time >= ?
			AND s.player_id = p.playerid
			AND f.facility_oid = s.facility_oid
		ORDER BY s.return_time ASC
		LIMIT 10000
	});
	
	&addStatement('game_db','sorties_window_select',q{
		SELECT s.sortie_id,
			s.player_id,
			s.personaid as persona_id,
			UNIX_TIMESTAMP(s.spawn_time) as start_time,
			UNIX_TIMESTAMP(s.return_time) as stop_time,
			s.spawn_time,
			s.return_time,
			s.mission_type,
			s.mission_id,
			s.rtb,
			s.kills as kill_count,
			s.strat_hits as strat_hit_count,
			s.vehicles_hit as hit_count,
			s.captures as capture_count,
			s.capture_assists as capture_assist_count,
			s.resupplies,
			s.alt_f4ed,
			s.guarding_kills,
			s.capper_kills,
			s.score,
			s.successful,
			s.vcountry,
			s.vcategory,
			s.vclass,
			s.vtype,
			s.facility_oid,
			UNIX_TIMESTAMP(s.added) as added_time,
			p.*,
			f.cp_oid
		FROM wwii_sortie s
			JOIN wwii_player p
			JOIN strat_facility f
		WHERE s.added between ? and ?
			AND !ISNULL(s.return_time)
			AND s.player_id = p.playerid
			AND f.facility_oid = s.facility_oid
		ORDER BY s.added ASC
	});
	
	&addStatement('game_db','captures_select',q{
		SELECT c.capuid as capture_id,
			c.started_at as capture_time,
			c.facil_country,
			f.cp_oid,
			f.facility_oid
		FROM strat_captures c
			JOIN strat_facility f
			JOIN strat_cp cp
		WHERE c.customerid = ?
			AND c.started_at BETWEEN ? AND ?
			AND c.facility_oid = f.facility_oid
			AND f.cp_oid = cp.cp_oid
		ORDER BY c.started_at ASC
	});
	
	&addStatement('game_db','damages_select',q{
		SELECT d.*, c.cp_oid, o.name as object
		FROM strat_damage d
			JOIN strat_facility f
			JOIN strat_cp c
			JOIN strat_object o
		WHERE d.sortie_id = ?
			AND f.facility_oid = d.facility_oid
			AND c.cp_oid = f.cp_oid
			AND o.object_id = d.object_id
			AND o.object_ox = d.object_ox
			AND o.object_oy = d.object_oy
		ORDER BY d.added ASC
	});
	
	#AND c.facility_id = f.facility_id
	#AND c.facility_ox = f.facility_ox
	#AND c.facility_oy = f.facility_oy
	
	&addStatement('game_db','kills_select',q{
		SELECT count(*) as kills
		FROM wwii.kills
		WHERE killer_sortie_id = ?
	});
	
	#&addStatement('temp_db','temp_kills_select',q{
	#	SELECT count(*) as kills
	#	FROM wwii_kill
	#	WHERE weapon_id = ?
	#});
	
	&addStatement('game_db','criticals_select',q{
		SELECT count(*) as kills
		FROM wwii.kills
		WHERE victim_sortie_id = ?
	});
	
	#&addStatement('temp_db','temp_criticals_select',q{
	#	SELECT count(*) as deaths
	#	FROM wwii_vehicle
	#	WHERE weapon_id = ?
	#});
}

my %sysvars = &startScoring();

print $LOGFILE "Processing Sorties (NEW)...$sysvars{'last_check'}\n";

chomp(@ARGV);

my $sorties = (defined($ARGV[0]) and defined($ARGV[1])) ? &doSelect('sorties_window_select','hashref_all', $ARGV[0], $ARGV[1]): &doSelect('sorties_select','hashref_all', $sysvars{'last_check'});

foreach my $sortie (@{$sorties})
{
	#print $LOGFILE "Sortie ".$sortie->{'sortie_id'}.": ";
	
	#print $LOGFILE "VALID\n";
	
	if(&loadVehicle($sortie, $sortie->{'vcountry'}, $sortie->{'vcategory'}, $sortie->{'vclass'}, $sortie->{'vtype'}))
	{
		#print $LOGFILE "     Vehicle ".$sortie->{'vehicle'}->{'vehicle_id'}."\n";
		
		if(&loadPersonaByID($sortie, $sortie->{'persona_id'}))
		{
			my $invalid = &doSelect('invalid_sortie_select','array_row',$sortie->{'sortie_id'}, $sortie->{'persona'}->{'persona_id'});
			
			if(!$invalid)
			{
				#print $LOGFILE "     Persona ".$sortie->{'persona'}->{'persona_id'}."\n";
				
				$sortie->{'tom'} 		= int(($sortie->{'stop_time'} - $sortie->{'start_time'}) / 60);
				
				my $kills 				= &doSelect('kills_select', 'array_row', $sortie->{'sortie_id'});
				#my $temp_kills			= &doSelect('temp_kills_select', 'array_row', $sortie->{'sortie_id'});
				
				my $criticals 			= &doSelect('criticals_select', 'array_row', $sortie->{'sortie_id'});
				#my $temp_criticals		= &doSelect('temp_criticals_select', 'array_row', $sortie->{'sortie_id'});
				
				$sortie->{'kill_count'} = $kills; # + $temp_kills;
				$sortie->{'criticals'} 	= $criticals; # + $temp_criticals;
				#$sortie->{'kill_count'} = $kills;
				#$sortie->{'criticals'} 	= $criticals;
				$sortie->{'deaths'} 	= ($sortie->{'rtb'} == 3 or $sortie->{'criticals'} > 0) ? 1: 0;
				$sortie->{'kd'} 		= ($sortie->{'deaths'} > 0) ? ($sortie->{'kill_count'} / $sortie->{'deaths'}): $sortie->{'kill_count'};
				
				### add sortie
				if(&doUpdate('campaign_sortie_insert',$sortie->{'sortie_id'},$sortie->{'persona'}->{'persona_id'},$sortie->{'vehicle'}->{'vehicle_id'},$sysvars{'campaign_id'},$sortie->{'vehicle'}->{'country_id'},$sortie->{'mission_id'},$sortie->{'mission_type'},$sortie->{'successful'},$sortie->{'score'},$sortie->{'cp_oid'},$sortie->{'facility_oid'},$sortie->{'kill_count'},$sortie->{'hit_count'},$sortie->{'capture_count'},$sortie->{'rtb'},$sortie->{'criticals'},$sortie->{'tom'},$sortie->{'kd'},$sortie->{'start_time'},$sortie->{'stop_time'},$sortie->{'added_time'}))
				{
					#print $LOGFILE "     Sortie inserted.\n";
					
					if($sortie->{'capture_count'} > 0)
					{
						#print $LOGFILE "     Processing Captures: ".$sortie->{'capture_count'}."\n";
						
						$sortie->{'captures'} = &doSelect('captures_select','hashref_all',$sortie->{'playerid'},$sortie->{'start_time'},$sortie->{'stop_time'} + $sysvars{'cutoff'});
						
						my $ccount = 0;
						
						foreach my $capture (@{$sortie->{'captures'}})
						{
							#if(!exists($used_captures{$capture->{'capture_id'}})){
								
								#print $LOGFILE "          Capture ".$capture->{'capture_id'}.": ";
								
								if(&doUpdate('campaign_capture_insert',$sortie->{'sortie_id'},$capture->{'capture_id'},$capture->{'cp_oid'},$capture->{'facility_oid'},$capture->{'facil_country'},$capture->{'capture_time'}))
								{
									#print $LOGFILE "INSERTED\n";
									
									#$used_captures{$capture->{'capture_id'}} = 1;
									
									$capture->{'used_capture'} = 1;
									
									$ccount++;
									
									if($ccount >= $sortie->{'capture_count'})
									{
										last;
									}
								}
								else
								{
									#print $LOGFILE "NOT INSERTED\n";
								}
								
								#&pauseForInput('In capture loop, pausing for error.');
							#}
						}
						
						if($ccount != $sortie->{'capture_count'})
						{
							#print $LOGFILE "          WARNING: $ccount captures found, ".$sortie->{'capture_count'}." captures expected.\n";
							&pauseForInput('Not enough captures found');
						}
					}
						
					if($sortie->{'strat_hit_count'} > 0)
					{
						#print $LOGFILE "     Processing Damages: ".$sortie->{'strat_hit_count'}."\n";
						
						$sortie->{'damages'} = &doSelect('damages_select','hashref_all',$sortie->{'sortie_id'});
						
						foreach my $damage (@{$sortie->{'damages'}})
						{
							if(&doUpdate('campaign_damage_insert',$damage->{'event_id'},$sortie->{'sortie_id'},$damage->{'cp_oid'},$damage->{'facility_oid'},$damage->{'object'},$damage->{'object_type'},$damage->{'object_country'},$damage->{'object_maxdamage'},$damage->{'damage_joules'},$damage->{'applied_joules'},$damage->{'effect_level'},$damage->{'added'}))
							{
								#print $LOGFILE "INSERTED\n";
							}
							else
							{
								#print $LOGFILE "NOT INSERTED\n";
							}
						}
					}
					
					&updateStats($sortie);
					&updateCPStats($sortie);
					&updateStreaks($sortie);
					&updateSpawns($sortie);
					
					#&pauseForInput('Pausing for sortie review');
				}
			}
			else
			{
				#print $LOGFILE "INVALID\n";
				#&pauseForInput('This sortie has already been scored');
			}
		}
		else
		{
			#print $LOGFILE "     Persona: No Persona found (".$sortie->{'playerid'}."/".$sortie->{'vehicle'}->{'country_id'}."/".$sortie->{'vehicle'}->{'branch_id'}.")\n";
			&pauseForInput('An incomplete sortie was found: Bad Persona');
		}
	}
	else
	{
		#print $LOGFILE "     Vehicle: No vehicle found (".$sortie->{'vcountry'}."/".$sortie->{'vcategory'}."/".$sortie->{'vclass'}."/".$sortie->{'vtype'}.")\n";
		&pauseForInput('An incomplete sortie was found: Bad Vehicle');
	}
	
	#&pauseForInput('In sortie loop, pausing for review.');
	
	$sysvars{'processed'}++;
}

## Lets add the collected stats

print $LOGFILE "Saving Counters...\n";

#&pauseForInput('Saving Persona Stats');
&saveStats($sysvars{'campaign_id'});

#&pauseForInput('Saving CP Stats');
&saveCPStats();

#&pauseForInput('Saving Streaks');
&saveStreaks();

#&pauseForInput('Saving Spawn Stats');
&saveSpawns();

print $LOGFILE "Processed ".$sysvars{'processed'}." sorties this run for campaign ".$sysvars{'campaign_id'}.".\n";

&freeDatabases();

exit(0);

sub startScoring()
{
	my %vars = ();
	
	$vars{'report'} 		= 0;
	$vars{'cutoff'} 		= 14400;
	$vars{'processed'} 		= 0;
	$vars{'campaign_id'} 	= &doSelect('campaign_select','array_row');
	$vars{'last_check'} 	= &doSelect('last_check_select','array_row',$vars{'campaign_id'});
	
	if(!defined($vars{'last_check'}))
	{
		$vars{'last_check'} = '1970-01-01 00:00:00';
		#$vars{'last_check'} = '2005-02-26 12:00:00';
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
