<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models;

/**
 * Model for the Facility game object. A facility object represents a "CP" or Firebase
 * or other capturable point in the game
 * 
 * This model maps to the wwiionline.strat_facility table
 *
 * @author Jason "drloon" Rout
 */
class Facility {
	
	/**
	 * Facility Types in the game
	 */
	const TYPE_UNDEFINED	= 0;
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
	
}
