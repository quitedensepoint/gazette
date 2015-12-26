<?php

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
		$gameDbHelper = new dbhelper($this->dbConnWWIIOnline);
		
		$query = $gameDbHelper
			->prepare("select count(*) as cp_count from strat_cp where cp_type != 5 and country = ?",[$country_id]);	
		
		return $gameDbHelper->getAsArray($query)[0]['cp_count'] == 0;					
	}
}
