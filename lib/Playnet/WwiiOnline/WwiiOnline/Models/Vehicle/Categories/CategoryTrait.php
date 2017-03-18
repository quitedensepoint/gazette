<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Categories;

/**
 * This trait is something added to all the Vehicle categories to provide a standard
 * implementation of the interfaces
 * 
 * @author Jason "drloon" Rout
 */
trait CategoryTrait {
	
	public static function getCategoryId() {
		return self::CATEGORY_ID;
	}

	public static function getCategoryName() {
		return self::CATEGORY_NAME;
	}
}