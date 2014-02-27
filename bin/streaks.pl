#!/usr/bin/perl -w

## Standard Modules
use strict;

use lib ('/usr/local/community/scoring'); 
use Playnet::Database;

INIT {
	
	if(!&addDatabase('community_db',"dbi:mysql:database=community;host=csr.wwiionline.com",'community','fun4all')){
		die "Unable to connect to ScoringDB";
	}
	
	#########################################
	# Services Queries
	#########################################
	
	&addStatement('community_db','streaks_select',q{
		SELECT * FROM scoring_streaks
	});
	
	&addStatement('community_db','streak_update',q{
		UPDATE scoring_streaks
		SET average = ?,
			high = ?
		WHERE streak_id = ?
		LIMIT 1
	});
	
	&addStatement('community_db','avg_select',q{
		SELECT streak_id, avg(best)
		FROM scoring_career_streaks
		WHERE streak_id = ?
		GROUP BY streak_id
	});
	
	&addStatement('community_db','high_select',q{
		SELECT streak_id, best
		FROM scoring_career_streaks
		WHERE streak_id = ?
		ORDER BY best desc
		LIMIT 1
	});
	
}

my %sysvars = &startScoring();

############ DO Tops by Streak #############

my $streaks = &doSelect('streaks_select','hashref_all');

foreach my $streak (@{$streaks}){
	
	print "Processing Streak ".$streak->{'short_title'}." (".$streak->{'streak_id'}.") ...\n";
	
	my $avg		= &doSelect('avg_select', 'array_row', $streak->{'streak_id'});
	my $high	= &doSelect('high_select', 'array_row', $streak->{'streak_id'});
	
	&doUpdate('streak_update', $avg, $high, $streak->{'streak_id'});
	
}

&freeDatabases();

exit(0);

sub startScoring(){
	
	my %vars = ();
	
	$vars{'processed'} 		= 0;
	$vars{'process_id'} 	= 1;
	
	return %vars;
	
}
