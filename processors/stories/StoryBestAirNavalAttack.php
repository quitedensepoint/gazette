<?php

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
class StoryBestAirNavalAttack extends StoryBase implements StoryInterface {
	
	public function isValid() {

		/**
		 * Gte the best kills from the strat.kills
		 */
		$kill = $this->getMostRecentBestKill($this->creatorData['country_id']);
		
		if(count($kill) != 1)
		{
			// Nobody meets the criteria
			return false;
		}
		$kill = $kill[0];
		
		/**
		 * Get the player who did the kills
		 */
		$player = $this->getPlayerById($kill['killer_id']);
		if(count($player) == 0)
		{
			return false;
		}
		$player = $player[0];
		$this->creatorData['template_vars']['player'] = $player['callsign'];
		
		
		/**
		 * Get the sortie info for the player
		 */
		$sortie = $this->getSortieById($kill['killer_sortie_id']);
		if(count($sortie) == 0)
		{
			return false;
		}
		$sortie = $sortie[0];
		
		$duration  = 0;
		if($sortie['spawn_time'] != null && $sortie['return_time'] != null) {
			$spawned = DateTime::createFromFormat("Y-m-d H:i:s", $sortie['spawn_time']);
			$returned = DateTime::createFromFormat("Y-m-d H:i:s", $sortie['return_time']);
			$dateInterval = $spawned->diff($returned);
			$duration  = $dateInterval->format('%i');
		}
		
		$this->creatorData['template_vars']['duration'] = $duration  = 0;
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
		$this->creatorData['template_vars']['rtb'] = $this->getRTBStatus($sortie['rtb']);
		
		
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
		$wwiiHelper = new dbhelper($this->dbConnWWII);
		
		$query = $wwiiHelper
			->prepare("SELECT count(kill_id) as kill_count, killer_sortie_id, killer_player_0 as killer_id, killer_vehtype_oid, MAX(kill_time) as kill_time "
				. "FROM kills "
				. "WHERE killer_country = ? AND killer_category = 1 AND victim_category  IN (3) "
				. "GROUP by killer_sortie_id, killer_player_0, killer_vehtype_oid "
				. "HAVING count(kill_id) > 0 "
				. "ORDER BY kill_time DESC "
				. "LIMIT 1", [$countryId]);	

		return $wwiiHelper->getAsArray($query);					
	}
	
}