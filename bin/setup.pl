#!/usr/bin/perl -w

## Standard Modules
use strict;
use Data::Dumper;
use lib ('/usr/local/community/events');
use Playnet::Config;
use Playnet::Database;

print "Initializing.\n";

my %sysvars = &initEvents();

if($sysvars{'OPTS'}->{'mode'} eq 'report')
{
	while(1)
	{
		print "Pending Missions: ".&doSelect('missions_pending_select')."\n";
		print "Pending Adjustments: ".&doSelect('adjustments_pending_select')."\n";
		sleep(3);
	}
}
else
{
	my $frame = &doSelect('frame_select','hashref_row', $sysvars{'OPTS'}->{'frame'});
	
	if($frame)
	{
		print "Setting up frame ".$frame->{'title'}."/".$sysvars{'OPTS'}->{'frame'}.".\n";
		print "Mode is ".$sysvars{'OPTS'}->{'mode'}.".\n";
		print "Practice: ".$sysvars{'OPTS'}->{'practice'}.".\n";
		print "Side is ".$sysvars{'SIDE'}."\n";
		
		if($sysvars{'OPTS'}->{'mode'} eq 'reset')
		{
			if($frame->{'frame_start'} > time)
			{
				print "Set frame back to planning.\n";
				&doUpdate('frame_update','Planning','True',$frame->{'registration'},$frame->{'frame_id'});
			}
			else
			{
				if(&doUpdate('frame_update','Completed','True','Closed',$frame->{'frame_id'}))
				{
					print "Set frame to be completed.\n";
					
					my $remaining_frames = &doSelect('remaining_frames_select','array_row',$frame->{'event_id'});
					
					if($remaining_frames == 0)
					{
						print "Set event to be completed.\n";
						&doUpdate('event_update','Completed','True',$frame->{'event_id'});
					}
				}
			}
			
			print "Dissasociating events.\n";
			&doUpdate('arena_associations_delete',$sysvars{'AID'}); 					# disassociate arenaid 22 from event
			
			print "Associating training.\n";
			
			#my @subscriptions = (6,7,9,13,17,19,20,21,22,23,25,26,27,28,29,30,37,38,39,41,42,43,44,45,46,47,48,54,55,56,57,58,60,61,62,63);
			#
			#foreach my $subscription (@subscriptions){
			#	&doUpdate('arena_association_insert',$subscription,$sysvars{'AID'});	# reassociate arenaid 22 with training
			#}
			
			&doUpdate('arena_association_insert',3,$sysvars{'AID'});					# reassociate arenaid 22 with training
			
			print "Deleting event subscriptions.\n";
			&doUpdate('subscriptions_delete',$sysvars{'SID'}); 							# delete all event subscriptions
			
			print "Deleting event chat groups.\n";
			&doUpdate('chat_groups_delete'); 											# delete all player permissions
			
			print "Setting ranks back to 1.\n";
			&doUpdate('ranks_update');													# reset all ranks to private
			
			print "Settings squads back to 0.\n";
			&doUpdate('squads_update');													# drop squads created by the frame
			
			if($sysvars{'OPTS'}->{'reset'})
			{
				print "Resetting strat.\n";
				&resetStrat();															# reset all events strat to start 
			}
			
			print "Resetting varables.\n";
			&resetVars();																# reset host vars
			
			## training is now available
		}
		elsif($sysvars{'OPTS'}->{'mode'} eq 'set')
		{
			###########################################################################
			###############  Place the frame in SETUP mode ############################
			###########################################################################
			
			if(!$sysvars{'OPTS'}->{'practice'})
			{
				print "Updated event to running when not in practice mode.\n";
				&doUpdate('event_update','Running','True',$frame->{'event_id'}); 		# event is running
			}
			
			print "Updated frame to setup.\n";
			&doUpdate('frame_update','Setup','True','Closed',$frame->{'frame_id'});	 	# frame is running
			
			print "Dissasociating training.\n";
			&doUpdate('arena_associations_delete',$sysvars{'AID'});						# disassociate arenaid 22 from training
			
			print "Associating events.\n";
			&doUpdate('arena_association_insert',$sysvars{'SID'},$sysvars{'AID'});		# associate arenaid 22 with the event
			
			print "Associating Bypass subscription.\n";
			&doUpdate('arena_association_insert','9',$sysvars{'AID'});					# associate arenaid 22 with bypass
			
			print "Deleting event chat groups.\n";
			&doUpdate('chat_groups_delete'); 											# delete all player permissions
			
			print "Deleting old vehicle supplies.\n";									# delete all previous vehicle supplies
			&doUpdate('supplies_delete');
			
			print "Setting up frame variables.\n";
			&setFrameVars($frame->{'frame_id'});										# set frame vars
			
			print "Doing vehicle adjustments.\n";										# set frame vehicle adjustments
			if(!$sysvars{'OPTS'}->{'novehicles'})
			{
				&setVehicleAdjustments($frame->{'frame_id'}, $sysvars{'SIDE'});
			}
			
			print "Doing mission assignments.\n";										# setup missions
			if(!$sysvars{'OPTS'}->{'nomissions'})
			{
				if($sysvars{'OPTS'}->{'practice'})
				{
					&setMissions($frame, $sysvars{'SIDE'}, time);
				}
				else
				{
					&setMissions($frame, $sysvars{'SIDE'}, $frame->{'frame_start'});
				}
			}
			
			print "Setting up SET Members.\n";
			&setSETMembers();
		}
		elsif($sysvars{'OPTS'}->{'mode'} eq 'play')
		{
			print "Updated frame to running.\n";
			&doUpdate('frame_update','Running','True','Closed',$frame->{'frame_id'});	# frame is running
			
			print "Setting up frame members.\n";
			&setFrameMembers($frame->{'frame_id'}, $sysvars{'SIDE'});					# sets up event frame members
		}
	}
	else
	{
		print "Frame not found.\n";
	}
}

