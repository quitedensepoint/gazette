#!/usr/bin/perl -w

## Standard Modules
use strict;

use lib ('/usr/local/community/scoring');
use Playnet::Database;
use Playnet::Reset;

INIT {
	
	if(!&addDatabase('community_db',"dbi:mysql:database=csr_community;host=localhost",'community','fun4all')){ #CP111713 changed csr to localhost
		die "Unable to connect to ScoringDB";
	}
	
	#########################################
	# Services Queries
	#########################################
	
	&addStatement('community_db','campaign_select',q{
		SELECT campaign_id FROM scoring_campaigns WHERE ISNULL(stop_time) ORDER BY start_time DESC LIMIT 1
	});
	
}

my $campaign_id = &doSelect('campaign_select','array_row');

&resetScoring($campaign_id);

&freeDatabases();

exit(0);
