<?php

require_once(__DIR__ . '/../DBconn.php');
require_once(__DIR__ . '/../vendor/autoload.php');
/**
 * This file will intercept any _runtime_ errors in the PHP code and display a
 * nicer error.
 * 
 * @param integer $errno
 * @param string $errstr
 * @param string $errfile
 * @param string $errline
 * @param array $errcontext
 */
set_error_handler(function($errno , $errstr, $errfile, $errline, $errcontext) {

	// This is a very bad hack, but has to be used if we want to access anything
	// declare in the config file
	global $options;
	
	/**
	 * Log the error
	 */
	$streamHandler = new \Monolog\Handler\StreamHandler(__DIR__ . "/../logs/error.log");
	$logger = new Monolog\Logger('errorlogger', [$streamHandler]);
	
	$logger->error("Gazette could not be displayed.", [
		'code' => $errno,
		'message' => $errstr,
		'file' => $errfile,
		'line' => $errline,
		//'context' => $errcontext
	]);
	
	$showErrors = $options['error_handling']['show_errors'];
	
	// If the PHP script has been run from the command line, where server
	// protocol does not exist, we just run as we don't want to display content anywhere
	if(!isset($_SERVER['SERVER_PROTOCOL']))
	{
		return;
	}
	
	/**
	 * This function will erase any buffered output so that the error page looks correct
	 */
	if(ob_get_contents() !== false)
	{
		ob_clean();
	}
	
	/**
	 * Parameters will be applied in the include script
	 */
	require_once(__DIR__ . '/../web/error.php');
	
	/**
	 * Send the appropriate 500 error
	 */
	header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
	
	ob_end_flush();
	
	/** Stop processing if there is problem, otherwise we get half built pages **/
	exit;

});

