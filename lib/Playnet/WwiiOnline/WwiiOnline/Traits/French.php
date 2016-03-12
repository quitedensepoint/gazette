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
trait French {
	
	// Uses the Allied Side trait
	use Allied;
	
	protected static $countryId = 3;
	protected static $countryName = 'France';
	protected static $countryAdjective = 'French';
	
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
