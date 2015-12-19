<?php
/**
 * Call this script from the command line in the application root (where
 * DBconn) lives
 *   
 *	php commands/campaign-stop.php --stop={date}
 * 
 * where:
 *	{stop} is the start date and time (CST) in the format "yyyy-mm-dd HH:ii:ss"
 * 
 * You'll need to include the quotes on the date
 *  .e.g. php commands/campaign-stop.php --start="2015-12-12 15:30:35"
 * 
 */

require(__DIR__ . '/../DBconn.php');
require(__DIR__ . '/../include/dbhelper.php');

/**
 * Allow us to do DB queries in one line instead of five
 */
$gazetteDbHelper = new dbhelper($dbconn);

/**
 * NOTE:: This script assumes that all dates in the database
 * are Rat time (CST, or "America/Chicago")
 * 
 * The server must be set to the correct datetime for this to work
 */
$serverTimezone = new DateTimeZone(date_default_timezone_get());

$options = getopt('', ['stop:']);

if(!isset($options['stop'])) {
	
	exit("Please enter a stop date in yyyy-mm-dd HH:ii:ss");
}

$stop = DateTime::createFromFormat("Y-m-d H:i:s", $options['stop'], $serverTimezone);
if($stop === false) {
	
	exit(sprintf("The stop date %s is invalid", $options['stop']));
}

/**
 * Check to see if the campaign already exists
 */
$campaignQuery = $gazetteDbHelper
	->prepare("SELECT id, start_time FROM `campaigns` WHERE `status` = 'Running'");
$campaignData = $gazetteDbHelper->getAsArray($campaignQuery);

if(count($campaignData) > 1) {
	
	exit(sprintf('DATA CONSISTENCY ERROR: There appears to be %s campaigns running at the moment!', count($campaignData)));
}

if(count($campaignData) == 0) {
	
	exit('There are no campaigns running at the moment.');
}

$campaignId = $campaignData[0]['id'];
$start = DateTime::createFromFormat('Y-m-d H:i:s',  $campaignData[0]['start_time'], $serverTimezone);

if($stop < $start) {
	
	exit('The stop time is before the start time of the campaign.');
}

/**
 * Update the currently running campaign and set it to stopped
 */
$campaignUpdate = $gazetteDbHelper->prepare("UPDATE `campaigns` SET `stop_time` = ?, `status` = 'Complete' WHERE `id` = ?",
	[$stop->format('Y-m-d H:i:s'), $campaignId]);
$campaignUpdate->execute();
$campaignUpdate->close();
exit(0);