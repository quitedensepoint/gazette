<?php 

// Extra brackets important!
(require_once(__DIR__ . '/include/error-handlers.php')) ||
	die('Cannot find the error handler file located at "' . __DIR__ . '/include/error-handlers.php".');

/**
 * This file allows connections to the wwii database required by the gazette
 * for information
 * 
 */

$dbconn = mysqli_connect("p:db_host", "db_user", "db_password", "gazette");
if (mysqli_connect_errno()){
    echo "Failed to connect to the Gazette database: " . mysqli_connect_error();
}

$dbConnWWII = mysqli_connect("p:db_host","db_user","db_password","wwii");
if (mysqli_connect_errno()){
    echo "Failed to connect to WWII database: " . mysqli_connect_error();
}

$dbConnWWIIOL = mysqli_connect("p:db_host","db_user","db_password","wwiionline");
if (mysqli_connect_errno()){
    echo "Failed to connect to WWIIOnline database: " . mysqli_connect_error();
}

$dbconnAuth = mysqli_connect("p:db_host", "db_user", "db_password", "auth");
if (mysqli_connect_errno()){
    echo "Failed to connect to the Auth database: " . mysqli_connect_error();
}

$dbConnToe = mysqli_connect("p:db_host", "db_user", "db_password", "toe");
if (mysqli_connect_errno()){
    echo "Failed to connect to the Toe database: " . mysqli_connect_error();
}

$dbConnCommunity = mysqli_connect("p:db_host", "db_user", "db_password", "community");
if (mysqli_connect_errno()){
    echo "Failed to connect to the Community database: " . mysqli_connect_error();
}

$dbConnWebmap = mysqli_connect("p:db_host", "db_user", "db_password", "webmap");
if (mysqli_connect_errno()){
    echo "Failed to connect to the Webmap database: " . mysqli_connect_error();
}

/**
 * IF you are running on a development system outside of the CST timezone,
 * you'll need to be sure you are doing queries against that timezone as
 * the core wwiionline game data is CST based
 * 
 * Uncomment the next section to address this issue
 */

/*
$dbconn->query("SET SESSION time_zone = 'America/Chicago'");
$dbConnWWII->query("SET SESSION time_zone = 'America/Chicago'");
$dbConnWWIIOnline->query("SET SESSION time_zone = 'America/Chicago'");
$dbconnAuth->query("SET SESSION time_zone = 'America/Chicago'");
$dbConnToe->query("SET SESSION time_zone = 'America/Chicago'");
$dbConnCommunity->query("SET SESSION time_zone = 'America/Chicago'"); 
 */

/**
 * The Options arrays allows us to set some global options regarding the pages
 * to be loaded
 * e.g.
 *   $options = [
 *     'ga-active' => true or false
 *   ];
 */
$options = [
	// if true, Google Analytics will be added to the bottom of the page
	// This should only be set to true on the live server
	'ga-active' => false,
	
	// Keep the campaign check log retention limited to this many days
	'campaigncheck_log_retention_days' => 14,
	
	// Set the Environment's to use for the WebMap URLs on the Main page. Options: dev / live
	'webmap-environment' => 'dev',
	
	/**
	 * Error handling for the gazette
	 */
	'error_handling' => [
		
		/**
		 * if true, will show the actual error in the handling page. Switch to true for development
		 */
		'show_errors' => false 
	]	
];