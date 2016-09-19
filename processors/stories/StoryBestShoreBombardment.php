<?php

use Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Types\Destroyer\British;
use Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Types\Destroyer\French;
use Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Types\Destroyer\German;
use Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Categories\Ground;
use Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Categories\Infantry;

/**
 * Executes the logic to generate a story from the 
 * "Best Shore Bombardment" source.
 * 
 * Variables needed
 *  SPAWN
 *  PLAYER
 *  VEHICLE
 *  DURATION
 *  LIST
 *  KILLS
 *  RTB
 */
class StoryBestShoreBombardment extends StoryBestSortieBase implements StoryInterface {

	/**
	 * The minimum number of kills needed to make this story valid
	 * 
	 * @var integer
	 */
	protected static $minKills = 1;	
	
	public function isValid() {

		/**
		 * Get the best kills from the strat.kills
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
		 * Get the player who did the kills
		 */
		$player = $this->getPlayerById($kill['killer_id']);
		if(count($player) == 0)
		{
			return false;
		}
		$player = $player[0];
		$this->creatorData['template_vars']['player'] = ucfirst($player['callsign']);
			
		/**
		 * Get the sortie info for the player
		 */
		$sortie = $this->getSortieById($kill['sortie_id']);
		if(count($sortie) == 0)
		{
			return false;
		}
		$sortie = $sortie[0];
		
		$this->creatorData['template_vars']['duration'] = $this->getSortieDuration($sortie['spawn_time'], $sortie['return_time']);
		$this->creatorData['template_vars']['target_kills'] = $sortie['kills'];		

		/**
		 * Get where the player spawned from
		 */
		$spawnFacility = $this->getFacilityById($sortie['facility_oid']);
		if(count($spawnFacility) == 0)
		{
			return false;
		}		
		$spawnFacility = $spawnFacility[0];
		$this->creatorData['template_vars']['spawn'] = $spawnFacility['name'];
	
		/**
		 * Get the vehicle the player was using as their avatar
		 */
		$killerVehicle = $this->getVehicleByClassification($sortie['vcountry'], $sortie['vcategory'], $sortie['vclass'], $sortie['vtype']);
		if(count($killerVehicle) == 0)
		{
			return false;
		}
	
		$killerVehicle = $killerVehicle[0];
		$this->creatorData['template_vars']['vehicle'] = $killerVehicle['name'];
		$this->creatorData['template_vars']['vehicle_short'] = $killerVehicle['short_name'];
		
		$kills = $this->getVehicleKillCountsForSortie($sortie['sortie_id']);
		$killList = [];
		foreach ($kills as $kill)
		{
			$killList[] = sprintf("%s %ss", $kill['kill_count'], $kill['name']);
		}
		$this->creatorData['template_vars']['list'] = join(", ", $killList);
		
		$this->creatorData['template_vars']['side_adj'] = $this->creatorData['template_vars']['enemy_side_adj'];
		
		$dateOfSpawn = DateTime::createFromFormat("Y-m-d H:i:s", $sortie['spawn_time'], self::$timezone);
		$this->creatorData['template_vars']['start'] = $dateOfSpawn === false ? "an unreported date" : $dateOfSpawn->format('F j');
		
		$this->createCommonTemplateVarsFromSortie($sortie);		
		
		return true;

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
			French::OBJECT_ID,  German::OBJECT_ID, British::OBJECT_ID,
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
			// Remove the second last paramater
			$tmp = array_pop($params); array_pop($params); array_push($params, $tmp);
		}

		return $dbHelper
			->first("SELECT count(kill_id) as kill_count, scs.sortie_id, MAX(kill_time) as kill_time, sck.opponent_vehicle_id, scs.player_id as killer_id"
				. " FROM scoring_campaign_sorties scs INNER JOIN scoring_campaign_kills sck  ON sck.sortie_id = scs.sortie_id"
				. " INNER JOIN scoring_vehicles sv_enemy ON sv_enemy.vehicle_id = sck.opponent_vehicle_id"
				. " INNER JOIN scoring_vehicles sv_player ON sv_player.vehicle_id = sck.vehicle_id"
				. " WHERE sv_player.vehicle_id IN (?,?,?) AND sv_enemy.category_id IN (?, ?) AND scs.country_id = ?"
				. $timeFilter
				. " GROUP BY scs.sortie_id, scs.player_id"
				. " HAVING count(kill_id) >= ? "
				. " ORDER BY kill_count DESC, MAX(kill_time) DESC"
				. " LIMIT 1", $params);				
		
	}
	
}