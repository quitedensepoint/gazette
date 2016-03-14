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
trait Us {
	
	// Uses the Allied Side trait
	use Allied;
	
	protected static $countryId = 2;
	protected static $countryName = 'United States';
	protected static $countryAdjective = 'US';
	
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
