<?php

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

	/**
	 * Parameters will be applied in the include script
	 */
	require_once(__DIR__ . '/../web/error.php');

});

