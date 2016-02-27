<?php

/**
 * Executes the logic to generate a story from the 
 * "Major City Contested" source.
 */
class StoryMajorCityContested extends StoryMajorCityBase implements StoryInterface {
	
	public function isValid() {

		/**
		 * Retrieve the strat_cp data from the current game database
		 */
		$contestedFacility = $this->getContestedFacility($this->creatorData['country_id']);
		
		/**
		 * Ten CPs or more appears to be "A Major City"
		 */
		if($contestedFacility && $contestedFacility['facilities'] >= self::$minContestedFacilities)
		{
			$this->creatorData['template_vars']['city'] = $contestedFacility['name'];
			$this->creatorData['template_vars']['con_city'] = $contestedFacility['name'];
			
			return true;
		}
		
		return false;
	}

}