print "Freeing databases.\n";

&freeEvents();

print "I'm done.\n";

exit(0);

sub initEvents()
{
	&initConfig('EVENTS_CONFIG','/usr/local/community/etc/events.conf');
	
	my %vars = &loadConfigSection('EVENTS_CONFIG', 'GLOBAL');
	
	my $opts = {
		'frame'			=> ['\d+','target frame id','f'],
		'mode'			=> ['\w+','setup mode: set|play|reset|report','m'],
		'practice'		=> ['flag','practice mode','p'],
		'side'			=> ['\d+','side to setup','s'],
		'reset'			=> ['flag','reset strat','r'],
		'nomissions'	=> ['flag','don\'t load missions','nm'],
		'novehicles'	=> ['flag','don\'t load vehicles','nv']
	};
	
	$vars{'OPTS'} = &getOpts($opts);
	
	if(!$vars{'OPTS'})
	{
		&getOptUsage($opts);
		exit;
	}
	
	### Add runtime variables
	
	$vars{'STAMP'} 			= time;
	$vars{'FRIENDLY_TIME'} 	= localtime(time);
	
	### Init Database
	
	my $ah_connect_str 		= "dbi:mysql:database=$vars{'AUTHDB'};host=$vars{'AUTHHOST'}";
	my $cm_connect_str 		= "dbi:mysql:database=community;host=66.28.224.237";
	my $evgm_connect_str 	= "dbi:mysql:database=$vars{'EVGMDB'};host=$vars{'EVGMHOST'}";
	
	if(!&addDatabase('auth_db',$ah_connect_str,$vars{'AUTHUSER'},$vars{'AUTHPASS'}))
	{
		die "Failed to add auth db!\n";
	}
	elsif(!&addDatabase('community_db',$cm_connect_str,'community','fun4all'))
	{
		die "Failed to add community db!\n";
	}
	elsif(!&addDatabase('events_game_db',$evgm_connect_str,$vars{'EVGMUSER'},$vars{'EVGMPASS'}))
	{
		die "Failed to add event db!\n";
	}
	
	##### event/frame selects
	&addStatement('community_db','frame_select',q{
		select f.*, e.event_id,
			UNIX_TIMESTAMP(f.start_time) as frame_start,
			UNIX_TIMESTAMP(f.stop_time) as frame_stop,
			'false' as practice,
			'All Forces' as participation
		from event_frames f,
			events e
		where f.frame_id = ?
			and f.event_id = e.event_id
		limit 1
	});
	
	&addStatement('community_db','remaining_frames_select',q{select count(*) from event_frames where event_id = ? and status != 'Completed'});
	&addStatement('community_db','frame_vars_select',q{SELECT v.*,efv.variable_id as frame_variable,efv.value as frame_value,if(efv.variable_id,"false","true") as use_default FROM event_vars v LEFT JOIN event_frame_vars efv ON (v.variableNo = efv.variable_id and efv.frame_id = ?) ORDER BY v.variableNo ASC});	
	&addStatement('community_db','best_vehicle_select',q{select ev.country_id,ev.category_id,ev.class_id,ev.type_id from event_frame_vehicles efv, event_vehicles ev where efv.mission_id = ? and efv.vehicle_id = ev.vehicle_id order by efv.spawns DESC limit 1});
	
	##### members select
	&addStatement('community_db','side_members_select',q{SELECT efm.member_id, efm.rank_id, efm.role_id, efm.mission_id, efc.default_rank, em.player_id, em.career, ec.country_id, ec.branch_id FROM event_frame_members efm, event_members em, event_command_units ecu, event_frame_commands efc, event_commands ec, event_countries c WHERE efm.frame_id = ? AND efm.mission_id > 0 AND efm.member_id = em.customer_id AND efm.unit_id = ecu.unit_id AND ecu.command_id = efc.command_id AND efc.frame_id = efm.frame_id AND efc.command_id = ec.command_id AND ec.country_id = c.country_id AND c.side_id = ?});
	&addStatement('community_db','members_select',q{SELECT efm.member_id,efm.rank_id,efm.role_id,efm.mission_id,efc.default_rank,em.player_id,em.career,ec.country_id,ec.branch_id FROM event_frame_members efm, event_members em, event_command_units ecu, event_frame_commands efc, event_commands ec WHERE efm.frame_id = ? AND efm.mission_id > 0 AND efm.member_id = em.customer_id AND efm.unit_id = ecu.unit_id AND ecu.command_id = efc.command_id AND efc.frame_id = efm.frame_id and efc.command_id = ec.command_id});
	
	##### event/frame updates
	&addStatement('community_db','event_update',q{update events set status = ?, committed = ?, modified = NOW() where event_id = ? limit 1});
	&addStatement('community_db','frame_update',q{update event_frames set status = ?, committed = ?, registration = ?, modified = NOW() where frame_id = ? limit 1});
	&addStatement('community_db','frame_units_update',q{update event_frame_units set registration = ?, modified = NOW() where frame_id = ?});
	
	##### event missions
	&addStatement('community_db','event_mission_update',q{update event_frame_missions set host_mission = ?, modified = NOW() where mission_id = ? limit 1});
	&addStatement('community_db','missions_select',q{select efm.mission_id,emt.title as title,efm.brief,efm.base_oid as launch_fac,efm.target_oid as target_fac,efm.priority_id as priority,emt.host_type as type,UNIX_TIMESTAMP(efu.launch) as launch_at,efu.base_oid as launch_cp,c.country_id as country,c.side_id as side,UNIX_TIMESTAMP(ef.start_time) as frame_start from event_frame_missions efm, event_frame_units efu, event_command_units ecu, event_commands ec, event_countries c, event_mission_types emt, event_frames ef where efm.frame_id = ? and efm.frame_id = efu.frame_id and efm.unit_id = efu.unit_id and efu.unit_id = ecu.unit_id and ecu.command_id = ec.command_id and ec.country_id = c.country_id and efm.type_id = emt.type_id and efm.frame_id = ef.frame_id});
	&addStatement('community_db','side_missions_select',q{select efm.mission_id,emt.title as title,efm.brief,efm.base_oid as launch_fac,efm.target_oid as target_fac,efm.priority_id as priority,emt.host_type as type,UNIX_TIMESTAMP(efu.launch) as launch_at,efu.base_oid as launch_cp,c.country_id as country,c.side_id as side,UNIX_TIMESTAMP(ef.start_time) as frame_start from event_frame_missions efm, event_frame_units efu, event_command_units ecu, event_commands ec, event_countries c, event_mission_types emt, event_frames ef where efm.frame_id = ? and efm.frame_id = efu.frame_id and efm.unit_id = efu.unit_id and efu.unit_id = ecu.unit_id and ecu.command_id = ec.command_id and ec.country_id = c.country_id and c.side_id = ? and efm.type_id = emt.type_id and efm.frame_id = ef.frame_id});
	
	##### event vehicle adjustments
	&addStatement('community_db','adjustments_select',q{select efv.spawns,ev.country_id,ev.category_id,ev.class_id,ev.type_id,efm.base_oid from event_frame_vehicles efv, event_vehicles ev, event_frame_missions efm where efv.frame_id = ? and efv.vehicle_id = ev.vehicle_id and efv.mission_id = efm.mission_id});
	&addStatement('community_db','side_adjustments_select',q{select efv.spawns,ev.country_id,ev.category_id,ev.class_id,ev.type_id,efm.base_oid from event_frame_vehicles efv, event_vehicles ev, event_frame_missions efm, event_command_units ecu, event_commands ec, event_countries c where efv.frame_id = ? and efv.vehicle_id = ev.vehicle_id and efv.mission_id = efm.mission_id and efm.unit_id = ecu.unit_id and ecu.command_id = ec.command_id and ec.country_id = c.country_id and c.side_id = ?});
	
	##### auth subscriptions
	&addStatement('auth_db','arena_associations_delete',q{delete from auth_subarenaassoc where arenaid = ?});
	&addStatement('auth_db','arena_association_insert',q{replace into auth_subarenaassoc (subscriptionid,arenaid) values (?,?)});
	&addStatement('auth_db','subscriptions_delete',q{delete from auth_subscribed where subscriptionid = ?});
	&addStatement('auth_db','subscription_insert',q{insert into auth_subscribed (customerid,subscriptionid,status) values (?,?,'active')});
	
	 ## TODO needs to take activity into account ##
	&addStatement('community_db','community_managers_select',q{select m.member_id as customerid, p.playerid from community_role_members m, wwii_player p where m.role_id = ? and p.customerid = m.member_id});
	
	##### reset default ranks
	&addStatement('events_game_db','ranks_update',q{update wwii_persona set rankid = '1'});
	&addStatement('events_game_db','squads_update',q{update wwii_player set squadid = '0'});
	&addStatement('events_game_db','persona_insert',q{insert ignore into wwii_persona (playerid,countryid,branchid,rankid,modified,added,usedmissnpts,rankpoints,personaid) values (?,?,?,?,NOW(),NOW(),'0','0',NULL)});
	&addStatement('events_game_db','rank_update',q{update wwii_persona set rankid = ? where playerid = ? and countryid = ? and branchid = ? limit 1});
	&addStatement('events_game_db','usepts_update',q{update wwii_persona set usedmissnpts = ? where playerid = ? limit 1});
	&addStatement('events_game_db','persona_ranks_update',q{update wwii_persona set rankid = ?, usedmissnpts = ? where playerid = ?});
	&addStatement('events_game_db','squad_update',q{update wwii_player set squadid = ? where playerid = ? limit 1});
	
	##### event reports
	&addStatement('events_game_db','missions_pending_select',q{select count(*) from ext_missions where pending = 1});
	&addStatement('events_game_db','adjustments_pending_select',q{select count(*) from ext_vehicle_adjust where pending = 1});
	
	##### game permissions
	&addStatement('events_game_db','chat_group_insert',q{insert ignore into chat_group_members values (?,?,NOW(),NOW())});
	&addStatement('events_game_db','chat_groups_delete',q{delete from chat_group_members where group_oid not in (1,2,3,17,18)});
	&addStatement('events_game_db','permission_insert',q{insert ignore into wwii_player_permiss values (?,?,NOW(),NOW())});
	&addStatement('events_game_db','permissions_delete',q{truncate wwii_player_permiss});
	
	##### reset original strat info
	&addStatement('events_game_db','cps_update',q{update strat_cp set side = originalside, country = originalcountry, conside = originalside, concountry = country, contention = 0});
	&addStatement('events_game_db','facilities_update',q{update strat_facility set side = originalside, country = originalcountry, open = 1, incomingrps = 0, storedrps = 0});
	&addStatement('events_game_db','objects_update',q{update strat_object set side = orignalside, country = originalcountry, damagejoules = 0});
	&addStatement('events_game_db','open_update',q{update strat_facility set open = 0 where facility_type = 7 && side != 2});
	&addStatement('events_game_db','supplies_delete',q{truncate strat_facility_supplies});
	&addStatement('events_game_db','adjustments_delete',q{truncate ext_vehicle_adjust});
	
	##### missions
	&addStatement('events_game_db','missions_delete',q{truncate ext_missions});
	&addStatement('events_game_db','mission_insert',q{replace into ext_missions (mission_id,pending,poster_id,posted_time,description,mission_type,country,side,launch_cp_oid,launch_fac_oid,target_cp_oid,target_fac_oid,post_at,launch_at,priority,vcountry,vcategory,vclass,vtype) values (?,1,?,NOW(),?,?,?,?,?,?,?,?,NOW(),FROM_UNIXTIME(?),?,?,?,?,?)});
	&addStatement('events_game_db','target_cp_select',q{select cp_oid from strat_facility where facility_oid = ? limit 1});
	
	##### vehicle adjustments
	
	&addStatement('events_game_db','adjustment_insert',q{insert into ext_vehicle_adjust (adjuid,schedule,wcountry,wcategory,wclass,wtype,fromlevel,tolevel,source,issued,pending,facility_ox,facility_oy,facility_id,delivery) values (NULL,NOW(),?,?,?,?,0,0,'ET Frame Loader',NOW(),1,?,?,?,?)});
	&addStatement('events_game_db','facility_select',q{select facility_ox,facility_oy,facility_id from strat_facility where facility_oid = ? limit 1});
	
	##### Set a config variable
	&addStatement('events_game_db','frame_var_update',q{update wwii_config set value=? where variableNo=? limit 1});
	
	$vars{'SIDE'} = ($vars{'OPTS'}->{'side'}) ? $vars{'OPTS'}->{'side'}: 0;
	
	return %vars;
}

