<?php

/**
 * Executes the logic to generate a story from the 
 * "Major City Sieged" source.
 */
class StoryMajorCitySieged extends StoryBase implements StoryInterface {
	
	
	public function isValid() {
		
		/**
		 * @todo Some of the tables from the old perl queries no longer exist - will need to reformulate queries
		 */
		return false;

		$side = $this->creatorData['side_id'];
		
		/**
		 * Retrieve the strat_cp data from the current game database
		 */
		$contestedFacilities = $this->getContestedFacilities($this->creatorData['country_id']);
		
		foreach($contestedFacilities as $contestedFacility)
		{
		
			/**
			 * Ten CPs or more appears to be "A Major City"
			 */
			if($contestedFacility['facilities'] > -1)
			{
				/**
				* Randomly decide 50% of the time to skip this city
				*/				
				if(rand(1, 100) > 50)
				{
					continue;
				}
				/**
				 * select the controlling sides for all links from the CPs
				 */
				$links = $this->getLinksControllingSides(intval($contestedFacility['cp_oid']));
				$total = $enemy = 0;
				
				foreach($links as $link)
				{
					$total++;
					$enemy = $enemy + ($link['controlling_side'] != $side) ? 1 : 0;
				}
					
				// Does the enemy control all outbound links from the cp
				if($total == $enemy)
				{
					$this->creatorData['template_vars']['city'] = $contestedFacility['name'];
					$this->creatorData['template_vars']['threats'] = $enemy;
					$this->creatorData['template_vars']['enemy_side'] = $this->creatorData['template_vars']['side'];

					return true;					
				}

			}
		}
		
		return false;
	}
		
	/**
	 * Retrieves the number of contested facilties for each city on the map
	 * 	 
	 * @param integer $countryId
	 * @return array
	 */
	public function getContestedFacilities($countryId)
	{
		$gameDbHelper = new dbhelper($this->dbConnWWIIOnline);
		
		$query = $gameDbHelper
			->prepare("SELECT c.cp_oid,c.name,count(f.facility_oid) AS facilities "
				. "FROM strat_cp c INNER JOIN strat_facility f ON c.cp_oid = f.cp_oid "
				. "WHERE c.cp_type != 5 AND c.contention = 1 AND c.country = ? "
				. "GROUP BY c.cp_oid order by facilities DESC", [$countryId]);	
		
		return $gameDbHelper->getAsArray($query);					
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
