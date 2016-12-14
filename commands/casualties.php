<?php
/**
 * Call this script from the command line in the application root (where
 * DBconn) lives
 *   
 *	php commands/casualties.php --report-only
 * 
 * where:
 *	{report-only} will force the script to output the data to the commandline,
 *		rather than saving it to the database
 */

require(__DIR__ . '/../DBconn.php');
require(__DIR__ . '/../include/dbhelper.php');

/**
 * Allow us to do DB queries in one line instead of five
 */
$gazetteDbHelper = new dbhelper($dbconn);
$wwiiDbHelper = new dbhelper($dbConnWWII);

/**
 * NOTE:: This script assumes that all dates in the database
 * are Rat time (CST, or "America/Chicago")
 * 
 * The server must be set to the correct datetime for this to work
 */
$serverTimezone = new DateTimeZone(date_default_timezone_get());

$options = getopt('', ['report-only']);

$reportOnly = isset($options['report-only']);

/**
 * Create a new date referring to the current hour
 */
$date = new DateTimeImmutable('now', $serverTimezone);

/**
 * Create another date that sets the time as the current hour
 */
$hourStart = $date->setTime($date->format('G'),0,0);

/**
 * Get the current campaign so that the casualty numbers can be recorded against it
 */
$campaignQuery = $gazetteDbHelper
	->prepare("SELECT `id`, `start_time` FROM `campaigns` WHERE `status` = 'Running' LIMIT 1");
$campaignData = $gazetteDbHelper->getAsArray($campaignQuery);

if(count($campaignData) == 0) {
	exit('There are no currently running campaigns.');
}

$campaignId = $campaignData[0]['id'];
$campaignStart = new DateTime($campaignData[0]['start_time'], $serverTimezone);

$killsQuery = $wwiiDbHelper
	->prepare("SELECT `victim_country` as `country_id`, `victim_category` as `branch_id`, count(`kill_id`) as `kill_count`"
		. " FROM `kills` WHERE `kill_time` < ? GROUP BY `country_id`,`branch_id`",
		[$hourStart->format('Y-m-d H:i:s')]);
$killsData = $wwiiDbHelper->getAsArray($killsQuery);		

/**
 * If we have asked for a report only, push it out to the commandline
 */
if($reportOnly)
{
	exit(json_encode($killsData));
}

/**
 * Go through the kills and push them out to the database
 */

array_walk($killsData, function(&$kill) use($hourStart, $date, $campaignId, $campaignStart) {

	$kill['campaign_id'] = intval($campaignId);
	$kill['period_start'] = $campaignStart->format('Y-m-d H:i:s'); 
	$kill['period_end'] = $hourStart->format('Y-m-d H:i:s'); // format as mysql compatible date
	$kill['created_at'] = $date->format('Y-m-d H:i:s');
	$kill['updated_at'] = $date->format('Y-m-d H:i:s');
});

$existingQuery = $gazetteDbHelper->getStatement("SELECT count(`id`) AS `record_count` FROM `campaign_casualties`"
	. " WHERE `period_start` = ? AND `period_end` = ? AND `campaign_id` = ? AND `branch_id` = ? AND `country_id` = ?");


$killsCreate = $gazetteDbHelper->getStatement(
	"INSERT INTO `campaign_casualties` (`created_at`, `updated_at`, `campaign_id`, `branch_id`, `kill_count`, `period_start`,`period_end`, `country_id`)" .
	" VALUES (?,?,?,?,?,?,?,?)");

foreach($killsData as $killData)
{
	$existingQuery->bind_param("ssiii", $killData['period_start'], $killData['period_end'],
		$killData['campaign_id'], $killData['branch_id'],$killData['country_id']);
	
	$existingCount = null;
	$existingQuery->bind_result($existingCount);
	$existingQuery->execute();
	$existingQuery->store_result();
	$existingQuery->fetch();
	
	if($existingCount == 0)
	{
		echo "existing count > 0";
		$killsCreate->bind_param("ssiiissi", $killData['created_at'], $killData['updated_at'], $killData['campaign_id'], $killData['branch_id'],
		$killData['kill_count'], $killData['period_start'], $killData['period_end'],$killData['country_id']);
	
		if($killsCreate->execute() == false) {
			echo $killsCreate->error;
		}
	}
}
$killsCreate->close();
$existingQuery->close();

exit(0);