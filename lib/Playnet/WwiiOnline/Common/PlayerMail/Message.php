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
	
	private $textContent = '';
	
	private $subject = '';
	
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
	
	public function getContent()
	{
		return $this->content;
	}
	
	public function setContent($value)
	{
		if(!is_string($value))
		{
			trigger_error("Content must be a string.", E_USER_ERROR);
		}
		$this->content = $value;
	}
	
	public function getTextContent()
	{
		return $this->textContent;
	}
	
	public function setTextContent($value)
	{
		if(!is_string($value))
		{
			trigger_error("Content must be a string.", E_USER_ERROR);
		}
		$this->textContent = $value;
	}
	
	public function getSubject()
	{
		return $this->subject;
	}
	
	public function setSubject($value)
	{
		if(!is_string($value))
		{
			trigger_error("Subject must be a string.", E_USER_ERROR);
		}
		$this->subject = $value;
	}	
	
	public function toJson()
	{
		return json_encode($this->toArray());
	}
	
	public function toArray()
	{
		$result = [
			'players' => [],
			'subject' => utf8_encode($this->subject),
			'content' => utf8_encode($this->content),
			'textContent' => utf8_encode($this->textContent),
		];
		
		foreach($this->players as $player)
		{
			$result['players'][] = $player->toArray();
		}
		
		return $result;			
	}
}
