<?php

/**
 * Executes the logic to generate a story from the 
 * "Latest Intel" source.
 */
class StoryVictoryImminent extends StoryBase implements StoryInterface {
			
	public function isValid() {

		/**
		 * Retrieve the strat_cp data from the current game database
		 */
		$totalCps = $this->getTotalGameCPCount();

		$ownedCps = $this->getOwnedGameCPCount($this->creatorData['side_id']);	
		
		$cpOwnershipPercent = intval(($ownedCps / $totalCps) * 100);
		
		return ($totalCps > 0 && ($cpOwnershipPercent > 90 and $cpOwnershipPercent < 94));
		
	}

	public function makeStory() {

		$template_vars = $this->creatorData['template_vars'];
		
		$template_vars['side_adj'] = strtolower($template_vars['side']) == 'allied' ? 'Allied' : 'Axis';
		$template_vars['enemy_side_adj'] = strtolower($template_vars['side']) == 'axis' ? 'Axis' : 'Allied';
		
		return $this->parseStory($template_vars);

	}
	
	/**
	 * Get the total number of capturable CPs in the campaign
	 * 
	 * @return integer
	 */
	public function getTotalGameCPCount()
	{
		$gameDbHelper = new dbhelper($this->dbConnWWIIOnline);
		
		$query = $gameDbHelper
			->prepare("select count(*) as cp_count from strat_cp where cp_type != 5 and country in (1,3,4)");	
		
		return $gameDbHelper->getAsArray($query)[0]['cp_count'];					
	}
	
	/**
	 * Get the total number of CPs owned by a nominated side
	 * 
	 * @param integer $sideId
	 * @return type
	 */
	public function getOwnedGameCPCount($sideId)
	{
		$gameDbHelper = new dbhelper($this->dbConnWWIIOnline);
		
		$query = $gameDbHelper
			->prepare("select count(*) as cp_count from strat_cp where cp_type != 5"
				. " and country in (1,3,4) and side = ?", [$sideId]);	
		
		return $gameDbHelper->getAsArray($query)[0]['cp_count'];					
	}


}
