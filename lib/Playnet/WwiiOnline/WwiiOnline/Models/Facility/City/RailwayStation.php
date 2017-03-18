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
	
	/**
	 * Overrides the static value in the base class
	 * 
	 * @var integer
	 */	
	protected static $subtypeId = 2;
}
