<?php
/**
 * Call this script from the command line in the application root (where
 * DBconn) lives
 *   
 *	php commands/campaign-start.php --campaign_id={id} --start={date}
 * 
 * where:
 *	{id} is the number of the campaign to start
 *	{start} is the start date and time (CST) in the format "yyyy-mm-dd HH:ii:ss"
 * 
 * You'll need to include the quotes on the date
 *  .e.g. php commands/campaign-start.php --campaign_id=120 --start="2015-12-12 15:30:35"
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

$options = getopt('', ['campaignid:', 'start:']);

if(!isset($options['campaignid'])) {
	
	exit("Please enter a campaign id.");
}

$campaignId = $options['campaignid'];
if(!ctype_digit($campaignId)) {
	
	exit("camapaignid must be a number.");
}
$campaignId =intval($campaignId);// convert so that the SQL bindings can handle it

if(!isset($options['start'])) {
	
	exit("Please enter a start date in yyyy-mm-dd HH:ii:ss");
}

$start = DateTime::createFromFormat("Y-m-d H:i:s", $options['start'], $serverTimezone);
if($start === false) {
	
	exit(sprintf("The start date %s is invalid", $options['start']));
}

/**
 * Check to see if the campaign already exists
 */
$campaignQuery = $gazetteDbHelper
	->prepare("SELECT id FROM `campaigns` WHERE `campaign_id` = ? LIMIT 1", [$campaignId]);
$campaignData = $gazetteDbHelper->getAsArray($campaignQuery);

if(count($campaignData) > 0) {
	
	exit(sprintf('Campaign %s already exists in the database!', $campaignId));
}

/**
 * Check to see if there is already a running campaign
 */
$campaignQuery = $gazetteDbHelper
	->prepare("SELECT id FROM `campaigns` WHERE `status` = 'Running' LIMIT 1");
$campaignData = $gazetteDbHelper->getAsArray($campaignQuery);

if(count($campaignData) > 0) {
	
	exit('There is already a running campaign! Use commands/campaign-stop.php to end the running campaign.');		
}

/**
 * Get the last stopped campaign and check that our new start is after that stop
 */
$campaignQuery = $gazetteDbHelper
	->prepare("SELECT stop_time FROM `campaigns` WHERE `stop_time` IS NOT NULL ORDER BY `stop_time` DESC LIMIT 1");
$campaignData = $gazetteDbHelper->getAsArray($campaignQuery);

$stop = new DateTime($campaignData[0]['stop_time'], $serverTimezone);

if($start < $stop)
{
	exit(sprintf('Start date must be greater than the stop time of the last campaign: %s', $stop->format("Y-m-d H:i:s")));			
}

/**
 * create the new campaign
 */
$campaignCreate = $gazetteDbHelper->prepare("INSERT INTO `campaigns` (`start_time`, `status`, `campaign_id`) VALUES (?,?,?)",
	[$start->format('Y-m-d H:i:s'), 'Running', $campaignId]);
$campaignCreate->execute();
$campaignCreate->close();
exit(0);