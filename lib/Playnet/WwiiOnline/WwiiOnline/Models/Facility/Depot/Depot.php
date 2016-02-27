<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models\Facility\Depot;

use Playnet\WwiiOnline\WwiiOnline\Models\Facility\Facility;

/**
 * Model for the Facility game object. A facility object represents a "CP" or Firebase
 * or other capturable point in the game
 * 
 * This model maps to the wwiionline.strat_facility table
 * 
 * Note that the subtypes in this name space have classes that map to enums in the game code. 
 * The game current only uses GENERIC and TRUCK data types
 * 
 * enum DEPOT_SUBTYPE
 * {
 *   DEPOT_GENERIC=1,
 *   DEPOT_TRAIN,			//UNUSED IN GAME
 *   DEPOT_TRUCK,
 *   DEPOT_BARGE,			//UNUSED IN GAME
 *   DEPOT_CONVOY,			//UNUSED IN GAME
 *   DEPOT_PACK_ANIMAL,		//UNUSED IN GAME
 *   DEPOT_WAGON,			//UNUSED IN GAME
 *   DEPOT_PORTER,			//UNUSED IN GAME
 *   MAX_DEPOT_SUBTYPES
 * } ;
 *
 * @author Jason "drloon" Rout
 */
class Depot extends Facility {
	
	const TYPE_ID = 4;
	
	public function __construct() 
	{
		self::$typeId = self::TYPE_ID;
	}
}
