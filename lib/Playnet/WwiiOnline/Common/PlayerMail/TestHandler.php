<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\Common\PlayerMail;

use Swift_Message;
use Swift_MailTransport;
use Swift_SmtpTransport;
use Swift_TransportException;
use Swift_Mailer;

use SplObjectStorage;
use Monolog\Logger;

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
	
	/**
	 *
	 * @var Logger 
	 */
	private $logger;
	
	public function __construct(Logger $logger, array $options = []) 
	{
		$this->logger = $logger;
		$this->options = $options;
		$this->messages = new SplObjectStorage();
	}
	
	/**
	 * {@inheritdoc}
	 */	
	public function addMessage(Message $message) 
	{
		$this->messages->attach($message);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function send() 
	{
		// This transport is used for sending out to local mailer for development
		$mailTransport = Swift_MailTransport::newInstance();
		
		// This transport is for sending to third parties
		$smtpTransport = null;
		if($this->options['smtp']['active'])
		{
			$smtpTransport = Swift_SmtpTransport::newInstance(trim($this->options['smtp']['host']), 25)
				->setUsername($this->options['smtp']['username'])
				->setPassword($this->options['smtp']['password'])
				;
		}
		
		$this->messages->rewind();
		
		while($message = $this->messages->current())
		{
			$swiftMessage = Swift_Message::newInstance();

			$swiftMessage->setSubject(trim($this->options['subject']));
			/* @var Message $message */
			$swiftMessage->setBody($message->getContent(), 'text/html');
			//$swiftMessage->addPart($message->getText(), 'text/plain');
			
			$swiftMessage->setSender($this->options['smtp']['username']);
			//$swiftMessage->setFrom('noreply@playnet.com');
			$swiftMessage->setFrom($this->options['smtp']['from']);
			$swiftMessage->setTo($this->options['smtp']['to']);
			
			if(trim($this->options['dev_recipient']) !== '')
			{
				$swiftMessage->addBcc(trim($this->options['dev_recipient']));
			}
			
			$mailTransport->send($swiftMessage);
			
			// If SMTP is active, send to third party email accounts
			if($this->options['smtp']['active'])
			{
				$mailer = Swift_Mailer::newInstance($smtpTransport);
				
				$swiftMessage->setBcc($this->options['smtp']['test_recipients']);
				try {
					$mailer->send($swiftMessage, $failedRecipients);
				}
				catch(Swift_TransportException $ex)
				{
					echo $ex->getMessage();
				}
			}
			
			$this->messages->next();
		}
		
	}

	/**
	 * {@inheritdoc}
	 */
	public function getOption($key) {
		return $this->options[$key];
	}

}
