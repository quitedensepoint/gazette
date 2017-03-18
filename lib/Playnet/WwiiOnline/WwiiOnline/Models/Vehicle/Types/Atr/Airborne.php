<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Types\Atr;

use Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Types\TypeInterface;
use Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Classes\Para;

/**
 * Represents an airborne ATR Unit
 *
 * @author Jason "drloon" Rout
 */
abstract class Airborne extends Para implements TypeInterface {
	
	const TYPE_ID = 9;
	
	public static function getTypeId() {
		return static::TYPE_ID;
	}

}
