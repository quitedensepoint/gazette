<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\Common\PlayerMail;

use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;	
use Monolog\Logger;

use SplObjectStorage;

/**
 * The Rest handler will take any request to send an email and send it to the REST service
 * defined in the options. This is essentially the "production version" of the player emailer.
 * 
 * Check the Confluence document for Gazette Player Emails for information on the data formats.
 * 
 * @author Jason "drloon" Rout
 */

class RestHandler implements HandlerInterface{
	
	/**
	 * A reference for the options passed to the object controls.
	 * 
	 * @var array
	 * @see DBConn.example.php
	 */
	private $options;
	
	/**
	 * The set of messages to send
	 * 
	 * @var SplObjectStorage 
	 */
	private $messages;
	
	/**
	 *
	 * @var GuzzleHttp\Client 
	 */
	private $client;
	
	/**
	 *
	 * @var Monolog\Logger; 
	 */
	private $logger;
	
	public function __construct(Logger $logger, array $options = []) 
	{

		$this->logger = $logger;		
		$this->options = $options;
		$this->messages = new SplObjectStorage();
		
		$this->client = new Client([
			'base_uri' => $this->options['api']['base_uri'],
			'timeout' => 5
		]);

		
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

		// Requeue the messages
		$this->messages->rewind();
		
		if($this->messages->count() == 0)
		{
			$this->logger->warning("No emails to send!");
			return;
		}
		
		$this->logger->debug("Sending an email");
		
		$jsonData = [];
		while($message = $this->messages->current())
		{
			// Set the subject for the email
			$message->setSubject(trim($this->options['subject']));
			$jsonData[] = $message->toArray();
			$this->messages->next();
		}
		
		$requestData = [
			// May need to set verify to false on some dev systems, NEVER on production. Bloody things.
			// This prevents verification of the SSL certificates for the API, if the certs are bad
			'verify' => true,
			'auth' => [
				$this->options['api']['auth_credentials']['username'],
				$this->options['api']['auth_credentials']['password']
			],
			'json' => $jsonData
		];
		
		/**
		 * Log the information we are sending to the API
		 */
		//$this->logger->debug("Request Data", $requestData);

		try {
			$result = $this->client->request('POST', '/v1/sendPlayerEmail', $requestData);
			
			$this->logger->debug('Message successfully sent to API', ['data' => $result->getBody()->getContents()]);
		}
		catch(RequestException $e)
		{
			$this->logger->error($e->getMessage());
		}

		$this->logger->debug("End the send process");
	}
	
	/**
	 * {@inheritdoc}
	 */	
	public function getOption($key) {
		return $this->options[$key];
	}
}
