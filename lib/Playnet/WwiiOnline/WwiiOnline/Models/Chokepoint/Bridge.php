<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\WwiiOnline\Models\Chokepoint;

/**
 * Represents a chokepoint that is a bridge
 *
 * @author Jason
 */
class Bridge implements ChokepointInterface {
	
	use ChokepointTrait;
	
	const TYPE_ID = 5;

}
