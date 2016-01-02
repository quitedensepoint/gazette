<?php

/**
 * Executes the logic to generate a story from the 
 * "RDP Intro" source.
 */
class StoryRDPIntro extends StoryRDPBase implements StoryInterface {
	
	public function isValid() {

		$action = $this->getRDPActionData('+', $this->creatorData['country_id']);

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
		
		$randomCity = $this->getRandomCityForCountry($action['country_id'])[0];
		
		$this->creatorData['template_vars']['city']  = $randomCity['name'];
		
		$data = intval($action['next_capacity']);
		$this->creatorData['template_vars']['data']				= $data;
		$this->creatorData['template_vars']['start_quantity'] 	= $data;
		$this->creatorData['template_vars']['start_adj'] 		= $this->getRDPChangeAdjective($data);		
		
		$enemyCountry = $this->getRandomEnemyCountry($this->creatorData['side_id']);
		$enemyVehicle = $this->getVehicleByClassification($enemyCountry['country_id'], $action['veh_category_id'], $action['veh_class_id'], $action['veh_type_id']);
		$this->creatorData['template_vars']['enemy_vehicle']	= $enemyVehicle[0]['name'];	

		return true;
		
	}
}