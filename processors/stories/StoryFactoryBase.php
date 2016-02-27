<?php

use Playnet\WwiiOnline\WwiiOnline\Models\Facility\Factory\Vehicle;

/**
 * Executes the logic to generate a story from the 
 * "Factory Captured" source.
 * 
 * This checks all of the factory towns for a side, and checks to see if
 * all of the factories reduced in capacity.
 */
abstract class StoryFactoryBase extends StoryBase {
	
	/**
	 * The max number of minutes that an output from factory can be from the most recent output
	 * to be considered part of the valid data set
	 * 
	 * @var integer
	 */
	protected static $outputMaxIntervalMinutes = 1;
				
	/**
	 * Retrieve the factory output data
	 * 
	 * @param integer $sideId
	 * @return array
	 */
	public function getFactoryOutputs($sideId)
	{
		$dbHelper = new dbhelper($this->dbConnWWIIOnline);
		
		$params = [
			$sideId,
			Vehicle::getTypeId(),
			Vehicle::getSubtypeId(),
			self::$outputMaxIntervalMinutes
	
		];

		return $dbHelper
			->get("select f.facility_oid, f.cp_oid, f.side, f.originalside, o.damage_pctg, sc.name as cp_name, wc.fullName as country_name, wc.countryID as country_id "
				. "from  strat_facility f "
				. "left join strat_factory_outputs o on f.country = o.country "
				. "inner join strat_cp sc on sc.cp_oid = f.cp_oid "
				. "inner join wwii_country wc on wc.countryID = sc.country "
				. "where (f.facility_oid = o.facility_oid or o.facility_oid is null) and o.side = ? and facility_type = ? and facility_subtype = ? "
				. "AND output_time >= (SELECT DATE_SUB(MAX(output_time),INTERVAL ? MINUTE) FROM strat_factory_outputs)",$params);	
						
	}

}