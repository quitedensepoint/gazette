<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models\Chokepoint;

/**
 * This trait is something added to all the Chokepoint types to provide a standard
 * implementation of the interfaces
 * 
 * @author Jason "drloon" Rout
 */
trait ChokepointTrait {
	
	public static function getTypeId() {
		return static::TYPE_ID;
	}
	
}