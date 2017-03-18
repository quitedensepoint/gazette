<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Categories;

use Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Branches\Navy;

/**
 * Represents an a sea vehicle belonging to the navy
 *
 * @author Jason
 */
abstract class Sea extends Navy implements CategoryInterface {
		
	use CategoryTrait;
	
	const CATEGORY_ID = 3;
	const CATEGORY_NAME = 'Sea Vehicle';
}
