<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Types\Sapper;

use Playnet\WwiiOnline\WwiiOnline\Models\CountryInterface;
use Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Types\TypeInterface;
use Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Types\TypeTrait;
use Playnet\WwiiOnline\WwiiOnline\Traits\Us as UsTrait;
use Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Classes\Troop;

/**
 * Represents a US Engineer Unit
 *
 * @author Jason "drloon" Rout
 */
class Us extends Troop implements CountryInterface, TypeInterface{
	
	/** Ensures this class can access side and country identifiers */
	use UsTrait, TypeTrait;
	
	/** The ID of the vehicle in wwii_vehtype */
	const OBJECT_ID = 18305;
	
	protected static $typeId = 16;

}
