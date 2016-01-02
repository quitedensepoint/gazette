<?php

/**
 * Executes the logic to generate a story from the 
 * "RDP Decrease" source.
 */
class StoryRDPDecrease extends StoryRDPBase implements StoryInterface {
	
	public function isValid() {

		$action = $this->getRDPActionData('<', $this->creatorData['country_id']);

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
		
		$this->creatorData['template_vars']['vehicle'] = $vehicle['name'];
		$this->creatorData['template_vars']['vehicle_short'] = $vehicle['short_name'];
		
		$this->creatorData['template_vars']['direction_adj'] = $this->getRandomDirection()['adjective'];

		$randomCity = $this->getRandomCityForCountry($action['country_id'])[0];
		
		$this->creatorData['template_vars']['city']  = $randomCity['name'];
		
		$randomFactoryCity = $this->getRandomFactoryCityForCountry($action['country_id'])[0];		
		$this->creatorData['template_vars']['factory_city']  = $randomFactoryCity['name'];		
		
		$currentCapacity = intval($action['current_capacity']);
		$data = intval($action['next_capacity']) - $currentCapacity;
		$this->creatorData['template_vars']['data']					= $data;
		$this->creatorData['template_vars']['spawns']				= $currentCapacity;
		$this->creatorData['template_vars']['quantity_decrease'] 	= $data;
		$percentageDecrease = abs(intval($data / $currentCapacity * 100));
		$this->creatorData['template_vars']['percentage_decrease%'] 	= $percentageDecrease . "%";
		$this->creatorData['template_vars']['decrease_adj'] 		= $this->getRDPChangeAdjective($percentageDecrease);
		
		$classData = $this->getClassById($action['veh_class_id']);	
		$this->creatorData['template_vars']['class']  = $classData[0]['name'];		
		
		return true;
		
	}
}