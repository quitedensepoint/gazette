<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Categories;

use Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Branches\Army;

/**
 * Represents an ground category vehcile in the game
 *
 * @author Jason
 */
abstract class Ground extends Army implements CategoryInterface {
	
	use CategoryTrait;
	
	const CATEGORY_ID = 2;
	const CATEGORY_NAME = 'Ground Vehicle';

}