sub freeEvents()
{
	&freeDatabases();
}

sub resetStrat()
{
	&doUpdate('cps_update');
	&doUpdate('facilities_update');
	&doUpdate('objects_update');
	&doUpdate('open_update');
	&doUpdate('supplies_delete');
}

sub resetVars(){
	
	my $variables = &doSelect('frame_vars_select','hashref_all','0');
	
	foreach my $variable (@{$variables})
	{
		&doUpdate('frame_var_update',$variable->{'value'},$variable->{'variableNo'});
	}
}

### Frame Setup

sub setFrameMembers()
{
	my $fid 	= shift(@_);
	my $side 	= shift(@_);
	
	my $members = ($side > 0) ?
		 &doSelect('side_members_select','hashref_all',$fid, $side):
		 &doSelect('members_select','hashref_all',$fid);
	
	foreach my $member (@{$members})
	{
		print "Doing frame member ".$member->{'member_id'}."\n";
		
		if(&doUpdate('subscription_insert',$member->{'member_id'},$sysvars{'SID'}))
		{
			my $rank_id = ($member->{'rank_id'} > 0) ?
				$member->{'rank_id'}:
				($member->{'default_rank'} > 0) ?
					$member->{'default_rank'}:
					0;
			
			&doUpdate('persona_insert',$member->{'player_id'},$member->{'country_id'},$member->{'branch_id'},1);
			
			# need to set usedmissnpts even if its not a default rank
			
			if($rank_id > 0)
			{
				&doUpdate('rank_update',$rank_id,$member->{'player_id'},$member->{'country_id'},$member->{'branch_id'});
			}
			
			&doUpdate('usepts_update',$sysvars{'USEDMPS'},$member->{'player_id'});
			&doUpdate('squad_update',$member->{'mission_id'},$member->{'player_id'});
			
			if($member->{'role_id'} > 2)
			{
				print "Inserting cinc/dcinc ".$member->{'country_id'}."/".$member->{'playerid'}."\n";
				
				if($member->{'country_id'} == 4)
				{
					&doUpdate('chat_group_insert',13,$member->{'player_id'});  # ghc+ group
				}
				else
				{
					&doUpdate('chat_group_insert',10,$member->{'player_id'});  # ahc+ group
				}
			}
		}
	}
}

