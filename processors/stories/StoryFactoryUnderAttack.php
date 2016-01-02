<?php

/**
 * Executes the logic to generate a story from the 
 * "Factory Under Attack" source.
 * 
 * This checks all of the factory towns for a side, and checks to see if
 * all of the factories reduced in capacity.
 */
class StoryFactoryUnderAttack extends StoryBase implements StoryInterface {
		
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
			$health = $damage = $captured = $producers = $capturingCountryId = 0;
			
			foreach($chokepoint['factories'] as $fact)
			{	
				$producers++;
				$health +=100;
				
				$capturingCountryId = $fact['country_id'];
				
				if($fact['side'] != $fact['originalside'])
				{
					$captured++;
					
					$damage += 100;					
				}
				else
				{
					$damage += $fact['damage_pctg'];
				}
			}
			
			/**
			 * If all factories are captured
			 */
			if($captured > 0 and $captured < $producers)			
			{
				$this->creatorData['template_vars']['factory_city'] = $chokepoint['cp_name'];
				$this->creatorData['template_vars']['factory_damage'] = intval(($damage / $health) * 100);
				$this->creatorData['template_vars']['factory_output'] = intval(($health - $damage) / $producers );				
				
				$country = $this->getCountryById($capturingCountryId);
				
				$this->creatorData['template_vars']['capturing_country']		= $country['name'];
				$this->creatorData['template_vars']['capturing_country_adj']	= $country['adjective'];
				$this->creatorData['template_vars']['capturing_side']			= $country['side'];
				$this->creatorData['template_vars']['attacking_country_adj']	= $country['adjective'];
				
				$percentEnemy = intval((($captured * 100) / (100 * $producers)) * 100);
				$this->creatorData['template_vars']['percent_enemy'] 	= $percentEnemy;
				$this->creatorData['template_vars']['percent_owned'] 	= 100 - $percentEnemy;
							

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
				$this->creatorData['template_vars']['day'] = $dt->format('l');
				$this->creatorData['template_vars']['month'] = $dt->format('F');
				$this->creatorData['template_vars']['day_ord'] = $dt->format('j');

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
			->prepare("select f.facility_oid, f.cp_oid, f.side, f.originalside, o.damage_pctg, sc.name as cp_name, wc.fullName as country_name, wc.countryID as country_id "
				. "from  strat_facility f "
				. "left join strat_factory_outputs o on f.country = o.country "
				. "inner join strat_cp sc on sc.cp_oid = f.cp_oid "
				. "inner join wwii_country wc on wc.countryID = sc.country "
				. "where (f.facility_oid = o.facility_oid or o.facility_oid is null) and o.side = ? and facility_type = 2 and facility_subtype = 7 "
				. "AND output_time >= (SELECT DATE_SUB(MAX(output_time),INTERVAL 1 MINUTE) FROM strat_factory_outputs)",[$sideId]);	
		
		return $dbHelper->getAsArray($query);				
	}

}