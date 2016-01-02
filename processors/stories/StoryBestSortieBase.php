<?php

/**
 * This class is a base class for the various "Best" attached functionality. It allows us
 * access to some common functions so we don't have to duplicate them across
 * the code base
 * 
 * It is abstract, meaning you have to define child classes to use it
 */
abstract class StoryBestSortieBase extends StoryBase implements StoryInterface {
	
	const UNKNOWN_DURATION_TEXT = "a few";
	
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
}