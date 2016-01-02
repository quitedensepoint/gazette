<?php

/**
 * Executes the logic to generate a story from the 
 * "Major City Contested" source.
 */
class StoryMajorCityContested extends StoryBase implements StoryInterface {

	public function isValid() {

		/**
		 * Retrieve the strat_cp data from the current game database
		 */
		$contestedFacility = $this->getContestedFacility($this->creatorData['country_id']);
		
		/**
		 * Ten CPs or more appears to be "A Major City"
		 */
		if(count($contestedFacility) > 0 && $contestedFacility['facilities'] > 10)
		{
			$this->creatorData['template_vars']['city'] = $contestedFacility['name'];
			$this->creatorData['template_vars']['con_city'] = $contestedFacility['name'];
			
			return true;
		}
		
		return false;
	}
	
	/**
	 * Retrieves the number of contested facilties for each city on the map and then
	 * returns the city with the greatest number contested
	 * 	 
	 * @param integer $countryId
	 * @return array
	 */
	public function getContestedFacility($countryId)
	{
		$dbHelper = new dbhelper($this->dbConnWWIIOnline);
		
		$query = $dbHelper
			->prepare("SELECT c.cp_oid,c.name,count(f.facility_oid) AS facilities "
				. "FROM strat_cp c INNER JOIN strat_facility f ON c.cp_oid = f.cp_oid "
				. "WHERE c.cp_type != 5 AND c.contention = 1 AND c.country = ? "
				. "GROUP BY c.cp_oid order by facilities DESC limit 1", [$countryId]);	
		
		$result = $dbHelper->getAsArray($query);
		if(count($result) == 1)
		{
			return $result[0];					
		}
		return [];
	}


}
