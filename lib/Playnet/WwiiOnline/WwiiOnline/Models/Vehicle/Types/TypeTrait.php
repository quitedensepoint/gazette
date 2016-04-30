<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Types;

/**
 * This trait is something added to all the Vehicle types to provide a standard
 * implementation of the interfaces
 * 
 * @author Jason "drloon" Rout
 */
trait TypeTrait {
	
	public static function getTypeId() {
		return static::$typeId;
	}
}