sub setSETMembers()
{
	my $members = &doSelect('community_managers_select','hashref_all',2);
	
	foreach my $member (@{$members})
	{
		print "Doing set member ".$member->{'customerid'}."\n";
		
		&doUpdate('subscription_insert',$member->{'customerid'},$sysvars{'SID'});
		&doUpdate('persona_ranks_update',13,0,$member->{'playerid'});
		&doUpdate('squad_update',1,$member->{'playerid'});
		&doUpdate('chat_group_insert',3,$member->{'playerid'});  # gm chat group
		&doUpdate('chat_group_insert',17,$member->{'playerid'});  # sideswap chat group
		&doUpdate('chat_group_insert',18,$member->{'playerid'});  # buzzards chat group
		
		if($member->{'customerid'} == 3476 or $member->{'customerid'} == 246220)
		{
			&doUpdate('chat_group_insert',1,$member->{'playerid'});  # staff chat group
		}
	}
}

sub setFrameVars()
{
	my $variables = &doSelect('frame_vars_select','hashref_all',shift);
	
	foreach my $variable (@{$variables})
	{
		if($variable->{'use_default'} eq 'false')
		{
			&doUpdate('frame_var_update',$variable->{'frame_value'},$variable->{'variableNo'});
		}
		else
		{
			&doUpdate('frame_var_update',$variable->{'value'},$variable->{'variableNo'});
		}
	}
}

