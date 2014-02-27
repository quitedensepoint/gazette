package Playnet::Content::PLAYER_BestSortie;

use strict;

use lib ('/usr/local/community/paper'); 

use Playnet::Database;
use Playnet::Misc;

INIT
{
	### add statements
	&addStatement('community_db','best_sortie_select',q{
		SELECT s.sortie_id,
			s.vehicle_id,
			s.kills,
			s.criticals,
			s.tom,
			DATE_FORMAT(s.sortie_start,'%b %d %H%i CT') as started,
			v.name as vehicle,
			r.title as rtb,
			c.name as spawn,
			pl.customerid,
			pl.callsign
		FROM scoring_campaign_sorties s,
			scoring_vehicles v,
			scoring_rtb_codes r,
			ids_cp c,
			wwii_persona pe,
			wwii_player pl
		WHERE s.sortie_id = ?
			AND v.vehicle_id = s.vehicle_id
			AND r.code_id = s.rtb
			AND c.cp_oid = s.origin_cp
			AND pe.personaid = s.persona_id
			AND pl.playerid = pe.playerid
	});
	
	&addStatement('community_db','best_kills_select',q{
		SELECT oc.name as class,
			count(ov.vehicle_id) as killed
		FROM scoring_campaign_kills k,
			scoring_vehicles ov,
			scoring_vehicle_xclasses oc
		WHERE k.sortie_id = ?
			AND ov.vehicle_id = k.opponent_vehicle_id
			AND oc.xclass_id = ov.xclass_id
		GROUP BY ov.xclass_id
		ORDER BY killed DESC
	});
}

## PLAYER
## VEHICLE
## KILLS
## DURATION
## SPAWN
## RTB
## START
## LIST

sub handleContent()
{
	my $vars 	= shift;
	my $best	= &doSelect($vars->{'source_id'} . '_sortie_select','hashref_row', $vars->{'country_id'});
	
	if($best and $best->{'kills'} > 0 and (!$best->{'goal'} or $best->{'kills'} >= $best->{'goal'}))
	{
		my $sortie 	= &doSelect('best_sortie_select','hashref_row', $best->{'sortie_id'});
		
		if($sortie)
		{
			my $kills = &doSelect('best_kills_select','hashref_all', $sortie->{'sortie_id'});
			
			$vars->{'USER_ID'} 		= $sortie->{'customerid'};
			$vars->{'VEHICLE_ID'} 	= $sortie->{'vehicle_id'};
			$vars->{'PLAYER'} 		= ucfirst(lc($sortie->{'callsign'}));
			$vars->{'RTB'} 			= $sortie->{'rtb'};
			$vars->{'KILLS'}		= $sortie->{'kills'};
			$vars->{'TARGET_KILLS'}	= $best->{'kills'};
			$vars->{'VEHICLE'}		= $sortie->{'vehicle'};
			$vars->{'DURATION'}		= $sortie->{'tom'};
			$vars->{'START'}		= $sortie->{'started'};
			$vars->{'SPAWN'}		= $sortie->{'spawn'};
			$vars->{'LIST'}			= &createList($kills);
			
			return 1;
		}
	}
	
	return 0;
}

1;

sub createList()
{
	my $kills	= shift;
	my $list 	= '';
	
	if($kills)
	{
		my $count = scalar(@{$kills});
		
		if($count > 1)
		{
			for(my $k = 0; $k < $count; $k++)
			{
				$list .= $kills->[$k]->{'killed'}.' '.$kills->[$k]->{'class'}.(($kills->[$k]->{'killed'} > 1) ? 's': '');
				$list .= ($count - $k == 2) ? ' and ': ($count - $k > 2) ? ', ': '';
			}
		}
		else
		{
			$list = $kills->[0]->{'killed'}.' '.$kills->[0]->{'class'}.(($kills->[0]->{'killed'} > 1) ? 's': '');
		}
	}
	
	return $list;
}
