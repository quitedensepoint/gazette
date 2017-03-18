<?php
/*
 * Copyright Playnet 2016
 */

namespace Playnet\WwiiOnline\Common\PlayerMail;

/**
 * The message is what is used to collate the information that will eventually be
 * sent to email API
 * 
 * @author Jason "drloon" Rout
 */
class MessagePlayer {
	
	/**
	 * The id of the player to receive the message
	 * @var integer
	 */
	private $id;
	
	public function __construct($id) 
	{
		$this->id = $id;
	}
	
	/**
	 * Get the ID of the player to receive the message
	 * @return integer
	 */
	public function getId()
	{
		return $this->id;
	}
	
	/**
	 * Set the ID of the player
	 * 
	 * @param integer $value
	 */
	public function setId($value)
	{
		if(!is_integer($value))
		{
			trigger_error("Player ID must be an integer", E_USER_ERROR);
		}
		$this->id = value;
		
	}
	
	/**
	 * Convert the object to a JSON array
	 * @return string
	 */
	public function toJson()
	{
		return json_encode($this->toArray());
	}

	/**
	 * Convert the object to an array
	 * @return array
	 */
	public function toArray()
	{
		return [
			'id' => $this->getId()
		];
	}
}
