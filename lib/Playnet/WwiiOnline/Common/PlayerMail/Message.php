<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\Common\PlayerMail;

use SplObjectStorage;

/**
 * The message is what is used to collate the information that will eventually be
 * sent to email API
 * 
 * @author Jason "drloon" Rout
 */
class Message {
	
	/**
	 * A set of players to receive the message
	 * 
	 * @var SplObjectStorage
	 */
	private $players;
	
	/**
	 * The content of the message
	 * @var string
	 */
	private $content = '';
	
	/**
	 *
	 * @var string 
	 */
	private $textContent = '';
	
	/**
	 * The subject for the message
	 * @var string 
	 */
	private $subject;
	
	public function __construct() 
	{
		$this->players = new SplObjectStorage();		
	}
	
	public function addPlayer(MessagePlayer $player)
	{
		$this->players->attach($player);
	}
	
	public function removePlayer(MessagePlayer $player)
	{
		$this->players->detach($player);
	}	
	
	/**
	 * 
	 * @return string
	 */
	public function getContent()
	{
		return $this->content;
	}
	
	/**
	 * 
	 * @param string $value
	 */
	public function setContent($value)
	{
		if(!is_string($value))
		{
			trigger_error("Content must be a string.", E_USER_ERROR);
		}
		$this->content = $value;
	}
	
	/**
	 * Get the content for the text portion of the email
	 * @return string
	 */
	public function getTextContent()
	{
		return $this->textContent;
	}
	
	/**
	 * Set the content for the text portion of the email
	 * @param string $value
	 */
	public function setTextContent($value)
	{
		if(!is_string($value))
		{
			trigger_error("Content must be a string.", E_USER_ERROR);
		}
		$this->textContent = $value;
	}
	
	/**
	 * Get the email subject
	 * @return string
	 */
	public function getSubject()
	{
		return $this->subject;
	}
	
	/**
	 * Set the email subject
	 * @param string $value
	 */
	public function setSubject($value)
	{
		if(!is_string($value))
		{
			trigger_error("Subject must be a string.", E_USER_ERROR);
		}
		$this->subject = $value;
	}	
	
	/**
	 * Returns a json formatted string for the array
	 * @return string
	 */
	public function toJson()
	{
		return json_encode($this->toArray());
	}
	
	/**
	 * Returns an array of detail matching the spec for a single email
	 * @return array
	 */
	public function toArray()
	{
		$result = [
			'players' => [],
			'subject' => utf8_encode($this->subject),
			'content' => utf8_encode($this->content),
			'text' => utf8_encode($this->textContent),
		];
		
		$this->players->rewind();
		while($player = $this->players->current())
		{
			$result['players'][] = $player->toArray();
			$this->players->next();
		}
		
		return $result;			
	}
}
