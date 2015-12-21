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

// Find Current Version
$vers=$dbConnWWIIOL->query("SELECT version, description FROM `wwii_checksums` WHERE `checksum_oid`='618'");
$ver=$vers->fetch_assoc();

//get todays date and subtract start date to get number of days between the two.
$today = time () ;
$today=date("d-M-Y", $today);
$start= date("d-M-Y", strtotime($row['start_time']))."<br>";
$days= $today-$start;





?>