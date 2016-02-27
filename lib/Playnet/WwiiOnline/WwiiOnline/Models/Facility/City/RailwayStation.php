<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models\Facility\City;

/**
 * Model for a RailwayStation object
 * 
 * This model maps to the wwiionline.strat_facility table
 *
 * @author Jason "drloon" Rout
 */
class RailwayStation extends City {
	
	const SUBTYPE_ID = 2;
	
	public function __construct() 
	{
		parent::__construct();
		
		self::$subTypeId = self::SUBTYPE_ID;
	}
}
