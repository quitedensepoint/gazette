<?php

namespace Playnet\WwiiOnline\Gazette\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * A model representing the total number of casualties over a time period
 * 
 * This is an example of a Model, which represents a database table, and the business logic
 * of that table. You'll notice there is no indication of how the model knows what database
 * table to look at. In the Laravel framework, you'll find it work by convention.
 * 
 * The Framework looks at the Model name (CampaignCasuality) and turns it into a snake case
 * string (campaign_casuality). It then uses that name as the related database table.
 * 
 * You can override this in the model if you need to connect to a differnet database table.
 * 
 * @property-read	integer			$id				ID of the record
 * @property		Carbon\Carbon	$created_at		Date and time of the record creation
 * @property		Carbon\Carbon	$updated_at		Date and time of the record last modification
 * @property		Carbon\Carbon	$deleted_at		Date and time the record was set as deleted
 * @property		integer			$campaign_id	The ID of the campaign this statistic relates to
 * @property		integer			$side_id		The ID of the side that the casualties belong to
 * @property		integer			$branch_id		The ID of the branch that the casualties belong to
 * @property		integer			$kill_count		The number of casualties
 * @property		Carbon\Carbon	$period_start	The starting DateTime of the range of kills
 * @property		Carbon\Carbon	$period_end		The ending DateTime of the range of kills
 */
class CampaignCasuality extends Model
{
	
}
