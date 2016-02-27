<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models\Facility\Depot;

/**
 * Model for the Facility game object. A facility object represents a "CP" or Firebase
 * or other capturable point in the game
 * 
 * This model maps to the wwiionline.strat_facility table
 *
 * @author Jason "drloon" Rout
 */
class Truck extends Depot {
	
	const SUBTYPE_ID = 3;
	
	public function __construct() 
	{
		parent::__construct();
		
		self::$subtypeId = self::SUBTYPE_ID;
	}
}
