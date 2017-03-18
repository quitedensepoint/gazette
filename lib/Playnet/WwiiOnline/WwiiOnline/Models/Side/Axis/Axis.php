<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models\Side\Axis;

use Playnet\WwiiOnline\WwiiOnline\Models\SideInterface;
use Playnet\WwiiOnline\WwiiOnline\Traits\Axis as AxisTrait;

/**
 * Represents the Axis side
 *
 * @author Jason "drloon" Rout
 */
class Axis implements SideInterface {

	/**
	 * Implements the current side entries
	 */
	use AxisTrait;
	
}
