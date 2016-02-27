<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Traits;

/**
 * A trait that can be used in a class to describe the allied side
 * 
 * This trait will hold the implementation of the SideInterface in the class that uses it
 *
 * @author Jason "drloon" Rout
 */
trait Allied {
	
	protected static $sideId = 1;
	protected static $sideName = 'Allied';
	
	/**
	 * Get the game of the side
	 * 
	 * @return integer
	 * 
	 */
	public function getSideId()		
	{
		return self::$sideId;
	}
	
	public function getSideName()		
	{
		return self::$sideName;
	}	
}
