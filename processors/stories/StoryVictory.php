<?php

use Playnet\WwiiOnline\WwiiOnline\Models\Chokepoint\Bridge;

/**
 * Executes the logic to generate a story from the 
 * "Victory" source.
 */
class StoryVictory extends StoryVictoryBase implements StoryInterface {
	
	public function __construct($dbConn, $dbConnWWII, $dbConnWWIIOnline, $dbConnToe, $creatorData) {
		parent::__construct($dbConn, $dbConnWWII, $dbConnWWIIOnline, $dbConnToe, $creatorData);
		self::$minOwnershipPercent = 94;			
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
		
		var_dump($cpOwnershipPercent);
		
		return ($totalCps > self::$minTotalCps && ($cpOwnershipPercent >= self::$minOwnershipPercent));
		
	}

	public function makeStory($template) {
		
		$template_vars = $this->creatorData['template_vars'];
		
		$template_vars['side_adj'] = strtolower($template_vars['side']) == 'allied' ? 'Allied' : 'Axis';
		$template_vars['enemy_side_adj'] = strtolower($template_vars['side']) == 'axis' ? 'Axis' : 'Allied';
		
		return $this->parseStory($template_vars, $template['title'], $template['body'] );	
	}	
	
	/**
	 * @todo Is this necessary?
	 * @param type $name
	 * @return type
	 */
	public function getControl($name)
	{
		$dbHelper = new dbhelper($this->dbConn);
		
		$query = $dbHelper
			->prepare("select value from control where name = ? limit 1", [$name]);	
		
		return $dbHelper->getAsArray($query)[0]['value'];					
	}
	
	/**
	 * @todo Is this necessary?
	 */
	public function setControl($name, $value)
	{
		$dbHelper = new dbhelper($this->dbConn);
		
		$query = $dbHelper
			->prepare("replace into control (name,value) values(?,?)", [$name, $value]);	
		
		return $dbHelper->getAsArray($query)[0]['value'];					
	}	


}
