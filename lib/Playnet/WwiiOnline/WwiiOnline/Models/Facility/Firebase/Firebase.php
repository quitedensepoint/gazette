<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models\Facility\Firebase;

use Playnet\WwiiOnline\WwiiOnline\Models\Facility\Facility;

/**
 * Model for the Base type of Facility game object. 
 * 
 * This model maps to the wwiionline.strat_facility table
 *
 * @author Jason "drloon" Rout
 */
class Firebase extends Facility {
	
	const TYPE_ID = 7;
	
	public function __construct() 
	{
		self::$typeId = self::TYPE_ID;
	}
}
