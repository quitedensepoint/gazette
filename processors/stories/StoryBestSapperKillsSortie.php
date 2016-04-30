<?php

use Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Types\Sapper\British;
use Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Types\Sapper\French;
use Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Types\Sapper\German;
use Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Types\Sapper\Us;
use Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Classes\Tank;

/**
 * Executes the logic to generate a story from the 
 * "Best Sapper Kills Sortie" source.
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
class StoryBestSapperKillsSortie extends StoryBestSortieBase implements StoryInterface {
	
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
		
		/**
		 * @todo Rework how to pull in the vehicle data for this story as we do not have a consistent set in the gazette
		 * See Redmine Issue #1358
		 */
		//$killerVehicle = $this->getVehicleById($kill['killer_vehtype_oid']);
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
		
		$dbHelper = new dbhelper($this->dbConnWWII);
		
		$params = [
			French::OBJECT_ID,  German::OBJECT_ID, British::OBJECT_ID, Us::OBJECT_ID,
			Tank::getClassId(),
			$countryId, self::$minKills];

		return $dbHelper
			->first("SELECT count(kill_id) as kill_count, killer_sortie_id, killer_player_0 as killer_id, killer_vehtype_oid, MAX(kill_time) as kill_time "
				. "FROM kills "
				. "WHERE killer_vehtype_oid IN (?,?,?,?) "
				. "AND victim_class = ? AND killer_country = ? "
				. "GROUP BY killer_sortie_id, killer_player_0, killer_vehtype_oid "
				. "HAVING count(kill_id) >= ? "
				. "ORDER BY kill_time DESC , kill_count DESC "
				. "LIMIT 1", $params);				
	}
	
}