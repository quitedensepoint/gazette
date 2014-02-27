package Playnet::Reset;

use strict;

require Exporter;

use vars qw(@ISA @EXPORT);

use lib ('/usr/local/community/scoring');
use Playnet::Database;

@ISA 		= qw(Exporter);
@EXPORT 	= qw(resetScoring);

sub resetScoring(){
	
	my $cid 	= shift(@_);
	
	print "\nAre you sure you want to reset scoring? (y/N)\n";
	
	my $response = lc(<STDIN>);
	
	chomp($response);
	
	if($response eq 'y' or $response eq 'yes'){
		&executeReset($cid);
	}
	
}

sub executeReset(){
	
	my $cid = shift(@_);
	
	&addStatement('community_db','reset_campaign_personas',q{
		truncate scoring_campaign_personas
	});

	&addStatement('community_db','reset_campaign_sorties',q{
		truncate scoring_campaign_sorties
	});

	&addStatement('community_db','reset_campaign_kills',q{
		truncate scoring_campaign_kills
	});

	&addStatement('community_db','reset_campaign_captures',q{
		truncate scoring_campaign_captures
	});

	&addStatement('community_db','reset_campaign_versus',q{
		truncate scoring_campaign_versus
	});

	&addStatement('community_db','reset_campaign_spawns',q{
		truncate scoring_campaign_spawns
	});

	&addStatement('community_db','reset_campaign_adversaries',q{
		truncate scoring_campaign_adversaries
	});

	&addStatement('community_db','reset_campaign_streaks',q{
		truncate scoring_campaign_streaks
	});
	
	&addStatement('community_db','reset_campaign_streak_bests',q{
		truncate scoring_campaign_streak_bests
	});
	
	#&addStatement('community_db','reset_career_personas',q{
	#	truncate scoring_career_personas
	#});

	#&addStatement('community_db','reset_career_versus',q{
	#	truncate scoring_career_versus
	#});

	#&addStatement('community_db','reset_career_spawns',q{
	#	truncate scoring_career_spawns
	#});

	#&addStatement('community_db','reset_career_adversaries',q{
	#	truncate scoring_career_adversaries
	#});
	
	#&addStatement('community_db','reset_career_streaks',q{
	#	truncate scoring_career_streaks
	#});
	
	&addStatement('community_db','reset_vehicle_versus',q{
		truncate scoring_vehicle_versus_campaign
	});

	&addStatement('community_db','reset_vehicle_spawns',q{
		truncate scoring_vehicle_spawns_campaign
	});
	
	&addStatement('community_db','reset_campaign_activity',q{
		truncate scoring_campaign_activity
	});
	
	&addStatement('community_db','reset_campaign',q{
		UPDATE scoring_campaigns
		SET sorties = 0,
			kills = 0,
			deaths = 0,
			hits = 0,
			captures = 0,
			successes = 0,
			rtbs = 0,
			rescues = 0,
			mias = 0,
			kias = 0,
			kd = 0,
			tom = 0
		WHERE campaign_id = ?
		LIMIT 1
	});
	
	&addStatement('community_db','reset_campaign_countries',q{
		UPDATE scoring_campaign_countries
		SET sorties = 0,
			kills = 0,
			deaths = 0,
			hits = 0,
			captures = 0,
			successes = 0,
			rtbs = 0,
			rescues = 0,
			mias = 0,
			kias = 0,
			kd = 0,
			tom = 0
		WHERE campaign_id = ?
	});
	
	&doUpdate('reset_campaign_personas');
	&doUpdate('reset_campaign_sorties');
	&doUpdate('reset_campaign_kills');
	&doUpdate('reset_campaign_captures');
	&doUpdate('reset_campaign_versus');
	&doUpdate('reset_campaign_spawns');
	&doUpdate('reset_campaign_adversaries');
	&doUpdate('reset_campaign_streak_bests');
	&doUpdate('reset_campaign_streaks');
	#&doUpdate('reset_career_personas');
	#&doUpdate('reset_career_versus');
	#&doUpdate('reset_career_spawns');
	#&doUpdate('reset_career_adversaries');
	#&doUpdate('reset_career_streaks');
	
	&doUpdate('reset_vehicle_versus');
	&doUpdate('reset_vehicle_spawns');
	
	&doUpdate('reset_campaign_activity');
	
	&doUpdate('reset_campaign_countries',$cid);
	
	&doUpdate('reset_campaign',$cid);
	
}

1;
