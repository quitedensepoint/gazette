<?php

/**
 * Executes the logic to generate a story from the 
 * "Major City Threatened" source.
 */
class StoryMajorCityThreatened extends StoryBase implements StoryInterface {
	
	public function isValid() {

		$sideId = $this->creatorData['side_id'];
		
		/**
		 * Retrieve the strat_cp data from the current game database
		 */
		$contestedFacilities = $this->getContestedFacilities($this->creatorData['country_id']);
		
		foreach($contestedFacilities as $contestedFacility)
		{
		
			/**
			 * Ten CPs or more appears to be "A Major City"
			 */
			if($contestedFacility['facilities'] > 10)
			{
				/**
				* Randomly decide 50% of the time to skip this city. This may prevent any story at all from being generated
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
					$enemy = $enemy + ($link['conside'] != $sideId) ? 1 : 0;
				}
					
				// Does the enemy control all outbound links from the cp
				if($enemy > 0)
				{
					$this->creatorData['template_vars']['city'] = $contestedFacility['name'];
					$this->creatorData['template_vars']['threats'] = $enemy;
					
					$enemySide = $this->getEnemySide($this->creatorData['side_id']);
					$this->creatorData['template_vars']['enemy_side'] = $enemySide['name'];
					$this->creatorData['template_vars']['enemy_side_adj'] = $enemySide['adjective'];
					
					return true;					
				}

			}
		}
		
		return false;
	}
	
	/**
	 * Override the story to produce variances from varieties
	 * 
	 * This appears to be a weird way to generate headlines outside the normal process
	 * 
	 * @param type $template
	 * @return type
	 */
	public function makeStory($template) {
		
		$varieties1 = explode(";", trim($template['variety_1']));
		$variety = $varieties1[rand(0, count($varieties1) - 1)];
		
		$data = [
			'title' => $variety,
			'body' => $variety
		];

		foreach ($this->creatorData['template_vars'] as $key => $value)
		{
			$data['title'] = str_replace('%' . strtoupper($key) . '%', $value, $data['title']);
			$data['body'] = str_replace('%' . strtoupper($key) . '%', $value, $data['body']);
		}

		return $data;			
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
