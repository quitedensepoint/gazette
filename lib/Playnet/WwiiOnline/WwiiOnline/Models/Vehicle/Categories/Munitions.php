<?php

/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Categories;

use Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Branches\Army;

/**
 * Represents an aircraft category in the game
 *
 * @author Jason
 */
abstract class Munitions extends Army implements CategoryInterface {
		
	use CategoryTrait;
	
	const CATEGORY_ID = 5;
	const CATEGORY_NAME = 'Munitions';

}
