<?php

/**
 * Executes the logic to generate a story from the 
 * "Misc" source.
 * 
 * Passed template title and body through unaltered. Currently
 * used to create silly stories.
 * 
 */
class StoryMisc extends StoryBase implements StoryInterface {
	
	public function isValid() {

		/**
		 * Always valid
		 */
		$frontlineCity = $this->getRandomFrontlineCityForCountry($this->creatorData['country_id']);
		
		$this->creatorData['template_vars']['any_front_city'] = $frontlineCity[0]['name'];
		
		return true;
	}
}
