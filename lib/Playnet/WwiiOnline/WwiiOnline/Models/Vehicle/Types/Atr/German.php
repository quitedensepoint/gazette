<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Types\Atr;

use Playnet\WwiiOnline\WwiiOnline\Models\CountryInterface;
use Playnet\WwiiOnline\WwiiOnline\Traits\German as GermanTrait;

/**
 * Represents a German ground based ATR Unit
 *
 * @author Jason "drloon" Rout
 */
class German extends Ground implements CountryInterface {
	
	/** Ensures this class can access side and country identifiers */
	use GermanTrait;
	
	/** The ID of the vehicle in wwii_vehtype */
	const OBJECT_ID = 120;

}