sub setVehicleAdjustments()
{
	my $fid 	= shift(@_);
	my $side 	= shift(@_);
	
	&doUpdate('adjustments_delete');
	
	my $adjustments = ($side > 0) ?
		&doSelect('side_adjustments_select','hashref_all',$fid, $side):
		&doSelect('adjustments_select','hashref_all',$fid);
	
	foreach my $adjust (@{$adjustments})
	{
		my $facility = &doSelect('facility_select','hashref_row',$adjust->{'base_oid'});
		
		&doUpdate('adjustment_insert',$adjust->{'country_id'},$adjust->{'category_id'},$adjust->{'class_id'},$adjust->{'type_id'},$facility->{'facility_ox'},$facility->{'facility_oy'},$facility->{'facility_id'},$adjust->{'spawns'});
	}
}

sub setMissions()
{
	my $frame 	= shift(@_);
	my $side 	= shift(@_);
	my $start	= shift(@_);
	
	&doUpdate('missions_delete');
	
	print "Select missions\n";
	
	my $missions = ($side > 0) ?
		&doSelect('side_missions_select','hashref_all',$frame->{'frame_id'}, $side):
		&doSelect('missions_select','hashref_all',$frame->{'frame_id'});
	
	foreach my $mission (@{$missions})
	{
		print "Doing mission ".$mission->{'mission_id'}."\n";
		
		print "     Getting Vehicle\n";
		
		my $vehicle 	= &doSelect('best_vehicle_select','hashref_row',$mission->{'mission_id'});
		my $description	= $mission->{'brief'};
		
		$description =~ s/<br>/\n/gi;
		$description =~ s/<[^>]+>//gi;
		
		print "     Checking target\n";
		
		if($mission->{'target_fac'} =~ /\d/ and $mission->{'target_fac'} > 0)
		{
			$mission->{'target_cp'} = &doSelect('target_cp_select','array_row',$mission->{'target_fac'});
		}
		else
		{
			$mission->{'target_cp'} 	= $mission->{'launch_cp'};
			$mission->{'target_fac'} 	= $mission->{'launch_fac'};
		}
		
		print "     Inserting mission\n";
		
		if($sysvars{'OPTS'}->{'practice'})
		{
			if(&doUpdate('mission_insert',$sysvars{'MISSIONID'},1027960,$description,($mission->{'type'} - 1),$mission->{'country'},$mission->{'side'},$mission->{'launch_cp'},$mission->{'launch_fac'},$mission->{'target_cp'},$mission->{'target_fac'},$start,$mission->{'priority'},$vehicle->{'country_id'},$vehicle->{'category_id'},$vehicle->{'class_id'},$vehicle->{'type_id'}))
			{
				&doUpdate('event_mission_update',$sysvars{'MISSIONID'},$mission->{'mission_id'});
			}
		}
		else
		{
			if(&doUpdate('mission_insert',$sysvars{'MISSIONID'},1027960,$description,($mission->{'type'} - 1),$mission->{'country'},$mission->{'side'},$mission->{'launch_cp'},$mission->{'launch_fac'},$mission->{'target_cp'},$mission->{'target_fac'},($start + ($mission->{'launch_at'} - $mission->{'frame_start'})),$mission->{'priority'},$vehicle->{'country_id'},$vehicle->{'category_id'},$vehicle->{'class_id'},$vehicle->{'type_id'}))
			{
				&doUpdate('event_mission_update',$sysvars{'MISSIONID'},$mission->{'mission_id'});
			}
		}
		
		$sysvars{'MISSIONID'}++;
	}
}

