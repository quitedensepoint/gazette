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
	public static function getSideId();
	
	/**
	 * Retrieve the identifying key for the side (e.g. "allied" or "axis")
	 * 
	 * return @string
	 */
	public static function getSideKey();	
	
	/**
	 * Retrieve the name of the side the object belongs to (e.g. "Allies" or "Axis")
	 * 
	 * return @string
	 */
	public static function getSideName();
	
	/**
	 * Retrieve an adjective describing the side (e.g. "Allied" or "Axis")
	 * 
	 * return @string
	 */
	public static function getSideAdjective();	
}
