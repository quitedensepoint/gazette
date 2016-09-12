<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Types\Destroyer;

use Playnet\WwiiOnline\WwiiOnline\Models\CountryInterface;
use Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Types\TypeInterface;
use Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Types\TypeTrait;
use Playnet\WwiiOnline\WwiiOnline\Traits\French as FrenchTrait;
use Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Classes\Sea;

/**
 * Represents a French Engineer Unit
 *
 * @author Jason "drloon" Rout
 */
class French extends Sea implements CountryInterface, TypeInterface{
	
	/** Ensures this class can access side and country identifiers */
	use FrenchTrait, TypeTrait;
	
	/** The ID of the vehicle in community.scoring_vehicles */
	const OBJECT_ID = 99; // Aigle Destroyer, blitzName = 'fr_z34'
	
	protected static $typeId = 1;

}
