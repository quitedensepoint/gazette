<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Categories;

use Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Branches\Army;

/**
 * Represents an infantry category in the game
 *
 * @author Jason
 */
abstract class Infantry extends Army implements CategoryInterface {
	
	use CategoryTrait;
		
	const CATEGORY_ID = 4;
	const CATEGORY_NAME = 'Infantry';

}
