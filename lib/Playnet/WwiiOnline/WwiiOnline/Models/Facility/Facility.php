<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models\Facility;

/**
 * Model for the Facility game object. A facility object represents a "CP" or Firebase
 * or other capturable point in the game
 * 
 * This model maps to the wwiionline.strat_facility table
 * 
 * The data is based on the enums present in the game code, each of which _may_ have a related subtype. Each
 * type will have its own folder, containg the type definition and related subtypes
 *  
 * enum FACILITY_TYPE
 * {
 *   FACILITY_CITY=1,
 *   FACILITY_FACTORY,
 *   FACILITY_BASE,
 *   FACILITY_DEPOT,
 *   FACILITY_OMT,                // Off Map Transfer (OMT) - // UNUSED
 *   FACILITY_SHIPYARD,
 *   FACILITY_FIREBASE,
 *   FACILITY_AIRBASE,
 *   FACILITY_ARMYBASE,
 *   FACILITY_NAVALBASE,
 *
 * } 
 *
 * @author Jason "drloon" Rout
 */
abstract class Facility {
	
	/**
	 * Facility Types in the game
	 */
	const UNDEFINED	= 0;
	
	/**
	 * The facility type. This is usually overridden in the derived classes, however some database
	 * entries do appear as zero, hence the undefined value set
	 * 
	 * @var integer
	 */
	protected static $typeId = self::UNDEFINED;
	
	/**
	 * The facility subtype. This is usually overridden in the derived classes, however some database
	 * entries do appear as zero, hence the undefined value set
	 * 
	 * @var integer 
	 */
	protected static $subtypeId = self::UNDEFINED;
	
	/**
	 * Returns the facility type ID
	 * 
	 * @return integer
	 */
	public static function getTypeId()
	{
		return static::$typeId;
	}
	
	/**
	 * Returns the facility subtype ID
	 * 
	 * @return integer
	 */	
	public static function getSubtypeId()
	{
		return static::$subtypeId;
	}	
	
}
