<?php

use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;

/**
 * Automatically checks the community and wwiionline DBs to retrive the status
 * of the campaign and update the gazette accordingly.
 * 
 * This script assumes that the Game DB and Community DB are in sync.
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
 * Check the options value in DBconn.php for the retention value
 */
$logger = new Logger("gazette");
$logger->pushHandler(new RotatingFileHandler(__DIR__ . '/../logs/campaign-check.log', $options['campaigncheck_log_retention_days'], Logger::INFO));

/**
 * NOTE: This script assumes that all dates in the database
 * are Rat time (CST, or "America/Chicago")
 * 
 * The server must be set to the correct datetime for this to work
 */
$serverTimezone = new DateTimeZone(date_default_timezone_get());

$gazetteCampaignData = $gazetteDb
	->get("SELECT `campaign_id` FROM `campaigns` WHERE `status` = 'Running' LIMIT 1");

$isGazetteCampaignRunning = false;
$gazetteCampaignID = null;
if(count($gazetteCampaignData) > 0)
{
	$isGazetteCampaignRunning = true;
	$gazetteCampaignID = $gazetteCampaignData[0]['campaign_id'];
	
	$logger->info(sprintf('The Gazette reports it is running campaign ID %s', $gazetteCampaignID));
}
else
{
	$logger->info('The Gazette reports it is in Intermission');
}

// Now check the wwiionline database to see what is running
$wwiiolCampaignState = $wwiiOnlineDb
	->get("SELECT `value` FROM `wwii_config` WHERE `variableName` = 'arena.intermission' LIMIT 1");
	
if(intval($wwiiolCampaignState[0]['value']) == 0){
	$wwiiolCampaignStatus = 'Running (0)';
}elseif(intval($wwiiolCampaignState[0]['value']) == 1){
	$wwiiolCampaignStatus = 'Intermission Setup (1)';
}else{
	$wwiiolCampaignStatus = 'Running Intermission (2)';
}

$logger->info(sprintf('The Game DB reports campaign status as %s', $wwiiolCampaignStatus));

$isWwiiOnlineCampaignRunning = intval($wwiiolCampaignState[0]['value']) == 0;

// If the Game DB and the Gazette don't agree
if($isWwiiOnlineCampaignRunning != $isGazetteCampaignRunning)
{
	$logger->debug('The Gazette will be updated to current game status');
	
	if($isWwiiOnlineCampaignRunning)
	{
		$logger->debug('Retrieving new campaign information');
		
		// Get the campaign data from the wwiionline db
		$wwiionlineStartData = $wwiiOnlineDb->get("SELECT `name`, `value`, `modified` FROM `wwii_arena` WHERE `name` IN ('campaign_id', 'campaign_start')");
		
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
				$newStartTime = $data['modified'];
			}
		}

		$logger->info(sprintf('A new campaign in Gazette will be added as campaign ID %d, starting at %s', $newCampaignId, $newStartTime));
		
		/**
		 * Create a new campaign to represent the one that just started
		 */
		$gazetteDb->execute("INSERT INTO `campaigns` (`start_time`, `status`, `campaign_id`) VALUES (?,?,?)",
			[$newStartTime, 'Running', $newCampaignId]);
		
		/**
		 * At campaign start, we mark all initial countries in the campaign as active
		 */
		$logger->info('Marking all initially active countries as active for campaign start');
		$gazetteDb->execute("UPDATE `countries` SET `is_active` = `is_active_initially`, `activated_at` = NOW() WHERE `is_active_initially` = 1");			

		/**
		 * Update the campaign end date/time with the updated one since the actual end date/time isn't created until the new campaing begins
		 */	
		$communityCampaignData = $communityDb->first("SELECT `campaign_id`, `stop_time` FROM `scoring_campaigns` WHERE `campaign_id` = "
			. "(SELECT MAX(`campaign_id`)-1 FROM `scoring_campaigns`) LIMIT 1");	
		
		$logger->info(sprintf('Campaign %d in the Gazette will be updated with an end date/time of %s',
			$communityCampaignData['campaign_id'], $communityCampaignData['stop_time']));
			
		// Update the campaign pulled from CSR
		$gazetteDb->execute("UPDATE `campaigns` SET `stop_time` = ? WHERE `campaign_id` = ?",
			[$communityCampaignData['stop_time'], $communityCampaignData['campaign_id']]);
		
	}
	else 
	{
		// Stop currently running campaign
		
		/**
		 * Pull the stop details from CSR
		 * 
		 * @todo Eventually this needs to be pulled from the Game DB once a way becomes available
		 */
		$communityCampaignData = $communityDb->first("SELECT `campaign_id`, `stop_time` FROM `scoring_campaigns` WHERE `campaign_id` = "
			. "(SELECT MAX(`campaign_id`) FROM `scoring_campaigns`) LIMIT 1");
		
		$logger->info(sprintf('Campaign %d in the Gazette will be marked as Completed, stopping at %s',
			$communityCampaignData['campaign_id'], (new DateTime())->format('Y-m-d H:i:s')));
		
		// Update the campaign pulled from CSR and the current date/time
		$gazetteDb->execute("UPDATE `campaigns` SET `stop_time` = ?, `status` = 'Completed' WHERE `campaign_id` = ?",
			[(new DateTime())->format('Y-m-d H:i:s'), $communityCampaignData['campaign_id']]);
		
		/**
		 * Reset all of the countries to an inactive state
		 */
		$logger->info('Marking all countries as inactive for Intermission');
		$gazetteDb->execute("UPDATE `countries` SET `is_active` = 0, `activated_at` = NULL");
	}
}
else
{
	$logger->info('No changes required for the campaign');
}

if($isWwiiOnlineCampaignRunning) 
{
	$logger->info('Checking to see if there are any new countries in the war');
	
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
	 * If we get to this state, there is a problem so we log the error. Then we set
	 * up the entries correctly for the next run and then finish
	 */
	if(count($activeCountryArray) == 0)
	{
		$logger->error("No active countries in campaign, setting defaults");
		$gazetteDb->execute("UPDATE `countries` SET `is_active` = `is_active_initially`, `activated_at` = NOW() WHERE `is_active_initially` = 1");
		exit(0);
	}

	/**
	 * Note the query joins a zero on the NOT IN. This is due to bad data occasionally appearing sorties that is not connected
	 * to anything.
	 */
	$newCountries = $wwiiOnlineDb->get("SELECT `vcountry` as `country_id` FROM `wwii_sortie` WHERE `vcountry` NOT IN (" . 
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
		$logger->info(sprintf('Countries with IDs %d have entered the war!', join(",", array_values($newCountriesArray))));
		$gazetteDb->execute("UPDATE `countries` SET `is_active` = 1, `activated_at` = NOW() WHERE `country_id` IN (" . join(",", array_values($newCountriesArray)) . ")");
	}
}

exit(0);