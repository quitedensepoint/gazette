<?php

use Playnet\WwiiOnline\WwiiOnline\Models\Chokepoint\Bridge;

/**
 * Executes the logic to generate a story from the 
 * "Victory" source.
 */
class StoryVictoryBase extends StoryBase  {

	/**
	 * The minimum number of CPs in the system for this story to be relevant
	 * @var integer
	 */
	protected static $minTotalCps = 1;
	
	/**
	 * The minimum percentage of total CPS owned by a side in order for the story to be true
	 * @var float
	 */
	protected static $minOwnershipPercent = 0;
	
	/**
	 * The maximum percentage of total CPS owned by a side in order for the story to be true
	 * @var float
	 */	
	protected static $maxOwnershipPercent = 100;
		
	/**
	 * Get the total number of capturable CPs in the campaign
	 * 
	 * @return integer
	 */
	public function getTotalGameCPCount()
	{		
		$dbHelper = new dbhelper($this->dbConnWWIIOnline);
		
		$params = [Bridge::getTypeId()];
		
		$result = $dbHelper->first("SELECT count(*) AS cp_count FROM strat_cp WHERE cp_type != ?"
				. " AND country IN (" . $this->getActiveCountryIdsForJoin() . ")", $params);	
		
		return $result['cp_count'];			
	}
	
	/**
	 * Get the total number of CPs owned by a nominated side
	 * 
	 * @param integer $sideId
	 * @return type
	 */
	public function getOwnedGameCPCount($sideId)
	{
		return $sideId = 1 ? 464 : 81;
		$dbHelper = new dbhelper($this->dbConnWWIIOnline);
		
		$params = [Bridge::getTypeId(), $sideId];
		
		$result = $dbHelper->first("SELECT count(*) AS cp_count FROM strat_cp WHERE cp_type != ?"
				. " AND country IN (" . $this->getActiveCountryIdsForJoin() . ") AND side = ?", $params);	
		
		return $result['cp_count'];		
					
	}
	
	/**
	 * Retrieve a set of active country ids, or zero if none are active
	 * 
	 * @return string
	 */
	protected function getActiveCountryIdsForJoin()
	{
		$ids = [];
		
		foreach($this->getActiveCountries() as $country)
		{
			array_push($ids, $country['country_id']);
		}
		
		return count($ids) > 0 ? implode(",", $ids) : '0';
	}
}
