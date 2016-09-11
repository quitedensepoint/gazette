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
	
	// Add story generator options
	'storygenerator' => [
		
		// Options for the logging of story generation
		'log' => [
			// Limit the rotating log to 14 days
			'retention_days' => 14,			
			// Record all events of this level and above
			'level' => 'debug',
			// If true will out debugging to the console. Good for local dev and debug
			'console' => true,
			// Timezone of the logger
			'timezone' => 'America/Chicago'
		]
	],	
	
	// Set the Environment's to use for the WebMap URLs on the Main page. Options: dev / live
	'webmap-environment' => 'dev',

	// Options for the player mailer (see Confluence Documentation)
	'playerMail' => [
		
		// The subject to go out on the emails
		'subject' => 'World War II Online Gazette: You\'ve been mentioned!',
		
		// The classname (in the Playnet\WiiOnline\Common\PlayerMail namespace
		'handler' => 'IgnoreHandler',
		
		// The options to pass into the constructor of the handler
		'options' => [
			
			// Only used by the TestMailHandler (see the Confluence documentation)
			'smtp' => [
				// If Active, will send out test emails to third parties via a standard SMTP gateway
				'active' => true,
				
				// SMTP Gateway host (mailgun.org is good for sandbox testing
				'host' => 'smtp.mailgun.org',
				
				// Username of your SMTP account
				'username' => 'example@my-sandbox-key.mailgun.org',
				// Password for your SMTP account
				'password' => 'a nice strong password',
				// The email address that the message will appear to be from
				'from' => 'you@example.org',
				// The email address that the email will be sent to
				'to' => 'you@example.org',
				
				// The array of third parties who are to receive your dev and test emails
				'test_recipients' => [ 
					'xoom@playnet.com',
					'foo@playnet.com'
				]				
			],
			
			// Emails may have image references in them to external assets. This is the base URL to use
			'assets_base_url' => 'http://playnet.com/example-path/gazette/',
			
			// The subject line of the outgoing email
			'subject' => 'World War II Online Gazette: You\'ve been mentioned!',
			
			// When developing, the local account to send the emails to. Leave it blank to skip local emails
			'dev_recipient' => 'developer@localhost',			
		
			// How long to keep the log files for the sending of player emails
			'log_retention_days' => 14,

			// **** FOR DEVELOPMENT ONLY ****
			// if true, will logout output from the REST mailer to the command line as well as log files
			'log_to_console' => false,
			
			// How long the system should wait, in minutes, between sending a user another notification
			'notification_rate' => 24 * 60, // 24 hours * 60 minutes

			// Unsubscribe information
			'unsubscribe' => [			
				'url' => 'http://gazette.localhost',
				'path' => '/unsubscribe.php'
			],			
			
			'api' => [
				// The URL base of gadgets API to send player notifications. Must end in a slash
				'base_uri' => 'https://internal-api.community-dev.playnet.com/router.php/v1/',

				// The path where logging of API calls and errors is sent to
				'log_path' => __DIR__ . '/logs/',
				
				// Logging level - 
				/**
				 *    const EMERGENCY = 'emergency';
				 *	const ALERT     = 'alert';
				 *	const CRITICAL  = 'critical';
				 *	const ERROR     = 'error';
				 *	const WARNING   = 'warning';
				 *	const NOTICE    = 'notice';
				 *	const INFO      = 'info';
				 *	const DEBUG     = 'debug'; 
				 */
				'log_level' => 'debug', // Monolog\Logger::DEBUG, - can't use Monolog setting in DBCONN as the autoloader hasn't kicked in yet			
				
				/**
				 * The credentials used to authenticate against the API
				 */
				'auth_credentials' => [
					'username' => 'foo',
					'password' => 'bar'
				]				
			]

		]
	],
	
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