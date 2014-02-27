#!/usr/bin/perl

## Standard Modules
use strict;

use lib ('/usr/local/community/scoring'); 
use Playnet::Database;

INIT
{
	if(!&addDatabase('axishq_db',"dbi:mysql:database=okw;host=66.28.224.234",'okwadmin','oktober45'))
	{
		die "Unable to connect to AxisHQDB";
	}
	
	#########################################
	# Report Queries
	#########################################
	
	&addStatement('axishq_db','forums_select',q{
		SELECT forum_id, forum_name
		FROM forum_forums
		WHERE forum_id IN (48,56,84,75) ORDER BY forum_id ASC
	});
	
	&addStatement('axishq_db','groups_select',q{
		SELECT a.*,
			g.group_name,
			g.group_single_user
		FROM forum_auth_access a,
			forum_groups g
		WHERE a.forum_id = ?
			AND g.group_id = a.group_id
		ORDER BY a.auth_mod DESC,
			a.auth_post DESC,
			a.auth_read DESC
	});
	
	&addStatement('axishq_db','members_select',q{
		SELECT ug.*,
			u.username,
			u.user_email,
			if(u.user_level = 2, 'admin', 'user') as level,
			DATE_FORMAT(FROM_UNIXTIME(u.user_regdate),'%b %d %Y') as registered,
			DATE_FORMAT(FROM_UNIXTIME(u.user_lastvisit),'%b %d %Y') as lastvisit
		FROM forum_user_group ug,
			forum_users u
		WHERE ug.group_id = ?
			AND u.user_id = ug.user_id
		ORDER BY u.username ASC
	});
}

my $forums = &doSelect('forums_select','hashref_all');

# print forum header

foreach my $forum (@{$forums})
{
	print "\n---------------------------------\n";
	print "FORUM: ".$forum->{'forum_name'}."\n";
	print "---------------------------------\n";
	
	printf("     %-37s%-5s%-5s%-5s%-5s%-5s%-5s%-5s%-5s%-5s%-5s%-5s%-s\n",
		'GROUP',
		'VIEW',
		'READ',
		'POST',
		'REPL',
		'EDIT',
		'DEL',
		'STIC',
		'ANNC',
		'VOTE',
		'POLL',
		'FILE',
		'MOD');
	
	my $break = 0;
	
	my $groups = &doSelect('groups_select','hashref_all', $forum->{'forum_id'});
	
	# print group header
	
	foreach my $group (@{$groups})
	{
		my $name = ($group->{'group_single_user'} == 1) ? 'personal': $group->{'group_name'};
		
		if($name ne 'personal' and length($name) > 25)
		{
			$name = substr($name, 0, 25);
			$name .= '...';
		}
		
		if($break > 20)
		{
			printf("     %-37s%-5s%-5s%-5s%-5s%-5s%-5s%-5s%-5s%-5s%-5s%-5s%-s\n",
				'GROUP',
				'VIEW',
				'READ',
				'POST',
				'REPL',
				'EDIT',
				'DEL',
				'STIC',
				'ANNC',
				'VOTE',
				'POLL',
				'FILE',
				'MOD');
			
			$break = 0;
		}
		
		printf("     %-37s%-5s%-5s%-5s%-5s%-5s%-5s%-5s%-5s%-5s%-5s%-5s%-s\n",
			$name,
			&isPermitted($group->{'auth_view'}),
			&isPermitted($group->{'auth_read'}),
			&isPermitted($group->{'auth_post'}),
			&isPermitted($group->{'auth_reply'}),
			&isPermitted($group->{'auth_edit'}),
			&isPermitted($group->{'auth_delete'}),
			&isPermitted($group->{'auth_sticky'}),
			&isPermitted($group->{'auth_announce'}),
			&isPermitted($group->{'auth_vote'}),
			&isPermitted($group->{'auth_pollcreate'}),
			&isPermitted($group->{'auth_attachments'}),
			&isPermitted($group->{'auth_mod'}));
		
		my $members = &doSelect('members_select','hashref_all', $group->{'group_id'});
		
		# print user
		
		foreach my $member (@{$members})
		{
			#printf("          %-32s%-25s%-s\n",
			#	$member->{'username'}.' ('.$member->{'level'}.')',
			#	'last on '.$member->{'lastvisit'},
			#	'joined '.$member->{'registered'});
			printf("          %-s\n",$member->{'username'}.' ('.$member->{'level'}.')');
			
			$break++;
		}
		
	}
}

&freeDatabases();

exit(0);

sub isPermitted()
{
	my $perm = shift;
	
	return ($perm && $perm == 1) ? 'yes': 'no';
}
