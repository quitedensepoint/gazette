<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Types\Destroyer;

use Playnet\WwiiOnline\WwiiOnline\Models\CountryInterface;
use Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Types\TypeInterface;
use Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Types\TypeTrait;
use Playnet\WwiiOnline\WwiiOnline\Traits\German as GermanTrait;
use Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Classes\Sea;

/**
 * Represents a German Destroyer Unit
 *
 * @author Jason "drloon" Rout
 */
class German extends Sea implements CountryInterface, TypeInterface{
	
	/** Ensures this class can access side and country identifiers */
	use GermanTrait, TypeTrait;
	
	/** The ID of the vehicle in community.scoring_vehicles */
	const OBJECT_ID = 80; //Zerstorer Type 1934, blitzName = 'z34'
	
	protected static $typeId = 1;

}
