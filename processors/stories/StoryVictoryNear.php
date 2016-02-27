<?php

/**
 * Executes the logic to generate a story from the 
 * "Victory Near" source.
 */
class StoryVictoryNear extends StoryVictoryBase implements StoryInterface {
	
	public function __construct($dbConn, $dbConnWWII, $dbConnWWIIOnline, $dbConnToe, $creatorData) {
		parent::__construct($dbConn, $dbConnWWII, $dbConnWWIIOnline, $dbConnToe, $creatorData);
		self::$maxOwnershipPercent = 90;
		self::$minOwnershipPercent = 88;		
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
		$ownedCps		= ($this->creatorData['side_id'] == 1) ? $ownedCps - 6: $ownedCps - 3;
		$totalCps		= $totalCps - 9;		
		
		$cpOwnershipPercent = intval(($ownedCps / $totalCps) * 100);
		
		return ($totalCps >= self::$minTotalCps && ($cpOwnershipPercent >= self::$minOwnershipPercent and $cpOwnershipPercent <= self::$maxOwnershipPercent));	
	}

	public function makeStory($template) {
		
		$template_vars = $this->creatorData['template_vars'];
		
		$template_vars['side_adj'] = strtolower($template_vars['side']) == 'allied' ? 'Allied' : 'Axis';
		$template_vars['enemy_side_adj'] = strtolower($template_vars['side']) == 'axis' ? 'Axis' : 'Allied';
		
		return $this->parseStory($template_vars, $template['title'], $template['body'] );
	}

}
