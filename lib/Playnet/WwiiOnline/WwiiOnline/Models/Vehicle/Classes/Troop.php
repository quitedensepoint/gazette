<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Classes;

use Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Categories\Infantry;

/**
 * Represents a unit of game class Troop
 *
 * @author Jason
 */
abstract class Troop extends Infantry implements ClassInterface {
	
	use ClassTrait;
	
	const CLASS_ID = 9;
	const CLASS_NAME = 'Troop';
}