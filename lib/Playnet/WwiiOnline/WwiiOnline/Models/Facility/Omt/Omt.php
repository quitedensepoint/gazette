<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models\Facility\Omt;

use Playnet\WwiiOnline\WwiiOnline\Models\Facility\Facility;

/**
 * Model for the Omt type of Facility game object. 
 * 
 * To current knowledge, this is not used in game
 *
 * @author Jason "drloon" Rout
 */
class Omt extends Facility {
	
	const TYPE_ID = 5;
	
	public function __construct() 
	{
		self::$typeId = self::TYPE_ID;
	}
}
