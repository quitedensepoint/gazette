<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models\Chokepoint;

/**
 * Interface describing the functions needed within a type of Chokepoint 
 * 
 * @author Jason "drloon" Rout
 */
interface ChokepointInterface {
	
	/**
	 * Retrieve the Type ID of the Chokepoint
	 * 
	 * @return integer Numeric GameID in the database
	 */
	public function getTypeId();
	
}
