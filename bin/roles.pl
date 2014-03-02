#!/usr/bin/perl -w

## Standard Modules
use strict;

use lib ('/usr/local/community/scoring'); 
use Playnet::Database;

INIT
{
	if(!&addDatabase('community_db',"dbi:mysql:database=community;host=localhost",'community','fun4all')){ #CP1111713 point to localhost rather than csr
		die "Unable to connect to ScoringDB";
	}
	
	if(!&addDatabase('game_db',"dbi:mysql:database=wwiionline;host=gamedbu.wwiionline.com",'wwiiol','freebird')){
		die "Unable to connect to GameDB";
	}

	if(!&addDatabase('event_db',"dbi:mysql:database=wwiionline;host=66.28.224.188",'wwiiol','freebird')){
		die "Unable to connect to EventDB";
	}
	
	#########################################
	# Queries
	#########################################
	
	&addStatement('community_db','jobs_select',q{
		SELECT * FROM community_sync
	});
	
	&addStatement('community_db','job_delete',q{
		DELETE FROM community_sync
		WHERE member_id = ?
		LIMIT 1
	});
	
	&addStatement('community_db','roles_select',q{
		SELECT m.member_id, r.*
		FROM community_role_members m,
			community_roles r
		WHERE m.member_id = ?
			AND r.role_id = m.role_id
	});
	
	&addStatement('community_db','rules_select',q{
		SELECT *
		FROM community_role_rules
		WHERE role_id = ?
	});
	
	&addStatement('game_db','player_select',q{
		SELECT *
		FROM wwii_player
		WHERE customerid = ?
		LIMIT 1
	});
	
	&addStatement('event_db','1_swapper_insert',q{
		INSERT IGNORE INTO wwii_player_permiss (playerid, permissionid, added, modified)
		VALUES (?,20,NOW(),NOW())
	});
	
	&addStatement('game_db','3_swapper_insert',q{
		INSERT IGNORE INTO wwii_player_permiss (playerid, permissionid, added, modified)
		VALUES (?,20,NOW(),NOW())
	});
	
	&addStatement('event_db','1_group_insert',q{
		INSERT IGNORE INTO chat_group_members (group_oid, playerid, added, modified)
		VALUES (?,?,NOW(),NOW())
	});
	
	&addStatement('game_db','3_group_insert',q{
		INSERT IGNORE INTO chat_group_members (group_oid, playerid, added, modified)
		VALUES (?,?,NOW(),NOW())
	});
	
	&addStatement('event_db','1_rank_insert',q{
		INSERT IGNORE INTO wwii_persona (playerid, countryid, branchid, rankid, modified, added, usedmissnpts, rankpoints, personaid)
		VALUES (?,?,?,?,NOW(),NOW(),0,0,null)
	});
	
	&addStatement('game_db','3_rank_insert',q{
		INSERT IGNORE INTO wwii_persona (playerid, countryid, branchid, rankid, modified, added, usedmissnpts, rankpoints, personaid)
		VALUES (?,?,?,?,NOW(),NOW(),0,0,null)
	});
	
	&addStatement('event_db','1_rank_update',q{
		UPDATE wwii_persona
		SET rankid = ?, modified = NOW()
		WHERE playerid = ?
			AND countryid = ?
			AND branchid = ?
			AND rankid < ?
		LIMIT 1
	});
	
	&addStatement('game_db','3_rank_update',q{
		UPDATE wwii_persona
		SET rankid = ?, modified = NOW()
		WHERE playerid = ?
			AND countryid = ?
			AND branchid = ?
			AND rankid < ?
		LIMIT 1
	});
	
	&addStatement('event_db','1_permissions_delete',q{
		DELETE FROM wwii_player_permiss
		WHERE playerid = ?
	});
	
	&addStatement('game_db','3_permissions_delete',q{
		DELETE FROM wwii_player_permiss
		WHERE playerid = ?
	});
	
	&addStatement('event_db','1_groups_delete',q{
		DELETE FROM chat_group_members
		WHERE playerid = ?
	});
	
	&addStatement('game_db','3_groups_delete',q{
		DELETE FROM chat_group_members
		WHERE playerid = ?
	});
}

my @countries	= (1,3,4);
my @branches	= (1,2,3);

