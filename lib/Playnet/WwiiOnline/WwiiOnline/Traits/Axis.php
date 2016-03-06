<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Traits;

/**
 * A trait that can be used in a class to describe the axis side
 * 
 * This trait will hold the implementation of the SideInterface in the class that uses it
 *
 * @author Jason "drloon" Rout
 */
trait Axis {
	
	protected static $sideId = 2;
	protected static $sideKey = 'axis';
	protected static $sideName = 'Axis';
	protected static $sideAdjective = 'Axis';	
	
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
	
	public function getSideKey()		
	{
		return self::$sideKey;
	}	
	
	public function getSideName()		
	{
		return self::$sideName;
	}
	
	public function getSideAdjective()		
	{
		return self::$sideAdjective;
	}	
}
