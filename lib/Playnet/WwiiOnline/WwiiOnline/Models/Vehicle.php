<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models;

/**
 * Model for the Vehicle game object. A vehicle object represents the in-game avatar
 * that a player controls.
 * 
 * This model maps to the wwiionline.wwii_vehicle table
 *
 * @author Jason "drloon" Rout
 */
class Vehicle {
	
	/**
	 * Vehicle categorisation types.
	 * 
	 * In the wwii_vehicles table, corresponds to the veh_cat field. 
	 */
	const CATEGORY_AIRCRAFT		= 1;
	const CATEGORY_GROUND		= 2;
	const CATEGORY_SEA			= 3;
	const CATEGORY_INFANTRY		= 4;
	const CATEGORY_MUNITIONS	= 5;
	
	
	/**
	 * Vehicle classification types.
	 * 
	 * In the wwii_vehicles table, corresponds to the veh_class field. 
	 */
	const CLASS_BOMBER			= 1;
	const CLASS_FIGHTER			= 2;
	
	/**
	 * Game manager? a flying buzzard.
	 */
	const CLASS_GM				= 3; 
	const CLASS_TANK			= 4;
	const CLASS_ARTILLERY		= 5;
	const CLASS_TRUCK			= 6;
	const CLASS_GUN				= 7;
	const CLASS_CREW			= 8;
	const CLASS_TROOP			= 9;
	const CLASS_AIRBOMB			= 10;
	const CLASS_HEAVYINFANTRY	= 11;
	const CLASS_RIVERINE		= 12;
	const CLASS_SEA				= 13;
	const CLASS_PARA			= 14;
	
}