my $jobs = &doSelect('jobs_select','hashref_all');

foreach my $job (@{$jobs})
{
	if(&doUpdate('job_delete', $job->{'member_id'}))
	{
		my $player = &getPlayerDetails($job->{'member_id'});
		
		if($player)
		{
			&setPlayerDetails($player);
		}
	}
}

&freeDatabases();

exit(0);

sub getPlayerDetails()
{
	my $player = &doSelect('player_select', 'hashref_row', shift);
	
	if($player)
	{
		$player->{'clusters'} = {};
		
		foreach my $role (@{&doSelect('roles_select','hashref_all', $player->{'customerid'})})
		{
			if(!exists($player->{'clusters'}->{$role->{'cluster_id'}}))
			{
				$player->{'clusters'}->{$role->{'cluster_id'}} 			= {};
				
				$player->{'clusters'}->{$role->{'cluster_id'}}->{'groups'} 	= {};
				$player->{'clusters'}->{$role->{'cluster_id'}}->{'ranks'}  	= {};
				$player->{'clusters'}->{$role->{'cluster_id'}}->{'swapper'}  	= 0;
			}
			
			if($role->{'side_swap'} eq 'true')
			{
				$player->{'clusters'}->{$role->{'cluster_id'}}->{'swapper'} = 1;
			}
			
			foreach my $rule (@{&doSelect('rules_select','hashref_all', $role->{'role_id'})})
			{
				
				if($rule->{'type'} eq 'group')
				{
					$player->{'clusters'}->{$role->{'cluster_id'}}->{'groups'}->{$rule->{'data1'}} = 1;
				}
				elsif($rule->{'type'} eq 'rank')
				{
					foreach my $cid (@countries)
					{
						foreach my $bid (@branches)
						{
							my $persona = $cid.'_'.$bid;
							
							if($rule->{'data1'} == 0 || $rule->{'data1'} == $cid)
							{
								if($rule->{'data2'} == 0 || $rule->{'data2'} == $bid)
								{
									if(!exists($player->{'clusters'}->{$role->{'cluster_id'}}->{'ranks'}->{$persona}) or
										$player->{'clusters'}->{$role->{'cluster_id'}}->{'ranks'}->{$persona} < $rule->{'data3'})
									{
										$player->{'clusters'}->{$role->{'cluster_id'}}->{'ranks'}->{$persona} = $rule->{'data3'};
									}
								}
							}
						}
					}
				}
			}
		}
	}
	
	return $player;
}

sub setPlayerDetails()
{
	my $player = shift;
	
	&resetPlayer(1, $player->{'playerid'});
	&resetPlayer(3, $player->{'playerid'});
	
	foreach my $cluster (keys(%{$player->{'clusters'}}))
	{
		if($player->{'clusters'}->{$cluster}->{'swapper'})
		{
			&doUpdate($cluster.'_swapper_insert', $player->{'playerid'});
		}
		
		&setPlayerGroups(
			$cluster,
			$player->{'playerid'},
			$player->{'clusters'}->{$cluster}->{'groups'}
		);
		
		&setPlayerRanks(
			$cluster,
			$player->{'playerid'},
			$player->{'clusters'}->{$cluster}->{'ranks'}
		);
	}
}

sub setPlayerGroups()
{
	my $cid 	= shift;
	my $pid 	= shift;
	my $groups 	= shift;
	
	foreach my $gid (keys(%{$groups}))
	{
		&doUpdate($cid.'_group_insert', $gid, $pid);
	}
}

sub setPlayerRanks()
{
	my $cid 	= shift;
	my $pid 	= shift;
	my $ranks 	= shift;
	
	foreach my $persona (keys(%{$ranks}))
	{
		my @details = split(/_/, $persona);
		
		if(!&doUpdate($cid.'_rank_update', $ranks->{$persona}, $pid, @details, $ranks->{$persona}))
		{
			&doUpdate($cid.'_rank_insert', $pid, @details, $ranks->{$persona});
		}
	}
}

sub resetPlayer()
{
	my $cid = shift;
	my $pid = shift;
	
	print "Resetting pid: $pid\n";
	
	&doUpdate($cid.'_permissions_delete', $pid);
	&doUpdate($cid.'_groups_delete', $pid);
	
	return 1;
}

