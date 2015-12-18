<?php

namespace Playnet\WwiiOnline\Gazette\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer		$campaign_id	The actual ID of the game campaign
 * @property string			$status			"Running" or "Completed" - server var is an enum
 * @property Carbon\Carbon	$start_time		Start of the campaign
 * @property Carbon\Carbon	$stop_time		End of the campaign
 */
class Campaign extends Model
{

	/**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
	
	/**
	 * Automatically convert MySQL dates into Carbon DateTime objects
	 * 
	 * @var array
	 */
	protected $dates = ['start_time', 'stop_time'];
	
	/**
	 * These constants correspond to the enum values in the "campaigns" table
	 */
	const CAMPAIGN_STATUS_RUNNING = 'Running';
	const CAMPAIGN_STATUS_COMPLETED = 'Completed';
}
