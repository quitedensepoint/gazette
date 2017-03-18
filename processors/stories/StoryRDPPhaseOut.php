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

		$this->creatorData['template_vars']['vehicle'] = $vehicle['name'];
		$this->creatorData['template_vars']['vehicle_short'] = $vehicle['short_name'];
		
		$randomCity = $this->getRandomCityForCountry($action['country_id'])[0];		
		$this->creatorData['template_vars']['city']  = $randomCity['name'];
		
		$randomFactoryCity = $this->getRandomFactoryCityForCountry($action['country_id'])[0];		
		$this->creatorData['template_vars']['factory_city']  = $randomFactoryCity['name'];		
		
		$classData = $this->getClassById($action['veh_class_id']);	
		$this->creatorData['template_vars']['class']  = $classData[0]['name'];
		
		$categoryData = $this->getCategoryById($action['veh_category_id']);
		$this->creatorData['template_vars']['category']  = $categoryData[0]['name'];
		
		$branchData =  $this->getBranchById($categoryData[0]['branch_id']);
		$this->creatorData['template_vars']['branch']  = $branchData[0]['name'];
		
		$currentCapacity = intval($action['current_capacity']);
		$data = intval($action['next_capacity']) - $currentCapacity	;	
		$percentageDecrease = abs(intval($data / $currentCapacity * 100));
		$this->creatorData['template_vars']['percentage_decrease%'] 	= $percentageDecrease . "%";		
		
		return true;
		
	}
}