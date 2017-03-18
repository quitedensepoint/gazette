<?php

use Playnet\WwiiOnline\WwiiOnline\Models\Chokepoint\Bridge;

/**
 * Executes the logic to generate a story from the 
 * "Major City Sieged" source.
 */
abstract class StoryMajorCityBase extends StoryBase {
	
	
	/**
	 * Retrieves the number of contested facilties for each city on the map and then
	 * returns the city with the greatest number contested
	 * 	 
	 * @param integer $countryId
	 * @return array|null
	 */
	public function getContestedFacility($countryId)
	{
		$dbHelper = new dbhelper($this->dbConnWWIIOnline);
		
		$params = [
			Bridge::getTypeId(), $countryId
		];
		
		return $dbHelper
			->first("SELECT c.cp_oid,c.name,count(f.facility_oid) AS facilities "
				. "FROM strat_cp c INNER JOIN strat_facility f ON c.cp_oid = f.cp_oid "
				. "WHERE c.cp_type != ? AND c.contention = 1 AND c.country = ? "
				. "GROUP BY c.cp_oid order by facilities DESC limit 1", $params);	
		
	}
	
	/**
	 * Retrieves the number of contested facilties for each city on the map
	 * 	 
	 * @param integer $countryId
	 * @return array
	 */
	public function getContestedFacilities($countryId)
	{
		$dbHelper = new dbhelper($this->dbConnWWIIOnline);
		
		$params = [
			Bridge::getTypeId(), $countryId
		];
		
		return $dbHelper
			->get("SELECT c.cp_oid,c.name,count(f.facility_oid) AS facilities "
				. "FROM strat_cp c INNER JOIN strat_facility f ON c.cp_oid = f.cp_oid "
				. "WHERE c.cp_type != ? AND c.contention = 1 AND c.country = ? "
				. "GROUP BY c.cp_oid order by facilities DESC", $params);				
	}	
	
	/**
	 * Get all the outbound links from a particular CP and return the side
	 * that controls those links
	 * 
	 * @param integer $cpId ID of the CP to check links of
	 * @return array
	 */
	public function getLinksControllingSides($cpId)
	{
		$dbHelper = new dbhelper($this->dbConnWWIIOnline);
		
		$query = $dbHelper
			->prepare("SELECT c.conside "
			. "FROM strat_link l INNER JOIN strat_cp c ON l.enddepot_id = c.cp_oid "
			. "WHERE l.startdepot_id = ? ", [$cpId]);	
		
		return $dbHelper->getAsArray($query);		
		
		
	}

}
