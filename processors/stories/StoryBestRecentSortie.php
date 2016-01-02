<?php

/**
 * Executes the logic to generate a story from the 
 * "Best Recent Sortie" source.
 */
class StoryBestRecentSortie extends StoryBestSortieBase implements StoryInterface {
	
	public function isValid() {

		/**
		 * Get all the sorties in the last two hours
		 */
		$time = new DateTime();
		$time->setTimezone(self::$timezone);
		$time->setTimestamp(time() - 7200);

		$sorties = $this->getRecentSorties($this->creatorData['country_id'], $time->getTimestamp());
		
		foreach ($sorties as $sortie)
		{
			$capture = $this->getStratCaptures($sortie['mission_id'], $sortie['player_id'], $sortie['country_id']);
			
			if(count($capture) == 1)
			{
				$this->creatorData['template_vars']['user_id'] = $sortie['customer_id'];
				$this->creatorData['template_vars']['player'] = $sortie['callsign'];
				$this->creatorData['template_vars']['rtb'] = $this->getRTBStatus($sortie['rtb']);
				$this->creatorData['template_vars']['kills'] = $sortie['kills'];
				$this->creatorData['template_vars']['hits'] = $sortie['vehicles_hit'];
				$this->creatorData['template_vars']['duration'] = $this->getSortieDuration($sortie['spawned'], $sortie['returned']);
				$this->creatorData['template_vars']['captured'] = $this->getCapturedFacility($capture[0]['facility_oid']);
				
				$dateOfSpawn = new DateTime(intval($sortie['spawned']) . " seconds", self::$timezone);
				$this->creatorData['template_vars']['month'] = $dateOfSpawn->format('F');
				$this->creatorData['template_vars']['day_ord'] = $dateOfSpawn->format('j');
				
				$enemyCountry = $this->getRandomEnemyCountry($this->creatorData['side_id']);
				$this->creatorData['template_vars']['enemy_country'] = $enemyCountry['name'];
				$this->creatorData['template_vars']['enemy_country_adj'] = $enemyCountry['adjective'];
				
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * Get the recent best sorties for a country
	 * 
	 * @param integer $countryId
	 * @param integer $time the number of seconds since 1970 (epoch or unix time)
	 * @return integer
	 * 
	 * @todo This function had "and s.captures > 0" in the original Perl file. Work out 
	 */
	public function getRecentSorties($countryId, $time)
	{
		$dbHelper = new dbhelper($this->dbConnWWIIOnline);
		
		$query = $dbHelper
			->prepare("SELECT s.mission_id, s.player_id, s.vcountry as country_id, s.rtb, s.kills, s.vehicles_hit, p.customerid as customer_id, p.callsign, "
				. "UNIX_TIMESTAMP(s.spawn_time) as spawned, UNIX_TIMESTAMP(s.return_time) as returned "
				. "FROM wwii_sortie s INNER JOIN wwii_player p ON s.player_id = p.playerid "
				. "WHERE s.added > FROM_UNIXTIME(?) AND s.spawn_time IS NOT NULL AND s.return_time IS NOT NULL "
				. "AND s.mission_id > 0 AND kills > 2 AND s.vcountry = ? "
				. "ORDER BY s.score DESC",[$time, $countryId]);	
		
		return $dbHelper->getAsArray($query);					
	}
	
	
	/**
	 * 
	 * @param integer $missionId
	 * @param integer $customerId
	 * @param integer $country
	 * @return array
	 */
	public function getStratCaptures($missionId, $customerId, $country) {
		
		$dbHelper = new dbhelper($this->dbConnWWIIOnline);
		
		$query = $dbHelper
			->prepare("select facility_oid from strat_captures where missionid = ? and customerid = ? and cust_country = ? limit 1",
			[$missionId, $customerId, $country]);	
		
		return $dbHelper->getAsArray($query);		
		
	}
	
	/**
	 * 
	 * @param integer $facilityId
	 * @param integer $ox
	 * @param integer $oy
	 * @return array
	 */
	public function getCapturedFacility($facilityId)
	{
		$dbHelper = new dbhelper($this->dbConnWWIIOnline);
		
		$query = $dbHelper
			->prepare("select name from strat_facility where facility_oid = ? limit 1",	[$facilityId]);	
		
		$result = $dbHelper->getAsArray($query);
		
		
		return count($result) == 1 ? $result[0]['name'] : 'an enemy facility';			
	}

}