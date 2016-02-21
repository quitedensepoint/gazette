<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Classes;

use Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Categories\Sea as SeaCategory;

/**
 * Represents a unit of game class Sea
 * 
 * This covers Destroyers and other large ocean only craft
 *
 * @author Jason
 */
abstract class Sea extends SeaCategory implements ClassInterface {
	
	use ClassTrait;
	
	const CLASS_ID = 13;
	const CLASS_NAME = 'Sea';
	
}