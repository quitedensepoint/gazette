<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Classes;

use Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Categories\Ground;

/**
 * Represents a unit of game class Truck
 *
 * @author Jason
 */
abstract class Truck extends Ground implements ClassInterface {
	
	use ClassTrait;
	
	const CLASS_ID = 6;
	const CLASS_NAME = 'Truck';
	
}