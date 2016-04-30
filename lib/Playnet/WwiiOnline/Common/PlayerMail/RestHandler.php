<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\Common\PlayerMail;

use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;	
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

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
	
	public function __construct(array $options = []) 
	{
		$this->options = $options;
		$this->messages = new SplObjectStorage();
		
		$this->client = new Client([
			'base_uri' => $this->options['api']['base_uri'],
			'timeout' => 5
		]);

		$this->logger = new Logger('playeremail');
		$this->logger->pushHandler(new StreamHandler($this->options['api']['log_path'] . 'playermailer.log', $this->options['api']['log_level']));
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
		$this->logger->debug("Starting the send process");
		// Requeue the messages
		$this->messages->rewind();
		
		if($this->messages->count() == 0)
		{
			return;
		}
		
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
			'verify' => true,
			'auth' => [
				$this->options['api']['auth_credentials']['username'],
				$this->options['api']['auth_credentials']['password']
			],
			'json' => $jsonData
		];
		$this->logger->debug("Request Data", $requestData);
		
		$logger = $this->logger;
		$promise = $this->client->requestAsync('POST', 'sendPlayerMail', $requestData);
		$promise->then(
			function (ResponseInterface $res) use($logger) {
				$logger->debug('Message successfully sent to API', ['data' => $res->getBody()]);
			},
			function (RequestException $e) use($logger) {

				$logger->error($e->getMessage());
			}
		);
		
		// Wait until the remote request is fulfilled
		$promise->wait(false);

		$this->logger->debug("End the send process");
	}
	
	/**
	 * {@inheritdoc}
	 */	
	public function getOption($key) {
		return $this->options[$key];
	}
}
