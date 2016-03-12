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
trait German {
	
	// Uses the Axis Side trait
	use Axis;
	
	protected static $countryId = 4;
	protected static $countryName = 'Germany';
	protected static $countryAdjective = 'German';
	
	public function getCountryId()		
	{
		return self::$countryId;
	}
	
	public function getCountryName()		
	{
		return self::$countryName;
	}
	
	public function getCountryAdjective()		
	{
		return self::$countryAdjective;
	}	
}
