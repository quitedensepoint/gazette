<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models\Facility\Factory;

/**
 * 
 * This model maps to the wwiionline.strat_facility table
 *
 * @author Jason "drloon" Rout
 */
class Vehicle extends Factory {
	
	const SUBTYPE_ID = 7;
	
	public function __construct() 
	{
		parent::__construct();
		
		self::$subtypeId = self::SUBTYPE_ID;
	}
}
