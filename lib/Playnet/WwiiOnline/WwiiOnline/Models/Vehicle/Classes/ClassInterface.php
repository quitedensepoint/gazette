<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Classes;

/**
 * Reference to a vehicle class (not PHP classes!)
 * 
 * @author Jason "drloon" Rout
 */
interface ClassInterface {
	
	/**
	 * Retrieve the ID of the class
	 * 
	 * @return integer Numeric GameID of the database
	 */
	public function getClassId();
	
	/**
	 * Retrieve the name of the class
	 */
	public function getClassName();
}
