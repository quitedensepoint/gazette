<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Branches;

use Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Vehicle;

/**
 * A class from which to inherit vehicles in the army
 *
 * @author Jason
 */
abstract class Navy extends Vehicle implements BranchInterface {
	
	use BranchTrait;
	
	const BRANCH_ID = 3;
	const BRANCH_NAME = 'Navy';

}
