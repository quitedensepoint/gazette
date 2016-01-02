<?php

/**
 * Executes the logic to generate a story from the 
 * "Sortie Averages" source.
 * 
 * Gives sortie averages over the last 1000 sorties
 * 
 */
class StorySortieAverages extends StoryBase implements StoryInterface {
	
	public function isValid() {

		/**
		 * Some sensible defaults
		 */
		$sortieData = [
			'sorties_total'		=> 0,
			'average_time'		=> 0,
			'average_kills'		=> 0,
			'average_rtb'		=> 0,
			'average_rescued'	=> 0,
			'average_mia'		=> 0,
			'average_kia'		=> 0,
			'average_attack'	=> 0,
			'average_defense'	=> 0,
			'average_success'	=> 0,
			'missions'			=> 0
		];
		
		/**
		 * Go through each sortie and collect the results
		 */
		foreach($this->getSortieData() as $sortie)
		{
			$sortieData['sorties_total']++;

			$sortieData['average_time']			+= ($sortie['returned'] - $sortie['spawned']);
			$sortieData['average_kills']		+= $sortie['kills'];
			$sortieData['average_success']		+= $sortie['successful'];

			if($sortie['mission_id'] > 0){
				$sortieData['missions']++;
				$sortieData['average_attack']		+= ($sortie['mission_type'] == 0 or $sortie['mission_type'] == 2) ? 1: 0;
				$sortieData['average_defense']		+= ($sortie['mission_type'] == 1 or $sortie['mission_type'] == 3) ? 1: 0;
			}

			$sortieData['average_rtb']			+= ($sortie['rtb'] == 0) ? 1: 0;
			$sortieData['average_rescued']		+= ($sortie['rtb'] == 1) ? 1: 0;
			$sortieData['average_mia']			+= ($sortie['rtb'] == 2) ? 1: 0;
			$sortieData['average_kia']			+= ($sortie['rtb'] == 3) ? 1: 0;			
		}
		
		/**
		 * Add data for the templates based on the collected data
		 */
		$this->creatorData['template_vars']['sorties_total']	= $sortieData['sorties_total'];
		$this->creatorData['template_vars']['average_time'] 	= intval(($sortieData['average_time'] / $sortieData['sorties_total']) / 60);
		$this->creatorData['template_vars']['average_kills'] 	= sprintf("%.2f", $sortieData['average_kills'] / $sortieData['sorties_total']);
		$this->creatorData['template_vars']['average_success'] 	= intval(($sortieData['average_success'] / $sortieData['sorties_total']) * 100);
		$this->creatorData['template_vars']['average_attack']	= intval(($sortieData['average_attack'] / $sortieData['missions']) * 100);
		$this->creatorData['template_vars']['average_defense'] 	= intval(($sortieData['average_defense'] / $sortieData['missions']) * 100);
		$this->creatorData['template_vars']['average_rtb'] 		= intval(($sortieData['average_rtb'] / $sortieData['sorties_total']) * 100);
		$this->creatorData['template_vars']['average_rescued'] 	= intval(($sortieData['average_rescued'] / $sortieData['sorties_total']) * 100);
		$this->creatorData['template_vars']['average_mia'] 		= intval(($sortieData['average_mia'] / $sortieData['sorties_total']) * 100);
		$this->creatorData['template_vars']['average_kia'] 		= intval(($sortieData['average_kia'] / $sortieData['sorties_total']) * 100);		
		
		return true;

	}
	
	/**
	 * Get the last 1000 sorties
	 * 
	 * @return array
	 *  
	 */
	public function getSortieData()
	{
		$dbHelper = new dbhelper($this->dbConnWWIIOnline);
		
		$query = $dbHelper
			->prepare("SELECT UNIX_TIMESTAMP(spawn_time) as spawned, UNIX_TIMESTAMP(return_time) as returned, kills, rtb, mission_id, mission_type, successful "
				. "FROM wwii_sortie "
				. "WHERE spawn_time IS NOT NULL AND return_time IS NOT NULL "
				. "ORDER BY ADDED DESC LIMIT 1000", []);	

		return $dbHelper->getAsArray($query);					
	}
}