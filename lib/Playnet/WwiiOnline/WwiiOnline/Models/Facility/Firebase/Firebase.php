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
	
	/**
	 * Overrides the static value in the base class
	 * 
	 * @var integer
	 */
	protected static $typeId = 7;
}