sub getOpts()
{
	my $config 		= shift;
	my @args		= @ARGV;
	my $parsed		= {};
	my $position 	= 0;
	
	chomp(@args);
	
	no strict;
	
	foreach my $opt (keys(%{$config}))
	{
		if($config->{$opt}->[0] eq 'flag')
		{
			$parsed->{$opt} = 0;
		}
	}
	
	foreach my $arg (@args)
	{
		if($arg =~ /^-/)
		{
			foreach my $opt (keys(%{$config}))
			{
				my $alias = $config->{$opt}->[2];
				
				if($alias and "-$alias" eq $arg)
				{
					$arg = "--$opt";
				}
			}
		}
	}
	
	foreach my $arg (@args)
	{
		if($arg =~ /^--(\w+)/)
		{
			my $opt = $1;
			
			if($config->{$opt})
			{
				if($config->{$opt}->[0] eq 'flag')
				{
					$parsed->{$opt} = 1;
				}
				else
				{
					my $value = $args[$position + 1];
					
					if($value and $value !~ /^-/ and $value =~ qr/$config->{$opt}->[0]/i)
					{
						$parsed->{$opt} = $value;
					}
				}
			}
		}
		
		$position++;
	}
	
	use strict;
	
	return (scalar(@args) > 0) ? $parsed: undef;
}

sub getOptUsage()
{
	my $config = shift;
	
	print "Usage setup.pl [options]\n\n";
	
	foreach my $opt (keys(%{$config}))
	{
		my $o 		= $config->{$opt};
		my $name 	= "--$opt" . (($o->[2]) ? ", -$o->[2]": '');
		
		printf("     %-20s%-s\n",$name,($o->[1]) ? $o->[1]: '');
	}
	
	print "\n";
	print "setup.pl -f 1 -m set -p -s 1        (put frame #1 into practice set mode for the allies)\n";
	print "setup.pl -f 1 -m play               (put frame #1 into play mode for all forces)\n";
	print "setup.pl -f 1 -m reset              (reset frame #1, save strat)\n\n";
}