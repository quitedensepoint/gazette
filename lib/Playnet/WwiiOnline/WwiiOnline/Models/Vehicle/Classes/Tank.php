<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Classes;

use Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Categories\Ground;

/**
 * Represents a unit of game class Tank
 *
 * @author Jason
 */
abstract class Tank extends Ground implements ClassInterface {
	
	use ClassTrait;
	
	const CLASS_ID = 4;
	const CLASS_NAME = 'Tank';

}