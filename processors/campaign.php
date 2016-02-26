<?php
/*
* For captuing current campaign number and using the start date determining the number of days in the campaign. 
* Also checks for current Version display
*
*
*
*
*/
require(__DIR__ . '/../DBconn.php');
	
// Delect the campaign that is currently running.  	
$currcamp=$dbconn->query("SELECT id, start_time FROM `campaigns` WHERE `status`='Running'");
$row=$currcamp->fetch_assoc();

// Find Current Version PC
$vers=$dbconnAuth->query("SELECT MAX(maxClientVersion), platform FROM app_host_versions WHERE appID='1' AND platform='0' ");
$ver=$vers->fetch_assoc();

// date_default_timezone_set("America/Chicago");

 $today = new DateTime("today");
 $start = new DateTime($row['start_time']);
 $days = $start->diff($today);

 // Get the latest WebMap update DateTime
$datetime=$dbConnWebmap->query("SELECT datetime FROM captures ORDER BY datetime DESC LIMIT 1");
$row=$datetime->fetch_assoc();
$fileDt = date('Y-m-d_H-i', strtotime($row['datetime']));

?>