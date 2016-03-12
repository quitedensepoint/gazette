<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models\Chokepoint;

/**
 * This trait is something added to all the Vehicle branches to provide a standard
 * implementation of the interfaces
 * 
 * @author Jason "drloon" Rout
 */
trait ChokepointTrait {
	
	public static function getTypeId() {
		return self::TYPE_ID;
	}
	
}