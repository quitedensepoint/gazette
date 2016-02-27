<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models\Facility\City;

use Playnet\WwiiOnline\WwiiOnline\Models\Facility\Facility;

/**
 * Model for the Facility game object. A facility object represents a "CP" or Firebase
 * or other capturable point in the game
 * 
 * This model maps to the wwiionline.strat_facility table
 *
 * @author Jason "drloon" Rout
 */
class City extends Facility {
	
	const TYPE_ID = 1;
	
	public function __construct() 
	{
		self::$typeId = self::TYPE_ID;
	}
}
