<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Classes;

use Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Categories\Infantry;

/**
 * Represents a unit of game class Para
 *
 * @author Jason
 */
abstract class Para extends Infantry implements ClassInterface {
	
	use ClassTrait;
	
	const CLASS_ID = 14;
	const CLASS_NAME = 'Para';
	


}