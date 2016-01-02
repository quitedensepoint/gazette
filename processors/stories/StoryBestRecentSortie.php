<?php

/**
 * Executes the logic to generate a story from the 
 * "Best Recent Sortie" source.
 */
class StoryBestRecentSortie extends StoryBestSortieBase implements StoryInterface {
	
	public function isValid() {

		/**
		 * Get all the sorties in the last two hours
		 * 
		 * @todo Remove hardcoding of timezone name
		 */
		$tz = new DateTimeZone('America/Chicago');
		$time = new DateTime();
		$time->setTimezone($tz);
		$time->setTimestamp(time() - 7200);

		$sorties = $this->getRecentSorties($time->getTimestamp());
		
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
				$this->creatorData['template_vars']['duration'] = $this->getSortieDuration($sortie['spawn_time'], $sortie['return_time']);
				$this->creatorData['template_vars']['captured'] = $this->getCapturedFacility($capture[0]['facility_oid']);
				
				$dateOfSpawn = new DateTime(intval($sortie['spawned']) . " seconds", $tz);
				$this->creatorData['template_vars']['month'] = $dateOfSpawn->format('F');
				$this->creatorData['template_vars']['day_ord'] = $dateOfSpawn->format('j');
				
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * Check to see if a country has any CPs left
	 * 
	 * @return integer
	 * 
	 * @todo This function had "and s.captures > 0" in the original Perl file. Work out 
	 */
	public function getRecentSorties($time)
	{
		$dbHelper = new dbhelper($this->dbConnWWIIOnline);
		
		$query = $dbHelper
			->prepare("SELECT s.mission_id, s.player_id, s.vcountry as country_id, s.rtb, s.kills, s.vehicles_hit, p.customerid as customer_id, p.callsign, "
				. "UNIX_TIMESTAMP(s.spawn_time) as spawned, UNIX_TIMESTAMP(s.return_time) as returned "
				. "FROM wwii_sortie s INNER JOIN wwii_player p ON s.player_id = p.playerid "
				. "WHERE s.added > FROM_UNIXTIME(?) and s.mission_id > 0 and kills > 2 "
				. "ORDER BY s.score DESC",[$time]);	
		
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