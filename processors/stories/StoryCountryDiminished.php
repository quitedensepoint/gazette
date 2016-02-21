<?php

use Playnet\WwiiOnline\WwiiOnline\Models\Chokepoint\Bridge;

/**
 * Executes the logic to generate a story from the 
 * "Country Diminished" source.
 */
class StoryCountryDiminished extends StoryBase implements StoryInterface {
	
	/**
	 * The minimum number of cps to be owned to be valid
	 * @var integer 
	 */
	protected static $minTotalCpCount = 1;
	
	/**
	 * The maximum percentage of ownership of all chokepoints to be valid
	 * @var float 
	 */
	protected static $maxCpOwnershipPercent = 10;
	
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
		
		return ($totalCps >= self::$minTotalCpCount && ($cpOwnershipPercent < self::$maxCpOwnershipPercent));
		
	}

	
	/**
	 * Get the total number of capturable CPs in the campaign
	 * 
	 * @return integer
	 */
	public function getTotalGameCPCount()
	{
		$dbHelper = new dbhelper($this->dbConnWWIIOnline);
		
		$params = [Bridge::getTypeId()];
		
		$result = $dbHelper
			->first("select count(*) as cp_count from strat_cp where cp_type != ?", $params);	
		
		return $result['cp_count'];					
	}
	
	/**
	 * Get the total number of CPs owned by a nominated country
	 * 
	 * @param integer $countryId
	 * @return integer
	 */
	public function getOwnedGameCPCount($countryId)
	{
		$dbHelper = new dbhelper($this->dbConnWWIIOnline);
		
		$params = [
			Bridge::getTypeId(),
			$countryId
		];
		
		$result = $dbHelper
			->first("select count(*) as cp_count from strat_cp where cp_type != ? and country = ?", $params);	
		
		return $result['cp_count'];					
	}



}
