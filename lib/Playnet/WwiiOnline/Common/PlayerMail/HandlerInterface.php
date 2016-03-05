<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\Common\PlayerMail;

/**
 * This interface serves as descriptorr for the different handlers
 * for sending out emails to the player base
 * 
 * There are several different handlers for development and testing
 * 
 * @author Jason "drloon" Rout
 */
interface HandlerInterface {
	
	/**
	 * Adds a new message to the set being sent out
	 * 
	 * @param \Playnet\WwiiOnline\Common\PlayerMail\Message $message
	 */
	public function addMessage(Message $message);
	
	/**
	 * Send the message to the appropriate service
	 */
	public function send();
}
