<?php

/*
 * Executes the logic to generate a story from the 
 * "Spawn Lister" source.
 * 
 * 
 * => This story Generates the spawn lists for the 'In the Field' - shows what vehicle type each country has currently available to spawn
 */
class StorySpawnLister extends StoryBase implements StoryInterface {
	
	public function isValid() {

		$spawnables = $this->getSpawnables($this->creatorData['country_id']);
		
		/**
		 * @todo figure out how to remove the formatted from the result set
		 */
		$list = "Infantry<br />";
		
		foreach($spawnables as $spawnable)
		{
			$vehicle = $this->getVehicleByClassification($spawnable['countryid'], $spawnable['categoryid'], $spawnable['classid'], $spawnable['typeid']);
			if(count($vehicle) == 0)
			{
				// Can't find this vehicle, skip to the next spawnable
				continue;
			}
			
			$list .= $vehicle[0]['short_name'] . "<br >";
		}
		
		$this->creatorData['template_vars']['list'] = $list;
		
		return true;
	}
	
	/**
	 * Retrieves a list of spawnable units
	 * 
	 * @param type $countryId
	 * @return type
	 */
	public function getSpawnables($countryId)
	{
		$dbHelper = new dbhelper($this->dbConnWWIIOnline);
		
		$query = $dbHelper
			->prepare("SELECT DISTINCT v.countryid,v.categoryid,v.classid,	v.typeid "
				. "FROM wwii_vehtype v "
				. "INNER JOIN toe.vehicles tv ON tv.vehtype_oid = v.vehtype_oid "
				. "INNER JOIN strat_hc_units h ON h.toe_template = tv.id "
				. "WHERE v.countryid = ? AND v.categoryid != 4 AND v.classid != 9 AND tv.capacity > 0 "
				. "ORDER BY v.categoryid,v.classid,v.typeid", [$countryId]);	
		
		return $dbHelper->getAsArray($query);					
	}
	

}
