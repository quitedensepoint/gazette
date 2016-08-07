<?php
/*
 * Copyright Playnet 2016
 */
namespace Playnet\WwiiOnline\Common\PlayerMail;

use \dbhelper;
use \DateTime;
use \DateTimeZone;
use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Playnet\WwiiOnline\Common\PlayerMail\HandlerInterface;

class NotificationManager {
	
	/**
	 *
	 * @var dbhelper 
	 */
	private $dbHelper;
	
	/**
	 * The number of minutes to wait, for a player, since their last_sent_at before sending another notification
	 * @var integer
	 */
	private $notificationRate;
	
	/**
	 *
	 * @var HandlerInterface 
	 */
	private $handler;
	
	/**
	 *
	 * @var array Array of ['url' => 'http://foo.com', 'path' => '/unsubcribe.php'
	 */
	private $unsubscribeOptions;
	
	/**
	 *
	 * @var Monolog\Logger; 
	 */
	private $logger;	

	/**
	 * 
	 * @param connection $dbConn
	 * @param string $handler The name of the mail handler to use
	 * @param array $options
	 */
	public function __construct($dbConn, $handler, $options) 
	{
		
		/**
		 * Create a 14 day rotating log retention into the emailer
		 */
		$this->logger = new Logger('playeremail');

		/**********
		 * Make sure the log files are always recorded in UTC.
		 * 
		 * NOTE: This different to the database which, for historical reasons, stores as America/Chicago.
		 */
		$this->logger->setTimezone(new DateTimeZone('UTC'));
		$this->logger->pushHandler(new RotatingFileHandler($options['api']['log_path'] . '/player-mailer.log', $options['log_retention_days'], $options['api']['log_level']));
		
		if($options['log_to_console'] === true)
		{
			// Log to the command line
			$this->logger->pushHandler(new StreamHandler('php://stdout', $options['api']['log_level']));
		}		
		
		// Retrieves the classname used to handle player email notifications from the dbConn options
		// Note that the double slash is needed at the end of the class path
		$playerMailHandlerClass = 'Playnet\WwiiOnline\Common\PlayerMail\\' . $handler;
		
		// dbconn is needed in the handlers now to handle player notification unsubscribes
		$this->handler = new $playerMailHandlerClass($this->logger, $options);		
		
		$this->dbHelper = new dbhelper($dbConn);
		$this->notificationRate = $options['notification_rate'];
		$this->unsubscribeOptions = $options['unsubscribe'];

	}
	
	/**
	 * Get the handler controlling the emails
	 * 
	 * @return Playnet\WwiiOnline\Common\PlayerMail\HandlerInterface
	 */
	public function getHandler()
	{
		return $this->handler;
	}
	
	/**
	 * Add a player to the notifications list if not already in there
	 * 
	 * @param integer $playerId
	 * @return array The data of the player added
	 */
	public function addPlayer($playerId)
	{	
		$this->logger->debug('Adding a new player by Id ' . $playerId);
			
		if(empty($this->getPlayer($playerId)))
		{
			$date = $this->getNow();
			
			// generate two md5 hashes and concatenate to have a 60 character string
			// not use to encrypt, just as a reasonably unique token
			$unsubscribeToken = md5(rand()) . substr(md5(rand()), 0, 28);

			$this->dbHelper
				->execute("INSERT INTO player_notifications (player_id, created_at, updated_at, unsubscribe_token, last_sent_at) VALUES (?,?,?,?,?)",
				[$playerId, $date->format("Y-m-d H:i:s"), $date->format("Y-m-d H:i:s"), $unsubscribeToken, $date->format("Y-m-d H:i:s")]);

			$this->logger->debug('Player Added ' . $playerId);
		}
		
		return $this->getPlayer($playerId);
	}
	
