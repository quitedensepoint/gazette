package Playnet::Content::PLAYER_BestAirNavalAttack;

use strict;

use lib ('/usr/local/community/paper');

use Playnet::Database;
use Playnet::Misc;
use Playnet::Content::PLAYER_BestSortie;

INIT
{
	### add handler
	$main::source_vars{'53'} = \&Playnet::Content::PLAYER_BestSortie::handleContent;
	
	### add statements
	&addStatement('community_db','53_sortie_select',q{
		SELECT k.sortie_id,
			count(*) as kills,
			'1' as goal
		FROM scoring_campaign_kills k,
			scoring_vehicles v,
			scoring_vehicles ov
		WHERE k.kill_time > FROM_UNIXTIME(UNIX_TIMESTAMP() - 3600)
			AND v.vehicle_id = k.vehicle_id
			AND v.country_id = ?
			AND v.xcategory_id = 5
			AND ov.vehicle_id = k.opponent_vehicle_id
			AND ov.xclass_id in (22,23)
		GROUP BY k.sortie_id
		ORDER BY kills DESC
		LIMIT 1
	});
}

1;
