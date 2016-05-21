<?php
use Playnet\WwiiOnline\Common\PlayerMail\HandlerInterface;

/**
 * Executes the logic to generate a story from the 
 * "Victory Near" source.
 */
class StoryVictoryNear extends StoryVictoryBase implements StoryInterface {
	
	public function __construct($creatorData, HandlerInterface $playerMailHandler, array $dbConnections = array()) {
		parent::__construct($creatorData, $playerMailHandler, $dbConnections);
	
		self::$minOwnershipPercent = 88;
		self::$maxOwnershipPercent = 90;
				
	}
	
	public function isValid() {

		/**
		 * Retrieve the strat_cp data from the current game database
		 */
		$totalCps = $this->getTotalGameCPCount();

		$ownedCps = $this->getOwnedGameCPCount($this->creatorData['side_id']);	
		
		/**
		 * This is some calculation that manually adjusts the CP count
		 * 
		 * side_id = 1 = Allies
		 * 
		 * @todo Figure out and document why these calculations are here
		 */
		//$ownedCps		= ($this->creatorData['side_id'] == 1) ? $ownedCps - 6: $ownedCps - 3;
		//$totalCps		= $totalCps - 9;		
		
		$cpOwnershipPercent = intval(($ownedCps / $totalCps) * 100);
		
		return ($totalCps >= self::$minTotalCps && ($cpOwnershipPercent >= self::$minOwnershipPercent and $cpOwnershipPercent <= self::$maxOwnershipPercent));	
	}

}
