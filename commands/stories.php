<?php
/**
 * Call this script from the command line in the application root (where
 * DBconn) lives
 *   
 *	php commands/stories.php --expire={story-key}
 * 
 * where:
 *	{story-key} is the unique identifier for the story

 *  .e.g. php commands/stories.php --expire=index_main_headline
 * 
 */

use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;

require(__DIR__ . '/../DBconn.php');
require(__DIR__ . '/../include/dbhelper.php');
require(__DIR__ . '/../processors/story-processor.php');
require(__DIR__ . '/../vendor/autoload.php');

/**
 * Initialise Gazette Logging
 */

$loggingOptions =$options['storygenerator']['log'];
$logger = new Logger("story-generator");
$logger->setTimezone(new DateTimeZone($loggingOptions['timezone']));
$logger->pushHandler(new RotatingFileHandler(__DIR__ . '/../logs/story-generator.log', $loggingOptions['retention_days'], Logger::DEBUG));

if($loggingOptions['console'])
{
	$logger->pushHandler(new StreamHandler('php://stdout', $loggingOptions['level']));
}

$logger->info('Gazette Story Processing is beginning.');

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

$storyOptions = getopt('', ['list:', 'expire:', 'help:', 'generate:', 'sourceid:', 'templateid:', 'typeid:', 'reportonly:', 'force:']);

if(count($storyOptions) == 0 || isset($storyOptions['help']))
{
	exit("\nusage: php command/stories.php [--help] [--expire=story-key] [--generate] [--sourceid=] [--templateid=]"
		. "\n\n  --expire=key\tWill expire the story with the specified key which will be processed on the next call."
		. "\n  --generate=all|expired|story_name\tWill generate a new story for the option providede, or new stories for all stories that have expired if no option given."
		. "\n  --sourceid\tForces a specific story of the id passed in to be generated.\n"
		. "\n  --templateid\tForces a specific template for the source to be used.\n"
		. "\n  --typeid\tForces a specific type to be used.\n"
		. "\n  --reportonly\tForces the text of the story to the command line rather than a file.\n"
		. "\n  --force\tWill generate a story based on most recent qualifying data. Good for testing in development.\n"
		);
}

$logger->info('Processing options provided', ['options' => $storyOptions]);

if(!isset($dbconn)) {
	$logger->emergency('The $dbConn variable is not defined! Cannot start without database connectivity!');
	throw new Exception('Please ensure you have defined a connection "$dbconn" to gazette DB in the DBConn file');
}

if(!isset($dbConnWWII)) {
	$logger->emergency('The $dbConnWWII variable is not defined! Cannot start without database connectivity!');
	throw new Exception('Please ensure you have defined a connection "$dbConnWWII" to wwii DB in the DBConn file');
}

if(!isset($dbConnWWIIOL)) {
	$logger->emergency('The $dbConnWWIIOL variable is not defined! Cannot start without database connectivity!');
	throw new Exception('Please ensure you have defined a connection "$dbConnWWIIOL" to wwiionline DB in the DBConn file');
}

if(!isset($dbConnToe)) {
	$logger->emergency('The $dbConnToe variable is not defined! Cannot start without database connectivity!');
	throw new Exception('Please ensure you have defined a connection "$dbConnToe" to toe DB in the DBConn file');
}

// Create a notification manager to help us determined who should receive the player emails
$notificationManager = new Playnet\WwiiOnline\Common\PlayerMail\NotificationManager($dbconn, $options['playerMail']['handler'], $options['playerMail']['options']);

// We are passing in the database connections as an array rather than separators
// we end up with too manay parameters
$storyProcessor = new StoryProcessor($logger, $notificationManager, [
	'dbConn' => $dbconn, 
	'dbConnCommunity' => $dbConnCommunity, 
	'dbConnWWIIOnline' => $dbConnWWIIOL, 
	'dbConnToe' => $dbConnToe]
	);

$sourceId = (isset($storyOptions['sourceid']) && ctype_digit($storyOptions['sourceid'])) ? intval($storyOptions['sourceid']) : null;
$templateId = (isset($storyOptions['templateid']) && ctype_digit($storyOptions['templateid'])) ? intval($storyOptions['templateid']) : null;
$typeId = (isset($storyOptions['typeid']) && ctype_digit($storyOptions['typeid'])) ? intval($storyOptions['typeid']) : null;
$reportOnly = isset($storyOptions['reportonly']);
$force = isset($storyOptions['force']);

if(isset($storyOptions['generate'])) {
	
	$opts = [
		'sourceId' => $sourceId, 
		'templateId' => $templateId,
		'typeId' => $typeId,
		'reportOnly' => $reportOnly,
		'force' => $force
	];
	
    /**
     * Any provided key that isn't "expired" or "all" will look
     * for a story of that key
     */
    if(!in_array(trim($storyOptions['generate']), ['expired', 'all'])) {
		/**
		 * Generate a new story for the entry provided
		 */
		$storyProcessor->process($storyOptions['generate'], $opts);
	}
	else
	{
				
		/**
         * Load in all the stories and generate new ones
		 */
		$query = 'SELECT `story_key` FROM `stories`';
		   
        /**
         * If we nominated expired, we'll only update the expired stories
         */		
		if($storyOptions['generate'] == 'expired')
		{
			$query .= 'WHERE expire = 1 OR expires <= NOW()';
		}
		
		$storyKeysQuery = $gazetteDbHelper->prepare($query);
		$storyKeysData = $gazetteDbHelper->getAsArray($storyKeysQuery);
		
		foreach ($storyKeysData as $storyKey)
		{		
			$storyProcessor->process($storyKey['story_key'], $opts);
		}
	}
	
}
		
/**
 * Send out the player stories
 */
$logger->info('Preparing to send Player Emails');
$notificationManager->getHandler()->send();
$logger->info('Player Emails sending complete');

/**
 * If we have asked for a specific area to be expired, it will regenerate on
 * the next run
 */
if(isset($storyOptions['expire'])) {
	$storyProcessor->forceStoryExpiry($storyOptions['expire']);
}

exit(0);