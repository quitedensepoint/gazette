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



//get todays date and subtract start date to get number of days between the two.
// date_default_timezone_set("America/Chicago");
// $today = time();
// $today=date("d-M-Y", $today);
// $start= date("d-M-Y", strtotime($row['start_time']));
// $days= $today-$start-1;

$days = date_diff(date_create($row['start_time'])),date_create(date('Y-m-d')))->format('%d');

?>