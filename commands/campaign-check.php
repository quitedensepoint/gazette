<?php

use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;

/**
 * Automatically checks the community and wwiionline DBS to retrive the status
 * of the campaign and update the gazette accordingly.
 * 
 * This script assumes that the GameDb and Community DB are in sync.
 * 
 */
require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../DBconn.php');
require(__DIR__ . '/../include/dbhelper.php');


/**
 * Allow us to do DB queries in one line instead of five
 */
$gazetteDb = new dbhelper($dbconn);
$communityDb = new dbhelper($dbConnCommunity);
$wwiiOnlineDb = new dbhelper($dbConnWWIIOL);

/**
 * This allows us to log script output to the console or to a file or wherever
 * 
 * Check the options value in DBConn.php for the retention value
 */
$logger = new Logger("gazette");
$logger->pushHandler(new RotatingFileHandler(__DIR__ . '/../logs/campaign-check.log', $options['campaigncheck_log_retention_days'], Logger::INFO));

/**
 * NOTE:: This script assumes that all dates in the database
 * are Rat time (CST, or "America/Chicago")
 * 
 * The server must be set to the correct datetime for this to work
 */
$serverTimezone = new DateTimeZone(date_default_timezone_get());

$gazetteCampaignQuery = $gazetteDb
	->prepare("SELECT `campaign_id` FROM `campaigns` WHERE `status` = ? LIMIT 1", ['Running']);
$gazetteCampaignData = $gazetteDb->getAsArray($gazetteCampaignQuery);

$isGazetteCampaignRunning = false;
$gazetteCampaignID = null;
if(count($gazetteCampaignData) > 0)
{
	$isGazetteCampaignRunning = true;
	$gazetteCampaignID = $gazetteCampaignData[0]['campaign_id'];
	
	$logger->info(sprintf('Gazette reports it is running campaign ID %s', $gazetteCampaignID));
}
else
{
	$logger->info('Gazette reports it is in intermission');
}


// Now check the wwiionline database to see what is running
$wwiiolCampaignQuery = $wwiiOnlineDb
	->prepare("SELECT `value` FROM `wwii_config` WHERE `variableName` = ? LIMIT 1", ['arena.intermission']);
$wwiiolCampaignState = $wwiiOnlineDb->getAsArray($wwiiolCampaignQuery);

$logger->info(sprintf('GameDB reports campaign status as %d', intval($wwiiolCampaignState[0]['value'])));

$isWwiiOnlineCampaignRunning = intval($wwiiolCampaignState[0]['value']) == 0;

// If Game DB and Gazette don't agree
if($isWwiiOnlineCampaignRunning != $isGazetteCampaignRunning)
{
	$logger->debug('Gazette to be updated to current game status');
	
	if($isWwiiOnlineCampaignRunning)
	{

		$logger->debug('Retrieving new campaign information');
		
		// Get the campaign data from the wwiionline db
		$wwiionlineStartQuery = $wwiiOnlineDb->prepare("SELECT `name`,`value` FROM `wwii_arena` WHERE `name` IN ('campaign_id','campaign_start')");
		$wwiionlineStartData = $wwiiOnlineDb->getAsArray($wwiionlineStartQuery);
		
		$newCampaignId = null;
		$newStartTime = null;
		
		/**
		 * Note that there is no error handling here. 
		 */
		foreach($wwiionlineStartData as $data)
		{
			if($data['name'] === 'campaign_id') 
			{
				$newCampaignId = intval($data['value']);
			}
			if($data['name'] === 'campaign_start') 
			{
				$newStartTime = new DateTime();
				$newStartTime->setTimestamp(intval($data['value']));
			}
		}

		$logger->info(sprintf('A new campaign in gazette will be added as campaign ID %d, starting at %s', $newCampaignId, $newStartTime->format('Y-m-d H:i:s')));
		
		/**
		 * Create a new campaign to represent the one just started
		 */
		$campaignCreate = $gazetteDb->prepare("INSERT INTO `campaigns` (`start_time`, `status`, `campaign_id`) VALUES (?,?,?)",
			[$newStartTime->format('Y-m-d H:i:s'), 'Running', $newCampaignId]);
		$campaignCreate->execute();
		$campaignCreate->close();
		
		/** At campaign start, we mark all initial countries in the campaign as active */
		$logger->info('Marking all initially active countries as active for campaign start.');
		$gazetteDb->execute("UPDATE `countries` SET `is_active` = `is_active_initially`, `activated_at` = NOW()");		
		
	}
	else {
		// Stop currently running campaign
		
		/**
		 * Pull the stop details from CSR
		 * 
		 * @todo Eventually this needs to be pulled from the GameDB once a way becomes available
		 */
		$communityCampaignQuery = $communityDb->prepare("SELECT `campaign_id`, `stop_time` FROM `scoring_campaigns` WHERE `campaign_id` = "
			. "(SELECT MAX(`campaign_id`) FROM `scoring_campaigns`) LIMIT 1");
		$communityCampaignData = $communityDb->getAsArray($communityCampaignQuery)[0];
		
		$logger->info(sprintf('Campaign %d in gazette will be marked as Completed, stopping at %s',
			$communityCampaignData['campaign_id'], $communityCampaignData['stop_time']));
		
		// Update the campaign pulled from CSR
		$campaignUpdate = $gazetteDb->prepare("UPDATE `campaigns` SET `stop_time` = ?, `status` = 'Completed' WHERE `campaign_id` = ?",
			[$communityCampaignData['stop_time'], $communityCampaignData['campaign_id']]);
		$campaignUpdate->execute();
		$campaignUpdate->close();
		
		/**
		 * Reset all of the countries to an inactive state
		 */
		$logger->info('Marking all countries as inactive for intermission.');
		$gazetteDb->execute("UPDATE `countries` SET `is_active` = 0, `activated_at` = NULL");
	}
}
else
{
	$logger->info('No changes required for the campaign.');
}

/**
 * Now we need to check if there are any sorties for countries that are not marked as
 * active. If so, we need to mark those countries as active
 */
$activeCountries = $gazetteDb->get("SELECT `country_id` FROM `countries` WHERE `is_active` = 1");
$activeCountryArray = [];
foreach($activeCountries as $activeCountry)
{
	array_push($activeCountryArray, $activeCountry['country_id']);
}

/**
 * Note the query joins a zero on the NOT IN. This is due to bad data occasionally appearing sorties that is not connected
 * to anything.
 */
$newCountries = $wwiiOnlineDb->get("SELECT `vcountry` as `country_id` FROM wwii_sortie WHERE `vcountry` NOT IN (" . 
	join(",", array_values($activeCountryArray)) . ", 0) GROUP BY `vcountry` HAVING COUNT(sortie_id) > 0");

$newCountriesArray = [];
foreach($newCountries as $newCountry)
{
	array_push($newCountriesArray, $newCountry['country_id']);
}

/**
 * Now we set the included countries as active if there are any to be updated
 */
if(count($newCountriesArray) > 0)
{
	$logger->info(sprintf('Countries with IDs %d have entered the war!',join(",", array_values($newCountriesArray))));
	
	$gazetteDb->execute("UPDATE `countries` SET `is_active` = 1, `activated_at` = NOW() WHERE `country_id` IN (" . join(",", array_values($newCountriesArray)) . ")");
}

exit(0);