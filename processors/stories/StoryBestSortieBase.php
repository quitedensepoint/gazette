<?php

use Monolog\Logger;

/**
 * This class is a base class for the various "Best" attached functionality. It allows us
 * access to some common functions so we don't have to duplicate them across
 * the code base
 * 
 * It is abstract, meaning you have to define child classes to use it
 */
abstract class StoryBestSortieBase extends StoryBase implements StoryInterface {
	
	/**
	 * Holds the maximum number of minutes of age that a sortie can be to
	 * qualify for the story
	 * 
	 * @var integer 
	 */
	protected $maxSortieAgeMinutes = 60;
	
	public function __construct(Logger $logger, $creatorData, array $dbConnections = array(), array $options = []) {
		parent::__construct($logger, $creatorData, $dbConnections, $options);
		$this->isPlayerCentric = true;			
	}	
	
	const UNKNOWN_DURATION_TEXT = "a few";
	
	/**
	 * Sets the player that performed the kills in the sortie
	 * 
	 * @param integer $id
	 * @return array|false
	 */
	public function setProtagonist($id)
	{
		$this->protagonistId = $id;		
		$player = $this->getPlayerById($this->protagonistId);
		if(count($player) == 0)
		{
			return false;
		}
		$this->creatorData['template_vars']['player'] = ucfirst($player[0]['callsign']);
		
		return $player[0];
	}
	
	/**
	 * Returns a string representing the number of minutes on a sortie,
	 * or the value of the constant ("a few") if the value cannot be calculated
	 * 
	 * @param integer $spawnTime A unix based timestamp
	 * @param integer $returnTime A unix base timestamp
	 * 
	 * @return string
	 */
	public function getSortieDuration($spawnTime, $returnTime)
	{
		$duration = self::UNKNOWN_DURATION_TEXT;
		
		/**
		 * Due to nulls recording for spawns and return times, duration cannot be determined sometimes
		 */
		if($spawnTime != null && $returnTime != null) {
			$spawned = DateTime::createFromFormat("Y-m-d H:i:s", $spawnTime);
			$returned = DateTime::createFromFormat("Y-m-d H:i:s", $returnTime);
			
			if($spawned === false || $returned === false)
			{
				// Could not parse the times, return the default
				return self::UNKNOWN_DURATION_TEXT;
			}
			/* @var $dateInterval DateInterval */
			$dateInterval = $spawned->diff($returned);
			if($dateInterval->h > 0 || $dateInterval->i > 0)
			{
				$duration  = $dateInterval->format('%i');
			}
		}
		
		return $duration;
	}
	
	/**
	 * Creates a common set of sortie data across "Best..." stories
	 * 
	 * @param array $sortie
	 * @return void
	 */
	public function createCommonTemplateVarsFromSortie($sortie)
	{
		$rtb = $this->getRTBStatus($sortie['rtb']);
		$this->creatorData['template_vars']['rtb'] = $rtb == null ? 'Unknown' : $rtb;		
	}
}