<?php

/**
 * Executes the logic to generate a story from the 
 * "Best Recent Sortie" source.
 */
class StoryBestRecentSortie extends StoryBestSortieBase implements StoryInterface {
	
	public function isValid() 
	{
		if(empty($kill = $this->getBestRecentSortie($this->creatorData['side_id'])))
		{		
			return false;
		}

		if(!$this->setProtagonist($kill['player_id']))
		{
			return false;
		}
		
		$sortie = $this->getSortieById($kill['sortie_id']);
		
		if(!$this->createCommonTemplateVarsFromSortie($sortie))
		{
			return false;
		}

		$this->creatorData['template_vars']['user_id'] = $sortie['player_id'];

		$this->creatorData['template_vars']['hits'] = $sortie['hits'];
		$this->creatorData['template_vars']['captured'] = $this->getCapturedFacility($kill['capture_fac']);

		$dateOfSpawn = new DateTime(intval($sortie['spawn_time']) . " seconds", self::$timezone);
		$this->creatorData['template_vars']['month'] = $dateOfSpawn->format('F');
		$this->creatorData['template_vars']['day_ord'] = $dateOfSpawn->format('j');

		$enemyCountry = $this->getRandomEnemyCountry($this->creatorData['side_id']);
		$this->creatorData['template_vars']['enemy_country'] = $enemyCountry['name'];
		$this->creatorData['template_vars']['enemy_country_adj'] = $enemyCountry['adjective'];

		return true;
	}
	
	/**
	 * Get the recent best sorties for a country
	 * 
	 * @param integer $countryId
	 * @param integer $time the number of seconds since 1970 (epoch or unix time)
	 * @return array|null 
	 */
	public function getBestRecentSortie($sideId)
	{
		$dbHelper = new dbhelper($this->dbConnCommunity);
		
		$params = [$sideId, $this->maxSortieAgeMinutes, $this->maxSortieAgeMinutes];
		
		$timeFilter = "HAVING scs.sortie_stop >= DATE_SUB(NOW(),INTERVAL ? MINUTE) "
			. "OR scs.sortie_start >= DATE_SUB(NOW(),INTERVAL ? MINUTE) ";

		if($this->options['force'])
		{		
			/**
			 * By using the force option, developers can test against their static data and just use the most recent records 
			 * they have as the starting point. This will generate an actual story, even if it is out of date.
			 */			
			$timeFilter = "";
			// Remove the last 2 parameters as they are not used
			array_pop($params);	array_pop($params);	
		}			
		
		return $dbHelper->first("SELECT scs.mission_id, scs.player_id,scs.country_id, scs.rtb, scs.kills, scs.hits, scs.captures, scs.sortie_id, " 
			. "max(scc.capture_time) as capture_time, scs.sortie_stop, scs.sortie_start, scc.capture_fac "
			. "FROM scoring_campaign_sorties scs INNER JOIN scoring_campaign_captures scc ON scc.sortie_id = scs.sortie_id "
			. "INNER JOIN scoring_countries sc on scs.country_id = sc.country_id "
			. "WHERE sc.side_id = ? "
			. "GROUP BY scs.mission_id, scs.player_id,scs.country_id, scs.rtb, scs.kills, scs.captures, scs.sortie_id "
			. $timeFilter
			. "ORDER BY scs.score DESC, scs.sortie_stop, scs.sortie_start DESC " 
			. "LIMIT 1", $params);				
	}
	
	/**
	 * 
	 * @param integer $facilityId
	 * @return string
	 */
	public function getCapturedFacility($facilityId)
	{
		$facility = $this->getFacilityById($facilityId);

		return !empty($facility) ? $facility['name'] : 'an enemy facility';			
	}

}