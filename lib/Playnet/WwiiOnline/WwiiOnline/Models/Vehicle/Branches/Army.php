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
abstract class Army extends Vehicle implements BranchInterface {
	
	use BranchTrait;
	
	const BRANCH_ID = 1;
	const BRANCH_NAME = 'Army';

}
