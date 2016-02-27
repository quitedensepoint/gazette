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
	
	/**
	 * Overrides the static value in the base class
	 * 
	 * @var integer
	 */
	protected static $typeId = 5;
}
