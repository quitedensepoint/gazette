<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Types\Atr;

use Playnet\WwiiOnline\WwiiOnline\Models\CountryInterface;
use Playnet\WwiiOnline\WwiiOnline\Traits\French as FrenchTrait;

/**
 * Represents a French ground based ATR Unit
 *
 * @author Jason "drloon" Rout
 */
class French extends Ground implements CountryInterface {
	
	/** Ensures this class can access side and country identifiers */
	use FrenchTrait;
	
	/** The ID of the vehicle in community.scoring_vehicles */
	const OBJECT_ID = 121;

}
