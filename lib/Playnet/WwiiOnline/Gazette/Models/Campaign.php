<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\Gazette\Models;

/**
 * Model for the Campaign game object. A campaign represents a single "war" in
 * the wwiionline universe
 * 
 * This model maps to the gazette.campaigns table
 *
 * @author Jason "drloon" Rout
 */
class Campaign {
	
	/**
	 * Status of the campaign
	 * 
	 * Only one campaign can be running at any one time
	 */
	const STATUS_RUNNING	= 'Running';
	const STATUS_COMPLETED	= 'Completed';
		
}
