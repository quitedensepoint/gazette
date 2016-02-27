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
	
	protected static $typeId = self::UNDEFINED;
	
	protected static $subtypeId = self::UNDEFINED;
	
	const TYPE_CITY			= 1;
	const TYPE_FACTORY		= 2;
	
	/**
	 * Flak batteries according to the, at the time of writing, strat_facility table
	 * 
	 * @todo Determine usages and maybe rename from "base"
	 */
	const TYPE_BASE			= 3;
	const TYPE_DEPOT		= 4;
	
	/**
	 * Type OMT is currently unknown. At the time of writing, there were no
	 * facilities using it.
	 * 
	 * @todo Determine an accurate description
	 */
	const TYPE_OMT			= 5;
	const TYPE_SHIPYARD		= 6;
	
	/**
	 * Represents the forward bases where players spawn for attacks on town
	 */
	const TYPE_FIREBASE		= 7;
	const TYPE_AIRBASE		= 8;
	const TYPE_ARMYBASE		= 9;
	const TYPE_NAVALBASE	= 10;
	
	/**
	 * Returns the facility type ID
	 * 
	 * @return integer
	 */
	protected static function getTypeId()
	{
		return self::$typeId;
	}
	
	/**
	 * Returns the facility subtype ID
	 * 
	 * @return integer
	 */	
	protected static function getSubtypeId()
	{
		return self::$subtypeId;
	}	
	
}
