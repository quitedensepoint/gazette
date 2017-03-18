<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Types\Atr;

use Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Types\TypeInterface;
use Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Classes\Troop;

/**
 * Represents an airborne ATR Unit
 *
 * @author Jason "drloon" Rout
 */
abstract class Ground extends Troop implements TypeInterface {
	
	const TYPE_ID = 9;
	
	public static function getTypeId() {
		return static::TYPE_ID;
	}

}
