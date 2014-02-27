package Playnet::Content::PLAYER_BestATGunner;

use strict;

use lib ('/usr/local/community/paper');

use Playnet::Database;
use Playnet::Misc;
use Playnet::Content::PLAYER_BestSortie;

INIT
{
	### add handler
	$main::source_vars{'47'} = \&Playnet::Content::PLAYER_BestSortie::handleContent;
	
	### add statements
	&addStatement('community_db','47_sortie_select',q{
		SELECT k.sortie_id,
			count(*) as kills,
			'2' as goal
		FROM scoring_campaign_kills k,
			scoring_vehicles v,
			scoring_vehicles ov
		WHERE k.kill_time > FROM_UNIXTIME(UNIX_TIMESTAMP() - 3600)
			AND v.vehicle_id = k.vehicle_id
			AND v.country_id = ?
			AND v.xclass_id = 6
			AND ov.vehicle_id = k.opponent_vehicle_id
			AND ov.xclass_id in (12,13,14,15,31)
		GROUP BY k.sortie_id
		ORDER BY kills DESC
		LIMIT 1
	});
}

1;
