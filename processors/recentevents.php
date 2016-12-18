<?php
/*
* Used for getting in game recent events that are significant enough to be shown on the gazette front page
*
* @author Sniper62
*/
	
// Select the campaign that is currently running
// $currCampQuery = $dbconn->query("SELECT `campaign_id`, `start_time` FROM `campaigns` WHERE `status` = 'Running'");
// $currCampData = $currCampQuery->fetch_assoc();
// $currCampNum = $currCampData['campaign_id'];


class RecentEventsProcessor{

	protected $dbConn;
	
	public function __construct($dbConn) {
		
		$this->dbConn = $dbConn;
	}
	
	/**
	 * Processes the recent captures database entries for display on the screen
	 * 
	 * @return array
	 */
	public function process(){
		$dbHelper = new dbhelper($this->dbConn);
		
		$recentEventsQuery = $dbHelper->prepare("
																	SELECT
																	  `wp`.`callsign` AS `callsign`,
																	  FROM_UNIXTIME(`sc`.`started_at`) AS `date_time`,
																	  `sf`.`name` AS `facility`,
																	  `scp`.`name` AS `town`,
																	  IF(`sc`.`cust_country` = '4','Axis','Allied') AS `side`,
																	  `sc`.`contention`,
																	  `sc`.`took_control`,
																	  `sc`.`took_ownership`
																	FROM
																	  `strat_captures` AS `sc`
																	INNER JOIN
																	  `wwii_player` AS `wp` ON `sc`.`customerID` = `wp`.`playerid`
																	INNER JOIN
																	  `strat_facility` AS `sf` ON `sc`.`facility_oid` = `sf`.`facility_oid`
																	INNER JOIN
																	  `strat_cp` AS `scp` ON `sf`.`cp_oid` = `scp`.`cp_oid`
                                                                    WHERE 
                                                                    	`sc`.`state` = '2' 
																	ORDER BY
																	  `sc`.`started_at` DESC
                                                                    LIMIT 15");
		$recentEventsData = $dbHelper->getAsArray($recentEventsQuery);

		/**
		 * NOTE: This script assumes that all dates in the database
		 * are Rat time (CST, or "America/Chicago")
		 * 
		 * The server must be set to the correct datetime for this to work
		 */
		$serverTimezone = new DateTimeZone(date_default_timezone_get());

		/**
		* Update the row's datetime (yyyy-mm-dd hh:mm:ss) to just a time (hh:mm)
		*/
		foreach($recentEventsData as $key => $value){
			$recentEventTime = new DateTime($recentEventsData[$key]['date_time'], $serverTimezone);
			$recentEventTime = $recentEventTime->format('H:i');
			$recentEventsData[$key]['date_time'] = $recentEventTime;
		}
		
		return $recentEventsData;
	}
}