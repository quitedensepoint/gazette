<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Traits;

/**
 * A trait that can be used in a class to describe the axis side
 * 
 * This trait will hold the implementation of the CountryInterface in the class that uses it
 *
 * @author Jason "drloon" Rout
 */
trait British {
	
	// Uses the Axis Side trait
	use Allied;
	
	public static $countryId = 1;
	public static $countryName = 'United Kingdom';
	public static $countryAdjective = 'British';
	
	public static function getCountryId()		
	{
		return static::$countryId;
	}
	
	public static function getCountryName()		
	{
		return static::$countryName;
	}
	
	public static function getCountryAdjective()		
	{
		return static::$countryAdjective;
	}	
}
