<?php

/**
 * Executes the logic to generate a story from the 
 * "RDP Intro" source.
 */
class StoryRDPIntro extends StoryRDPBase implements StoryInterface {
	
	public function isValid() {

		$action = $this->getRDPActionData('+');

		if(count($action) == 0)
		{
			return false;
		}
		$action = $action[0];
		
		/** this vehicle classification **
		$this->creatorData['template_vars']['vehicle'] = 
		*/
		// branch is defined by the veh_category_id, veh_class_id, veh_type_id, of the action so have to
		// use the classification function
		
		$randomCity = $this->getRandomCityForCountry($action['country_id'])[0];
		
		$this->creatorData['template_vars']['city']  = $randomCity['name'];
		
		$data = intval($action['next_capacity']);
		$this->creatorData['template_vars']['data']				= $data;
		$this->creatorData['template_vars']['start_quantity'] 	= $data;
		$this->creatorData['template_vars']['start_adj'] 		= $this->getRDPChangeAdjective($data);		

		return true;
		
	}
}