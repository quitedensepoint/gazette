<?php

/**
 * Executes the logic to generate a story from the 
 * "Front Lines" source.
 * 
 */
class StoryFrontLines extends StoryBase implements StoryInterface {
	
	public function isValid() {

		/**
		 * Always valid
		 */
		$frontlineCity = $this->getRandomFrontlineCityForCountry($this->creatorData['country_id']);
		
		$this->creatorData['template_vars']['any_front_city'] = $frontlineCity[0]['name'];
		//meters to miles
		$this->creatorData['template_vars']['distance'] = intval($frontlineCity[0]['distance'] * 0.00062137);
		
		$vehicleData = $this->getRandomVehicle($this->creatorData['country_id']);
		$this->creatorData['template_vars']['vehicle_short'] = $vehicleData != null ? $vehicleData['short_name'] : 'more';		
						
		$classData = $this->getRandomVehicleClass();
		$this->creatorData['template_vars']['class'] = $classData != null ? $classData['name'] : 'military';
		
		return true;
	}
}
