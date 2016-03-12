<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models\Chokepoint;

use Playnet\WwiiOnline\WwiiOnline\Models\CountryInterface;

/**
 * Represents a chokepoint that is a bridge
 *
 * @author Jason
 */
class Bridge implements ChokepointInterface {
	
	use ChokepointTrait;
	
	const TYPE_ID = 5;

}
