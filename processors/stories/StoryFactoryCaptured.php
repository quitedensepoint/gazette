<?php

/**
 * Executes the logic to generate a story from the 
 * "Factory Captured" source.
 * 
 * This checks all of the factory towns for a side, and checks to see if
 * all of the factories reduced in capacity.
 */
class StoryFactoryCaptured extends StoryFactoryBase implements StoryInterface {
	
		
	public function isValid() {

		$factoryData = $this->getFactoryOutputs($this->creatorData['side_id']);
		
		$chokepoints = [];

		/**
		 * This is somewhat convulted, and needs revisiting, but it basically builts a
		 * structure that can be interated below and build the final story
		 */
		foreach($factoryData as $factory)
		{
			if(!isset($chokepoints[$factory['cp_oid']])) {
				$chokepoints[$factory['cp_oid']] = ['factories' => []];
			}
			$chokepoints[$factory['cp_oid']]['factories'][] = $factory;
			
			if(!isset($chokepoints[$factory['cp_oid']]['cp_name'])) {
				$chokepoints[$factory['cp_oid']]['cp_name'] = $factory['cp_name'];
				$chokepoints[$factory['cp_oid']]['country_name'] = $factory['country_name'];
			}

		}
		
		/**
		 * Go through each chokepoint and see if all the factories have been captured
		 */
		foreach($chokepoints as $chokepoint)
		{	
			$captured = $producers = $capturingCountryId = 0;
			
			foreach($chokepoint['factories'] as $fact)
			{	
				$producers++;
				
				if($fact['side'] != $fact['originalside'])
				{
					$captured++;
					$capturingCountryId = $fact['country_id'];
				}
			}
			
			/**
			 * If all factories are captured
			 */
			if($producers == $captured)
			{
				$this->creatorData['template_vars']['factory_city'] = $chokepoint['cp_name'];
				
				$country = $this->getCountryById($capturingCountryId);
				
				$this->creatorData['template_vars']['capturing_country']		= $country['name'];
				$this->creatorData['template_vars']['capturing_country_adj']	= $country['adjective'];
				$this->creatorData['template_vars']['capturing_side']			= $country['side'];				

				$enemyCountry = $this->getRandomEnemyCountry($this->creatorData['side_id']);			
				$this->creatorData['template_vars']['enemy_country_adj'] = $enemyCountry['adjective'];
				
				$vehicleClass = $this->getRandomVehicleClass();
				$this->creatorData['template_vars']['class'] = $vehicleClass['name'];
				
				$this->creatorData['template_vars']['direction_adj'] = $this->getRandomDirection()['adjective'];
				$this->creatorData['template_vars']['intensity'] = $this->getRandomIntensity();
				
				$city = $this->getRandomCityForCountry($this->creatorData['country_id']);
				$this->creatorData['template_vars']['city'] = $city[0]['name'];
				
				$dt = new DateTime();
				$dt->setTimezone(self::$timezone);
				$this->creatorData['template_vars']['military_time'] = $dt->format('Hi');

				return true;
			}
		}
		
		return false;		
	}

}