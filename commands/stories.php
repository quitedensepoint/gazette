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

$options = getopt('', ['list:', 'expire:', 'help:', 'generate:','sourceid:']);

if(count($options) == 0 || isset($options['help']))
{
	exit("\nusage: php command/stories.php [--help] [--list] [--expire=story-key] [--generate] [--sourceid=]"
		. "\n\n  --expire=key\tWill expire the story with the specified key and generate a new story."
		. "\n  --generate\tWill generate new stories for all stories that have expired."
		. "\n  --sourceid\tForces a specific story of the id passed in to be generated.\n"
		);
}

$storyProcessor = new StoryProcessor($dbconn, $dbConnWWIIOnline);

$sourceId = (isset($options['sourceid']) && ctype_digit($options['sourceid'])) ? intval($options['sourceid']) : null;

if(isset($options['expire'])) {
	$storyProcessor->process($options['expire'], $sourceId);
}


exit(0);