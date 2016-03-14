<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Types;

/**
 * Reference to a type of vehicle.
 * 
 * Note that due to the way application is structured, a typeid in one class (e.g. a tank) may have
 * the same type id on a infantry class, but with different class, category and/or branch ids
 * 
 * @author Jason "drloon" Rout
 */
interface TypeInterface {
	
	/**
	 * Retrieve the ID of the type
	 * 
	 * @return integer Numeric GameID of the database
	 */
	public static function getTypeId();

}