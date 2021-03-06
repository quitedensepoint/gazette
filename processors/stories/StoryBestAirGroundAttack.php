<?php
/**
 * Copyright Playnet 2016
 */

use Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Categories\Aircraft;
use Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Categories\Ground;
use Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Categories\Infantry;

/**
 * Executes the logic to generate a story from the 
 * "Best Air Ground Attack" source.
 * 
 * Variables needed
 *  SPAWN
 *  PLAYER
 *  VEHICLE
 *  DURATION
 *  LIST
 *  TARGET_KILLS
 *  RTB
 */
class StoryBestAirGroundAttack extends StoryBestSortieBase implements StoryInterface {
	
	/**
	 * The minimum number of kills needed to make this story valid
	 * 
	 * @var integer
	 */
	protected static $minKills = 3;
	
	public function isValid() 
	{

		/**
		 * Gte the best kills from the strat.kills
		 */
		if(!($kill = $this->getMostRecentBestKill($this->creatorData['country_id'])))
		{
			return false;
		}
		
		/**
		 * Get the player who did the kills
		 */
		if(!$this->setProtagonist($kill['killer_id']))
		{
			return false;
		}
	
		/**
		 * Get the sortie info for the player
		 */
		if(empty($sortie = $this->getSortieById($kill['sortie_id'])))
		{
			return false;
		}
		
		return $this->createCommonTemplateVarsFromSortie($sortie);
	}
	
	/**
	 * Get the most recent kill that fits the criteria for this story
	 * 
	 * @return array
	 *  
	 */
	public function getMostRecentBestKill($countryId)
	{
		$dbHelper = new dbhelper($this->dbConnCommunity);
		
		$params = [
			Aircraft::getCategoryId(),
			Ground::getCategoryId(), Infantry::getCategoryId(),
			$countryId, $this->maxSortieAgeMinutes, self::$minKills];
		
		$timeFilter = " AND kill_time >= DATE_SUB(NOW(),INTERVAL ? MINUTE)";

		if($this->options['force'])
		{		
			/**
			 * By using the force option, developers can test against their static data and just use the most recent records 
			 * they have as the starting point. This will generate an actual story, even if it is out of date.
			 */			
			$timeFilter = "";
			// Remove the last parameter as it not used
			$tmp = array_pop($params); array_pop($params); array_push($params, $tmp);
		}

		return $dbHelper
			->first("SELECT count(kill_id) as kill_count, scs.sortie_id, MAX(kill_time) as kill_time, sck.opponent_vehicle_id, scs.player_id as killer_id"
				. " FROM scoring_campaign_sorties scs INNER JOIN scoring_campaign_kills sck  ON sck.sortie_id = scs.sortie_id"
				. " INNER JOIN scoring_vehicles sv_enemy ON sv_enemy.vehicle_id = sck.opponent_vehicle_id"
				. " INNER JOIN scoring_vehicles sv_player ON sv_player.vehicle_id = sck.vehicle_id"
				. " WHERE sv_player.category_id = ? AND sv_enemy.category_id IN (?,?) AND scs.country_id = ?"
				. $timeFilter
				. " GROUP BY scs.sortie_id, scs.player_id, sv_player.type_id"
				. " HAVING count(kill_id) >= ? "
				. " ORDER BY kill_count DESC, MAX(kill_time) DESC"
				. " LIMIT 1", $params);				
				
	}
	
}