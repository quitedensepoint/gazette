<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Types\Atr;

use Playnet\WwiiOnline\WwiiOnline\Models\CountryInterface;
use Playnet\WwiiOnline\WwiiOnline\Traits\British as BritishTrait;

/**
 * Represents a British airborne ATR Unit
 *
 * @author Jason "drloon" Rout
 */
class BritishAirborne extends Airborne implements CountryInterface {
	
	/** Ensures this class can access side and country identifiers */
	use BritishTrait;
	
	/** The ID of the vehicle in community.scoring_vehicles */
	const OBJECT_ID = 166;

}
