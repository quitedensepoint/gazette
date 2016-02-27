<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Types\Atr;

use Playnet\WwiiOnline\WwiiOnline\Models\CountryInterface;
use Playnet\WwiiOnline\WwiiOnline\Traits\French as FrenchTrait;

/**
 * Represents a French airborne ATR Unit
 *
 * @author Jason "drloon" Rout
 */
class FrenchAirborne extends Airborne implements CountryInterface {
	
	/** Ensures this class can access side and country identifiers */
	use FrenchTrait;
	
	/** The ID of the vehicle in wwii_vehtype */
	const OBJECT_ID = 183;

}
