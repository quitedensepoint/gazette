<?php

/**
 * Executes the logic to generate a story from the 
 * "Country Diminished" source.
 */
class StoryCountryDiminished extends StoryBase implements StoryInterface {
	
	public function isValid() {

		/**
		 * Retrieve the strat_cp data from the current game database
		 */
		$totalCps = $this->getTotalGameCPCount();

		$ownedCps = $this->getOwnedGameCPCount($this->creatorData['country_id']);	
		
		/**
		 * This is some calculation that manually adjusts the CP count
		 * 
		 * side_id = 1 = Allies
		 * 
		 * @todo Figure out and document why these calculations are here
		 */
		$ownedCps		= ($this->creatorData['side_id'] == 1) ? $ownedCps - 6: $ownedCps - 3;
		$totalCps		= $totalCps - 9;		
		
		$cpOwnershipPercent = intval(($ownedCps / $totalCps) * 100);
		
		return ($totalCps > 0 && ($cpOwnershipPercent < 10));
		
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
			->prepare("select count(*) as cp_count from strat_cp where cp_type != 5");	
		
		return $gameDbHelper->getAsArray($query)[0]['cp_count'];					
	}
	
	/**
	 * Get the total number of CPs owned by a nominated country
	 * 
	 * @param integer $countryId
	 * @return type
	 */
	public function getOwnedGameCPCount($countryId)
	{
		$gameDbHelper = new dbhelper($this->dbConnWWIIOnline);
		
		$query = $gameDbHelper
			->prepare("select count(*) as cp_count from strat_cp where cp_type != 5 and country = ?", [$countryId]);	
		
		return $gameDbHelper->getAsArray($query)[0]['cp_count'];					
	}



}
