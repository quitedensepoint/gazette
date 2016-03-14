<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Branches;

/**
 *
 * @author Jason "drloon" Rout
 */
interface BranchInterface {
	
	/**
	 * Retrieve the ID of the branch
	 * 
	 * @return integer Numeric GameID of the database
	 */
	public static function getBranchId();
	
	/**
	 * Retrieve the name of the branch
	 */
	public static function getBranchName();
}
