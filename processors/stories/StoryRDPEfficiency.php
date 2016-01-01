<?php

/**
 * Executes the logic to generate a story from the 
 * "RDP Efficiency" source.
 */
class StoryRDPEfficiency extends StoryRDPBase implements StoryInterface {
	
	public function isValid() {
		
		$action = $this->getRDPActionData('=', $this->creatorData['country_id']);
		if(count($action) == 0)
		{
			return false;
		}
		$action = $action[0];
		
		/**
		 * Pull the vehicle data for the sortie
		 */
		$vehicle = $this->getVehicleByClassification($action['country_id'], $action['veh_category_id'], $action['veh_class_id'], $action['veh_type_id']);

		if(count($vehicle) == 0)
		{
			return false;
		}
		$vehicle = $vehicle[0];		

		$this->creatorData['template_vars']['vehicle'] = $vehicle['name'];
		$this->creatorData['template_vars']['vehicle_short'] = $vehicle['short_name'];
		
		$contestedCity = $this->getRandomContestedCity();
		$contestedCity = count($contestedCity) == 1 ? $contestedCity[0]['name'] : 'an unnamed town';
		$this->creatorData['template_vars']['any_con_city'] = $contestedCity;
		
		$randomCity = $this->getRandomCityForCountry($action['country_id'])[0];
		
		$this->creatorData['template_vars']['city']  = $randomCity['name'];
		
		return true;
		
	}
}