	/**
	 * @param integer $playerId The ID of the player to retrieve information for
	 * @return array|null
	 */
	public function getPlayer($playerId)
	{
		$this->logger->debug('getPlayer ' . $playerId);

		return $this->dbHelper->first("SELECT * from player_notifications WHERE player_id = ?", [$playerId]);	
		
	}
	
	/**
	 * Retrieve a player notification by the record id
	 * 
	 * @param type $id
	 * @return array|null
	 */
	public function getRecord($id)
	{
		return $this->dbHelper->first("SELECT * from player_notifications WHERE id = ?", [$id]);			
	}
	
	
	/**
	 * Check to see if a player has unsubscribed from the Gazette emails
	 * 
	 * @param integer $playerId
	 * @return boolean
	 */
	public function isPlayerUnsubscribed($playerId)
	{
		$result = $this->dbHelper->first("SELECT count(player_id) as unsub_count from player_notifications WHERE player_id = ?"
				. " AND unsubscribed_at IS NOT NULL", [$playerId]);
		
		$this->logger->debug('Result:', $result);
		
		return !empty(intval($result['unsub_count']));			
	}
	
	/**
	 * Update the last sent at for a player to the current time
	 * @param integer $playerId
	 * @return void
	 */
	public function updateLastSend($playerId)
	{
		
		$this->logger->debug('updateLastSend ' . $playerId);
		
		$date = $this->getNow();
		
		$this->dbHelper
			->execute("UPDATE player_notifications SET last_sent_at = ? WHERE player_id = ?", [$date->format("Y-m-d H:i:s"), $playerId]);		
	}	
	
	/**
	 * Unsubscribe a player from the gazette emails
	 * 
	 * @param integer $linkId
	 * @param string $token
	 * @return void
	 */
	public function unsubscribe($linkId, $token)
	{
		$date = $this->getNow();
		
		$this->dbHelper
			->execute("UPDATE player_notifications SET unsubscribed_at = ?, updated_at = ? "
				. "WHERE id = ? AND unsubscribe_token = ? AND unsubscribed_at IS NULL", 
				[$date->format("Y-m-d H:i:s"),$date->format("Y-m-d H:i:s"), $linkId, $token]);			
	}
	
	/**
	 * Returns a unique unsubscribe link for a player
	 * @param integer $playerId
	 * @return string|false
	 */
	public function getUnsubscribeLink($playerId)
	{	
		$this->logger->debug('getUnsubscribeLink ' . $playerId);
		
		if(empty($player = $this->getPlayer($playerId)))
		{
			return false;
		}
		
		return sprintf('%s%s?linkid=%d&token=%s', 
			$this->unsubscribeOptions['url'], 
			$this->unsubscribeOptions['path'], 
			$player['id'], 
			$player['unsubscribe_token']
			);	
	}
	
	/**
	 * Can the system send an email to this player?
	 * 
	 * @param integer $playerId
	 * @param integer $timeout How much time, in minutes, must have elapsed since the last send for sending to be allowed
	 * @return boolean
	 */
	public function canSendNotification($playerId)
	{
		$this->logger->debug('canSendNotification ' . $playerId);
		$player = $this->getPlayer($playerId);
		if(empty($player))
		{
			return false;
		}
		
		// Start comparing dates
		$date = $this->getNow();
		
		/* @var $last_sent_at \DateTime */
		$last_sent_at = \DateTime::createFromFormat("Y-m-d H:i:s", $player['last_sent_at'], $this->getTimezone());
		
		// Check to see if the number of seconds have elapsed past the threshold		
		return $date->getTimestamp() - $last_sent_at->getTimestamp() >= $this->notificationRate;
		
	}
	
	/**
	 * 
	 * @return \DateTime
	 */
	private function getNow()
	{
		return  new \DateTime('now', $this->getTimezone());
	}
	
	/**
	 * Get the current timezone
	 * @return \DateTimeZone
	 */
	private function getTimezone()
	{
		return new \DateTimeZone("America/Chicago");
	}
}

