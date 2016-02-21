<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Categories;

/**
 *
 * @author Jason "drloon" Rout
 */
interface CategoryInterface {
	
	/**
	 * Retrieve the ID of the category
	 * 
	 * @return integer Numeric GameID of the database
	 */
	public function getCategoryId();
	
	/**
	 * Retrieve the name of the category
	 */
	public function getCategoryName();
}
