<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Types\Engineer;

use Playnet\WwiiOnline\WwiiOnline\Models\CountryInterface;
use Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Types\TypeInterface;
use Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Types\TypeTrait;
use Playnet\WwiiOnline\WwiiOnline\Traits\German as GermanTrait;
use Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Classes\Troop;

/**
 * Represents a German Engineer Unit
 *
 * @author Jason "drloon" Rout
 */
class German extends Troop implements CountryInterface, TypeInterface{
	
	/** Ensures this class can access side and country identifiers */
	use GermanTrait, TypeTrait;
	
	/** The ID of the vehicle in community.scoring_vehicles */
	const OBJECT_ID = 33; // Engineer,blitzName = 'de-eng'
	
	protected static $typeId = 3;

}
