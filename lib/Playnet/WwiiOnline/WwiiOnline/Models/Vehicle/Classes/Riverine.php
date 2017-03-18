<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Classes;

use Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Categories\Sea as SeaCategory;

/**
 * Represents a unit of game class Sea
 * 
 * This covers FMBs and other smaller craft that can go on rivers
 *
 * @author Jason
 */
abstract class Riverine extends SeaCategory implements ClassInterface {
	
	use ClassTrait;
	
	const CLASS_ID = 12;
	const CLASS_NAME = 'Riverine';
	
}