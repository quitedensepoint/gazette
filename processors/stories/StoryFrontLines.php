<?php

/**
 * Executes the logic to generate a story from the 
 * "Front Lines" source.
 * 
 */
class StoryFrontLines extends StoryBase implements StoryInterface {
	
	/**
	 * Converting a set of metres to miles
	 */
	const METRES_TO_MILES = 0.00062137;
	
	/**
	 * The maximum number of cities to show in the front line list
	 * 
	 * @var integer
	 */
	protected static $maxFrontlineCityCount = 3;
	
	public function isValid() {

		/**
		 * Always valid
		 */
		$frontlineCity = $this->getRandomFrontlineCityForCountry($this->creatorData['country_id']);
		$this->creatorData['template_vars']['any_front_city'] = $frontlineCity[0]['name'];
		//meters to miles
		$this->creatorData['template_vars']['distance'] = intval($frontlineCity[0]['distance'] * self::METRES_TO_MILES);
		
		$vehicleData = $this->getRandomVehicle($this->creatorData['country_id']);
		$this->creatorData['template_vars']['vehicle_short'] = $vehicleData != null ? $vehicleData['short_name'] : 'more';		
						
		$classData = $this->getRandomVehicleClass();
		$this->creatorData['template_vars']['class'] = $classData != null ? $classData['name'] : 'military';
		
		/**
		 * Add some random frontline cities to a list of frontlines
		 */
		$frontlines = $this->getFrontlineCitiesForCountry($this->creatorData['country_id'], true);
		
		$frontlineList = [];
		foreach($frontlines as $frontline)
		{
			if(!in_array($frontline['name'], $frontlineList))
			{
				array_push($frontlineList, $frontline['name']);
			}
			
			if(count($frontlineList) == self::$maxFrontlineCityCount)
			{
				break;
			}
		}

		$chokepoints = "";
		switch(count($frontlineList))
		{
			case 1:
				$chokepoints = $frontlineList[0];
				break;
			case 2:
				$chokepoints = sprintf("%s and %s", $frontlineList[0], $frontlineList[1]);
				break;
			default:
				$chokepoints = sprintf("%s, %s and %s", $frontlineList[0], $frontlineList[1], $frontlineList[2]);
		}
		$this->creatorData['template_vars']['chokepoints'] = $chokepoints;
		
		return true;
	}
}
