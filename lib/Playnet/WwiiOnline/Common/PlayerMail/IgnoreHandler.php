<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\Common\PlayerMail;

use Monolog\Logger;

/**
 * The ignore handler will take any request to send an email and ignore it,
 * effectively bypassing the sending email. This handler can be used in development
 * enviroments when testing other features so that you are not sending out emails
 * every time.
 * 
 * @author Jason "drloon" Rout
 */
class IgnoreHandler implements HandlerInterface {
	
	public function __construct(Logger $logger, array $options = []) {
		// Ignore the options
	}
	
	public function addMessage(Message $message) {
		// Dont do anything
		return;
	}

	public function send() {
		// Don't do anything
		return;
	}

	public function getOption($key) {
		return '';
	}

//put your code here
}
