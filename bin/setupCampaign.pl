#!/usr/bin/perl -w

## Standard Modules
use strict;

use lib ('/usr/local/community/paper');
use Playnet::Database;

INIT
{
        &addDatabase('community_db',"dbi:mysql:database=community;host=66.28.224.237",'community','fun4all');

        &addStatement('community_db','campaign_select',q{
                select * from paper_campaigns order by campaign_id desc limit 1
        });

        &addStatement('community_db','campaign_update',q{
                update scoring_campaigns
                set winning_side = ?,
                	stop_time = ?
                where campaign_id = ?
                limit 1
        });
}

if(scalar(@ARGV) == 0)
{
	print "You must specify a winning side for the last campaign: 1 = allies, 2 = axis\n";
	exit;
}

my $winner = int(shift(@ARGV));

my $nextCampaign = &doSelect("campaign_select",'hashref_row');

if(!$nextCampaign)
{
	print "New campaign not found!\n";
	exit;
}

print "Closing campaign " .  ($nextCampaign->{'campaign_id'} - 1) ." at " . $nextCampaign->{'start_time'} . " with a winner of $winner.\n";
print "Starting campaign " .  $nextCampaign->{'campaign_id'} . ".\n";

&doUpdate
(
	'campaign_update',
	$winner,
	$nextCampaign->{'start_time'},
	$nextCampaign->{'campaign_id'} - 1
);

&freeDatabases();

exit(0);


