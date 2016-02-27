<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Categories;

use Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Branches\Air;

/**
 * Represents an aircraft category in the game
 *
 * @author Jason
 */
abstract class Aircraft extends Air implements CategoryInterface {
	
	use CategoryTrait;
	
	const CATEGORY_ID = 1;
	const CATEGORY_NAME = 'Aircraft';

}
