<?php

/**
 * Executes the logic to generate a story from the 
 * "RDP Increase" source.
 * 
 * Template Placeholders
 *  VEHICLE
 *  VEHICLE_SHORT
 *  CLASS
 *  DIRECTION_ADJ
 *  CITY
 *  FRONT_CITY
 *  FACTORY_CITY
 *  DATA
 *  SPAWNS
 *  QUANTITY_INCREASE
 *  PERCENTAGE_INCREASE%
 *  PERCENT_INCREASE
 *  INCREASE_ADJ
 *  BRANCH
 */
class StoryRDPIncrease extends StoryRDPBase implements StoryInterface {
	
	public function isValid() {

		$action = $this->getRDPActionData('>', $this->creatorData['country_id']);

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
				
		$vehicleClass = $this->getClassById($action['veh_class_id']);
		if(count($vehicleClass) == 0)
		{
			return false;
		}
		$vehicleClass = $vehicleClass[0];			
		
		$this->creatorData['template_vars']['vehicle'] = $vehicle['name'];
		$this->creatorData['template_vars']['vehicle_short'] = $vehicle['short_name'];
		$this->creatorData['template_vars']['class'] = $vehicleClass['name'];
		
		$this->creatorData['template_vars']['direction_adj'] = $this->getRandomDirection()['adjective'];		
		
		$randomCity = $this->getRandomCityForCountry($action['country_id'])[0];
		$this->creatorData['template_vars']['city']  = $randomCity['name'];
		
		$randomFactoryCity = $this->getRandomFactoryCityForCountry($action['country_id'])[0];
		$this->creatorData['template_vars']['factory_city'] = $randomFactoryCity['name'];
		
		$randomFrontlineCity = $this->getRandomFrontlineCityForCountry($action['country_id'])[0];
		$this->creatorData['template_vars']['front_city'] = $randomFrontlineCity['name'];		
		
		$currentCapacity = intval($action['current_capacity']);
		$data = intval($action['next_capacity']) - $currentCapacity;
		$this->creatorData['template_vars']['data']					= $data;
		$this->creatorData['template_vars']['spawns']				= $currentCapacity;
		$this->creatorData['template_vars']['quantity_increase'] 	= $data;
		$percentageIncrease = intval($data / $currentCapacity * 100);
		
		/**
		 * Note duplicated values
		 * @todo Fix placeholder naming across template system
		 */
		$this->creatorData['template_vars']['percentage_increase%'] 	= $percentageIncrease . "%";
		$this->creatorData['template_vars']['percent_increase'] 	= $percentageIncrease . "%";
		$this->creatorData['template_vars']['increase_adj'] 		= $this->getRDPChangeAdjective($percentageIncrease);
		
		/**
		 * Branch placeholder
		 */
		$branch = $this->getBranchById($vehicle['branch_id']);
		if(count($branch) == 0)
		{
			return false;
		}
		$branch = $branch[0];
		$this->creatorData['template_vars']['branch'] = $branch['name'];		
		
		return true;
		
	}
}