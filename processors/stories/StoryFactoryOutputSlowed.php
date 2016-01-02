<?php

/**
 * Executes the logic to generate a story from the 
 * "Factory Output Shutdown" source.
 * 
 * This checks all of the factory towns for a side, and checks to see if
 * all of the factories reduced in capacity.
 */
class StoryFactoryOutputSlowed extends StoryBase implements StoryInterface {
		
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
			$health = $damage =  $producers = 0;
			
			foreach($chokepoint['factories'] as $fact)
			{	
				$producers++;
				$health +=100;
				
				if($fact['side'] != $fact['originalside'])
				{
					$damage += 100;					
				}
				else
				{
					$damage += $fact['damage_pctg'];
				}
			}
			
			/**
			 * If any factories are damaged
			 */
			if($health - $damage != $health)
			{
				$this->creatorData['template_vars']['factory_city'] = $chokepoint['cp_name'];			
				$this->creatorData['template_vars']['factory_damage'] = intval(($damage / $health) * 100);
				$this->creatorData['template_vars']['factory_output'] = intval(($health - $damage) / $producers );
				
				$enemyCountry = $this->getRandomEnemyCountry($this->creatorData['side_id']);			
				$this->creatorData['template_vars']['enemy_country_adj'] = $enemyCountry['adjective'];
				
				$vehicleClass = $this->getRandomVehicleClass();
				$this->creatorData['template_vars']['class'] = $vehicleClass['name'];
				
				$this->creatorData['template_vars']['direction'] = $this->getRandomDirection()['name'];
				$this->creatorData['template_vars']['intensity'] = $this->getRandomIntensity();
				
				$city = $this->getRandomCityForCountry($this->creatorData['country_id']);
				$this->creatorData['template_vars']['city'] = $city[0]['name'];
				return true;
			}
		}
		
		return false;		
	}
	
	/**
	 * Retrieve the factory output data
	 * 
	 * @param integer $sideId
	 * @return array
	 */
	public function getFactoryOutputs($sideId)
	{
		$dbHelper = new dbhelper($this->dbConnWWIIOnline);
		
		$query = $dbHelper
			->prepare("select f.facility_oid, f.cp_oid, f.side, f.originalside, o.damage_pctg, sc.name as cp_name, wc.fullName as country_name "
				. "from  strat_facility f "
				. "left join strat_factory_outputs o on f.country = o.country "
				. "inner join strat_cp sc on sc.cp_oid = f.cp_oid "
				. "inner join wwii_country wc on wc.countryID = sc.country "
				. "where (f.facility_oid = o.facility_oid or o.facility_oid is null) and o.side = ? and facility_type = 2 and facility_subtype = 7 "
				. "AND output_time >= (SELECT DATE_SUB(MAX(output_time),INTERVAL 1 MINUTE) FROM strat_factory_outputs)",[$sideId]);	
		
		return $dbHelper->getAsArray($query);				
	}

}