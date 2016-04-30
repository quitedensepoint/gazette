<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models;

/**
 * This interface defines some functions that need to be in any class that references "country"
 * 
 * Use the relevant Trait to define the implementation
 *
 * @author Jason "drloon" Rout
 */
interface CountryInterface extends SideInterface {
	
	/**
	 * Retrieve the ID of the country this object belongs to
	 * 
	 * @return integer
	 */
	public static function getCountryId();
	
	/**
	 * Retrieve the name of the country the object belongs to
	 * 
	 * return @string
	 */
	public static function getCountryName();
	
	/**
	 * Retrieve the adjectove of the country the object belongs to
	 * 
	 * return @string
	 */
	public static function getCountryAdjective();	
}
