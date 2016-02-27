<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models\Facility\Factory;

use Playnet\WwiiOnline\WwiiOnline\Models\Facility\Facility;

/**
 * Model for the Facility game object. A facility object represents a "CP" or Firebase
 * or other capturable point in the game
 * 
 * This model maps to the wwiionline.strat_facility table
 * 
 * Note that the subtypes in this name space have classes that map to enums in the game code. 
 * All Factory substypes are used in the game
 * 
 * enum FACTORY_SUBTYPE
 * {
 *     FACTORY_GENERIC = 1,
 *     FACTORY_MUNITIONS,
 *     FACTORY_AIRCRAFT,
 *     FACTORY_OIL_REFINERY,
 *     FACTORY_CHEMICAL_PLANT,
 *     FACTORY_MACHINERY,
 *     FACTORY_VEHICLE,
 * } ;
 *
 * @author Jason "drloon" Rout
 */
class Factory extends Facility {
	
	/**
	 * Overrides the static value in the base class
	 * 
	 * @var integer
	 */
	protected static $typeId = 2;

}
