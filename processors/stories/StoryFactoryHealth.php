<?php

/**
 * Executes the logic to generate a story from the 
 * "Factory Health" source.
 */
class StoryFactoryHealth extends StoryRDPBase implements StoryInterface {
		
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
		
		$this->creatorData['chokepoints'] = $chokepoints;
		
		return true;
		
	}
	
	/**
	 * This has been overridden due to the unique nature of the data required by
	 * this particular story
	 * 
	 * @param array $template
	 */
	public function makeStory($template) {
		/**
		 * All of the factories in each chokepoint need to be reviwed and will
		 * built an outline of that chokepoint
		 * 
		 * We'll build a list of all chokepoints and their status
		 */
		
		$finalBody = '';
		
		foreach($this->creatorData['chokepoints'] as $chokepoint)
		{
			$producers = $health = $damage = $captured = $reduced = $repairs = 0;
			
			foreach($chokepoint['factories'] as $fact)
			{			
				$producers++;
				$health +=100;
				
				if($fact['side'] != $fact['originalside'])
				{
					$captured++;					
				}
				if(intval($fact['damage_pctg']) > 0)
				{
					$reduced++;					
				}				
				$damage += $fact['damage_pctg'];
			}
			
			$template_vars = [];
			$template_vars['factory_cp'] = $chokepoint['cp_name'];
			$template_vars['factory_country'] = $chokepoint['country_name'];				

			$factoryDamage = intval(($damage / $health) * 100);
			$template_vars['factory_damage'] = $factoryDamage;
			
			$factoryOutput = intval(($health - $damage) / $producers);
			$template_vars['factory_output'] = $factoryOutput;
		
			$template_vars['factory_status'] = 'Full Production';
			
			if($captured == $producers)
			{
				$template_vars['factory_status'] 	= 'Captured';
			}
			else if($captured > 0)
			{
				$template_vars['factory_status'] 	= 'Under Attack';
			}			
			else if($repairs > 0)
			{
				$template_vars['factory_status'] 	= 'Under Repairs';
			}
			else if($reduced > 0)
			{
				$template_vars['factory_status'] 	= 'Reduced Production';
			}

			$finalBody .= $this->parseStory($template_vars, $template['title'], $template['body'])['body'];
		}		
		
		return ['title' => '', 'body' => $this->makeVarieties($template, $finalBody)];
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