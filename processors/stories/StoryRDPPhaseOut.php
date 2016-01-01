<?php

/**
 * Executes the logic to generate a story from the 
 * "RDP Phase Out" source.
 */
class StoryRDPPhaseOut extends StoryRDPBase implements StoryInterface {
	
	public function isValid() {

		$action = $this->getRDPActionData('-', $this->creatorData['country_id']);

		if(count($action) == 0)
		{
			return false;
		}
		$action = $action[0];
		
		$vehicle = $this->getVehicleByClassification($action['country_id'], $action['veh_category_id'], $action['veh_class_id'], $action['veh_type_id']);
		if(count($vehicle) == 0)
		{
			return false;
		}
		$vehicle = $vehicle[0];	
		
		$randomCity = $this->getRandomCityForCountry($action['country_id'])[0];
		
		$this->creatorData['template_vars']['city']  = $randomCity['name'];
		
		return true;
		
	}
}