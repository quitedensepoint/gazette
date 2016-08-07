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

require(__DIR__ . '/../DBconn.php');
require(__DIR__ . '/../include/dbhelper.php');
require(__DIR__ . '/../processors/story-processor.php');
require(__DIR__ . '/../vendor/autoload.php');


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

$storyOptions = getopt('', ['list:', 'expire:', 'help:', 'generate:', 'sourceid:', 'templateid:', 'reportonly:']);

if(count($storyOptions) == 0 || isset($storyOptions['help']))
{
	exit("\nusage: php command/stories.php [--help] [--expire=story-key] [--generate] [--sourceid=] [--templateid=]"
		. "\n\n  --expire=key\tWill expire the story with the specified key which will be processed on the next call."
		. "\n  --generate=all|expired|story_name\tWill generate a new story for the option providede, or new stories for all stories that have expired if no option given."
		. "\n  --sourceid\tForces a specific story of the id passed in to be generated.\n"
		. "\n  --templateid\tForces a specific template for the source to be used.\n"
		. "\n  --reportonly\tForces the text of the story to the command line rather than a file.\n"
		);
}

if(!isset($dbconn)) {
	throw new Exception('Please ensure you have defined a connection "$dbconn" to gazette DB in the DBConn file');
}

if(!isset($dbConnWWII)) {
	throw new Exception('Please ensure you have defined a connection "$dbConnWWII" to wwii DB in the DBConn file');
}

if(!isset($dbConnWWIIOL)) {
	throw new Exception('Please ensure you have defined a connection "$dbConnWWIIOL" to wwiionline DB in the DBConn file');
}

if(!isset($dbConnToe)) {
	throw new Exception('Please ensure you have defined a connection "$dbConnToe" to toe DB in the DBConn file');
}

// Create a notification manager to help us determined who should receive the player emails
$notificationManager = new Playnet\WwiiOnline\Common\PlayerMail\NotificationManager($dbconn, $options['playerMail']['handler'], $options['playerMail']['options']);

// We are passing in the database connections as an array rather than separators
// we end up with too manay parameters
$storyProcessor = new StoryProcessor($notificationManager, [
	'dbConn' => $dbconn, 
	'dbConnWWII' => $dbConnWWII, 
	'dbConnWWIIOnline' => $dbConnWWIIOL, 
	'dbConnToe' => $dbConnToe]
	);

$sourceId = (isset($storyOptions['sourceid']) && ctype_digit($storyOptions['sourceid'])) ? intval($storyOptions['sourceid']) : null;
$templateId = (isset($storyOptions['templateid']) && ctype_digit($storyOptions['templateid'])) ? intval($storyOptions['templateid']) : null;
$reportOnly = isset($storyOptions['reportonly']);

if(isset($storyOptions['generate'])) {
	
	$opts = [
		'sourceId' => $sourceId, 
		'templateId' => $templateId,
		'reportOnly' => $reportOnly
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
$notificationManager->getHandler()->send();

/**
 * If we have asked for a specific area to be expired, it will regenerate on
 * the next run
 */
if(isset($storyOptions['expire'])) {
	$storyProcessor->forceStoryExpiry($storyOptions['expire']);
}

exit(0);