<?php

/**
 * Executes the logic to generate a story from the 
 * "Major City Sieged" source.
 */
class StoryMajorCitySieged extends StoryMajorCityBase implements StoryInterface {
	
	public function isValid() {
		
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
			if($contestedFacility['facilities'] > 10)
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
					$enemy = $enemy + ($link['conside'] != $side) ? 1 : 0;
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

}
