<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\Common\PlayerMail;

use Swift_Message;

use SplObjectStorage;

/**
 * The test handler will take any request to send an email and send it to a list of
 * email addresses for test, as define by the options parameter of the constructor. 
 * This handler can be used in development enviroments when testing email outputs
 * 
 * @author Jason "drloon" Rout
 */
class TestHandler implements HandlerInterface {
	
	private $options;
	
	/**
	 * The set of messages to send
	 * 
	 * @var SplObjectStorage 
	 */
	private $messages;
	
	public function __construct(array $options = []) 
	{
		$this->options = $options;
		$this->messages = new SplObjectStorage();
	}
	
	public function addMessage(Message $message) 
	{
		$this->messages->attach($message);
	}

	public function send() 
	{
		// Send the email to noreply, add the test recipients to the
		// Bcc list
		$to = 'noreply@playnet.com';
		$from = 'noreply@playnet.com';
		
		$headers = 'To: ' . $to . PHP_EOL;
		$headers .= 'From: ' . $from . PHP_EOL;
		foreach($this->options['test_recipients'] as $recipient)
		{
			$headers .= 'Bcc: ' . $recipient . PHP_EOL;			
		}
		$subject = $this->options['subject'];
		$headers .= 'Subject: ' . $subject . PHP_EOL;

		$this->messages->rewind();
		
		while($message = $this->messages->current())
		{
			var_dump($headers);
			/* @var Message $message */
			
			$content = $message->getContent();
			if(!mail($to, $subject, $content, $headers))
			{
				echo "WTF";
			
			}
			$this->messages->next();
		}
		
	}
}
