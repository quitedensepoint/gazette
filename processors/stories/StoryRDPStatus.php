<?php

/**
 * Executes the logic to generate a story from the 
 * "RDP Status" source.
 */
class StoryRDPStatus extends StoryRDPBase implements StoryInterface {
	
	const OUTPUT_PERIOD = 600;
	
	public function isValid() {

		$completionStats = $this->getCompletionStatus($this->creatorData['country_id']);

		if(count($completionStats) == 0)
		{
			return false;
		}
		$completionStats = $completionStats[0];
		
		$factories = $this->getLatestFactoryOutputs($this->creatorData['country_id']);
		
		$numProducingFactories = count($factories);
		$health = 0;
		$damage = 0;

		foreach($factories as $factory)
		{
			$health += 100;
			// If damage pctg is null, the factory is not owned by the default country
			$damage += is_null($factory['damage_pctg']) ? 100 : intval($factory['damage_pctg']);
		}
		
		$balance = intval($completionStats['totalGoal']) - intval($completionStats['totalProduction']);
		$output = intval(($health - $damage) / $numProducingFactories);
		$this->creatorData['template_vars']['output'] = $output;
		$this->creatorData['template_vars']['completion'] = $completionStats['completion'] * 100;
		$this->creatorData['template_vars']['goal'] = $completionStats['totalGoal'];
		$this->creatorData['template_vars']['balance'] = $balance;
		$this->creatorData['template_vars']['eta'] = $this->calculateETA($balance, intval($numProducingFactories * ($output * .01)));
		
		/** this vehicle classification **
		$this->creatorData['template_vars']['vehicle'] = 
		*/
		// branch is defined by the veh_category_id, veh_class_id, veh_type_id, of the action so have to
		// use the classification function
		/*
		$randomCity = $this->getRandomCityForCountry($action['country_id'])[0];
		
		$this->creatorData['template_vars']['city']  = $randomCity['name'];
		
		$currentCapacity = intval($action['current_capacity']);
		$data = intval($action['next_capacity']) - $currentCapacity;
		$this->creatorData['template_vars']['data']					= $data;
		$this->creatorData['template_vars']['spawns']				= $currentCapacity;
		$this->creatorData['template_vars']['quantity_decrease'] 	= $data;
		$percentageDecrease = intval($data / $currentCapacity * 100);
		$this->creatorData['template_vars']['percentage_decrease%'] 	= $percentageDecrease;
		$this->creatorData['template_vars']['decrease_adj'] 		= $this->getRDPChangeAdjective($percentageDecrease);
		
	$vars->{'OUTPUT'} 		= int(($health - $damage) / $producers);
	$vars->{'OUTPUT'}		= ($vars->{'OUTPUT'} > 100) ? 100: $vars->{'OUTPUT'};
	$vars->{'COMPLETION'}	= ($status->{'completed'} > 100) ? 100: $status->{'completed'};
	
	$vars->{'GOAL'} 		= $status->{'goal'};
	$vars->{'PRODUCED'} 	= $status->{'produced'};
	$vars->{'BALANCE'} 		= $vars->{'GOAL'} - $vars->{'PRODUCED'};
	$vars->{'ETA'} 			= &estimateCompletion($vars->{'BALANCE'}, int($producers * ($vars->{'OUTPUT'} * .01)));		
		*/
		return true;
		
	}
	
	/**
	 * Retrieve the RDP completion statistics for a specific country
	 * 
	 * @param integer $countryId
	 * @return array
	 */
	public function getCompletionStatus($countryId)
	{
		$dbHelper = new dbhelper($this->dbConnToe);
		
		$query = $dbHelper
			->prepare("SELECT * FROM v_rdp_completion_stats WHERE countryID =? limit 1",[$countryId]);	
		
		return $dbHelper->getAsArray($query);			
	}
	
	/**
	 * Get a list of all the factories and their outputs for the latest rdp cycle
	 * 
	 * @param integer $countryId
	 * @return array
	 */
	public function getLatestFactoryOutputs($countryId) {
		
		$dbHelper = new dbhelper($this->dbConnWWIIOnline);
		
		$query = $dbHelper
			->prepare("select f.facility_oid, o.damage_pctg from  strat_facility f left join strat_factory_outputs o on f.country = o.country " 
				. "where (f.facility_oid = o.facility_oid or o.facility_oid is null) and f.country = 1 and facility_type = 2 and facility_subtype = 7 "
				. "AND output_time = (SELECT MAX(output_time) FROM strat_factory_outputs)",[$countryId]);	
		
		return $dbHelper->getAsArray($query);			
			
	}
	
	/**
	 * Retrieve the estimated time to cycle is completed
	 * 
	 * @param integer $goal The production goal to be met
	 * @param inetger $productionData
	 * @return string
	 */
	public function calculateETA($goal, $productionData)
	{
		if($productionData > 0)
		{
			$seconds = intval(($goal / $productionData) * self::OUTPUT_PERIOD);
			
			$zero = new DateTime("@0");
			$offset = new DateTime("@$seconds");
						
			$dateInterval = $zero->diff($offset);
			
			return $dateInterval->format("%h hours, %i minutes, %s seconds ");
		}
		
		return "unknown";
	}
}