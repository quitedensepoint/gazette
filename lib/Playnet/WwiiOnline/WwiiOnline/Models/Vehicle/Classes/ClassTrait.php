<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Classes;

/**
 * This trait is something added to all the Vehicle classes to provide a standard
 * implementation of the interfaces
 * 
 * @author Jason "drloon" Rout
 */
trait ClassTrait {
	
	public static function getClassId() {
		return self::CLASS_ID;
	}

	public static function getClassName() {
		return self::CLASS_NAME;
	}
}
