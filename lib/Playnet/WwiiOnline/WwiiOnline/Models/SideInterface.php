<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models;

/**
 * This interface defines some functions that need to be in any class that references "side"
 * 
 * Use the relevant Trait to define the implementation
 *
 * @author Jason "drloon" Rout
 */
interface SideInterface {
	
	/**
	 * Retrieve the ID of the side this object belongs to
	 * 
	 * @return integer
	 */
	public function getSideId();
	
	/**
	 * Retrieve the name of the side the object belongs to
	 * 
	 * return @string
	 */
	public function getSideName();
}
