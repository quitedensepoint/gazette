<?php

use Playnet\WwiiOnline\WwiiOnline\Models\Chokepoint\Bridge;

/**
 * Executes the logic to generate a story from the 
 * "Latest Intel" source.
 */
class StoryCountryCaptured extends StoryBase implements StoryInterface {
	
	public function isValid() {

		/**
		 * Retrieve the strat_cp data from the current game database
		 */
		return $this->isCountryOwnedBy($this->creatorData['country_id']);
		
	}
	
	/**
	 * Check to see if a country has any CPs left
	 * 
	 * @return integer
	 */
	public function isCountryOwnedBy($country_id)
	{
		$dbHelper = new dbhelper($this->dbConnWWIIOnline);
		
		$params = [
			Bridge::getTypeId(),
			$country_id
		];
		
		$result = $dbHelper->first("select count(*) as cp_count from strat_cp where cp_type != ? and country = ?", $params);	
		
		return $result['cp_count'] == 0;					
	}
}
