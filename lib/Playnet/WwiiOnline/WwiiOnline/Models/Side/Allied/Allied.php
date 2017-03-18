<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models\Side\Allied;

use Playnet\WwiiOnline\WwiiOnline\Models\SideInterface;
use Playnet\WwiiOnline\WwiiOnline\Traits\Allied as AlliedTrait;

/**
 * Represents the Allied side
 *
 * @author Jason "drloon" Rout
 */
class Allied implements SideInterface {

	/**
	 * Implements the current side entries
	 */
	use AlliedTrait;
	
}
