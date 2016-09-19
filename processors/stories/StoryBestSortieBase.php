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
	 * @return boolean
	 */
	public function createCommonTemplateVarsFromSortie($sortie)
	{
		$rtb = $this->getRTBStatus($sortie['rtb']);
		$this->creatorData['template_vars']['rtb'] = $rtb == null ? 'Unknown' : $rtb;
		
		// Setup the duration of the sortie
		$this->creatorData['template_vars']['duration'] = $this->getSortieDuration($sortie['spawn_time'], $sortie['return_time']);
		
		$this->creatorData['template_vars']['target_kills'] = $sortie['kills'];
		$this->creatorData['template_vars']['kills'] = $sortie['kills'];
		
		/**
		 * Get where the player spawned from
		 */
		if(!$this->setSpawnFacility($sortie))
		{
			return false;
		}		
		
		if(!$this->setKillerVehicle($sortie))
		{
			return false;
		}
		
		$this->setKillsList($sortie);
		$this->setSpawnStart($sortie);

		return true;
	}
	
	/**
	 * 
	 * @param array $sortie
	 * @return boolean
	 */
	public function setSpawnFacility($sortie)
	{
		/**
		 * Get where the player spawned from
		 */
		if(empty($spawnFacility = $this->getFacilityById($sortie['facility_oid'])))
		{
			return false;
		}		
		$this->creatorData['template_vars']['spawn'] = $spawnFacility['name'];
		
		return true;
	}
	
	/**
	 * Update common info about the vehicle used by the player in the sortie
	 * 
	 * @param array $sortie
	 * @return boolean
	 */
	public function setKillerVehicle(array $sortie)
	{
		/**
		 * Get the vehicle the player was using as their avatar
		 * @TODO Update definition of getVehicleByClassification across all stories
		 */		
		$data = $this->getVehicleByClassification($sortie['vcountry'], $sortie['vcategory'], $sortie['vclass'], $sortie['vtype']);
		if(count($data) == 0)
		{
			return false;
		}
	
		$killerVehicle = $data[0];
		$this->creatorData['template_vars']['vehicle'] = $killerVehicle['name'];
		$this->creatorData['template_vars']['vehicle_short'] = $killerVehicle['short_name'];
		
		return true;
	}
	
	/**
	 * Sets the list of kills for the sortie
	 * 
	 * @param array $sortie
	 * @return void
	 */
	public function setKillsList($sortie)
	{
		$kills = $this->getVehicleKillCountsForSortie($sortie['sortie_id']);
		$killList = [];
		foreach ($kills as $kill)
		{
			$killList[] = sprintf("%s %ss", $kill['kill_count'], $kill['name']);
		}
		$this->creatorData['template_vars']['list'] = join(", ", $killList);		
	}
	
	/**
	 * Set the date of the spawn time
	 * 
	 * @param array $sortie
	 */
	public function setSpawnStart($sortie)
	{
		$dateOfSpawn = DateTime::createFromFormat("Y-m-d H:i:s", $sortie['spawn_time'], self::$timezone);
		$this->creatorData['template_vars']['start'] = $dateOfSpawn === false ? "an unreported date" : $dateOfSpawn->format('F j');
	}
}