<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Types\Destroyer;

use Playnet\WwiiOnline\WwiiOnline\Models\CountryInterface;
use Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Types\TypeInterface;
use Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Types\TypeTrait;
use Playnet\WwiiOnline\WwiiOnline\Traits\British as BritishTrait;
use Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Classes\Sea;

/**
 * Represents a British Engineer Unit
 *
 * @author Jason "drloon" Rout
 */
class British extends Sea implements CountryInterface, TypeInterface{
	
	/** Ensures this class can access side and country identifiers */
	use BritishTrait, TypeTrait;
	
	/** The ID of the vehicle in wwii_vehtype */
	const OBJECT_ID = 98;
	
	protected static $typeId = 1;

}
