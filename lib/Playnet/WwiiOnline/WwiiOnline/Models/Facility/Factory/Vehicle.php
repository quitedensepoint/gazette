<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models\Facility\Factory;

/**
 * Represents a Factory that makes Vehicles
 * 
 * This model maps to the wwiionline.strat_facility table
 *
 * @author Jason "drloon" Rout
 */
class Vehicle extends Factory {
	
	/**
	 * Overrides the static value in the base class
	 * 
	 * @var integer
	 */	
	protected static $subtypeId = 7;

}
