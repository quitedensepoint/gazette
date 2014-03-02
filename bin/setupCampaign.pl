#!/usr/bin/perl -w

## Standard Modules
use strict;

use lib ('/usr/local/community/paper');
use Playnet::Database;

INIT
{
        &addDatabase('community_db',"dbi:mysql:database=community;host=localhost",'community','fun4all'); #CP1111713 point to localhost rather than csr

        &addStatement('community_db','campaign_select',q{
                select * from paper_campaigns where status = 'Running';
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

my $currentCampaign = &doSelect("campaign_select",'hashref_row');
my $nextCampaignID = ($currentCampaign->{'campaign_id'} + 1);

if(!$currentCampaign)
{
	print "Current campaign not found!\n";
	exit;
}

print "Closing campaign " .  $currentCampaign->{'campaign_id'} ." at " . $currentCampaign->{'start_time'} . " with a winner of $winner.\n";
print "Starting campaign " .  $nextCampaignID . ".\n";

&doUpdate(
		  'campaign_update',
		  $winner,
		  $currentCampaign->{'stop_time'},
		  $currentCampaign->{'campaign_id'}
		 );

&freeDatabases();

exit(0);


