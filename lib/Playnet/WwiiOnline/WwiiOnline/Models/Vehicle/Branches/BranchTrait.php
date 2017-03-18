<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Branches;

/**
 * This trait is something added to all the Vehicle branches to provide a standard
 * implementation of the interfaces
 * 
 * @author Jason "drloon" Rout
 */
trait BranchTrait {
	
	public static function getBranchId() {
		return static::BRANCH_ID;
	}
	
	public static function getBranchName() {
		return static::BRANCH_NAME;
	}
}