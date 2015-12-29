<?php

/**
 * An abstract class to help parse the stories
 */
abstract class StoryBase
{
	/**
	 * A database connection to the gazette DB
	 * @var resource 
	 */
	protected $dbConn;
	
	/**
	 * A database helper class
	 * @var dbhelper
	 */
	protected $dbHelper;
	
	
	/**
	 * Connection to the wwii DB
	 * @var resource 
	 */
	protected $dbConnWWII;
	
	/**
	 * Connection to the wwiionline DB
	 * @var resource 
	 */
	protected $dbConnWWIIOnline;
	
	/**
	 * An array of data used to populate the stories
	 * @var array
	 */
	protected $creatorData;	
	
	public function __construct($dbConn, $dbConnWWII, $dbConnWWIIOnline, $creatorData) {
		$this->dbConn = $dbConn;
		$this->dbConnWWII = $dbConnWWII;
		$this->dbHelper = new dbhelper($dbConn);
		$this->dbConnWWIIOnline = $dbConnWWIIOnline;
		$this->creatorData = $creatorData;		
	}
	
	public function makeStory($template) {

		$result = $this->parseStory($this->creatorData['template_vars'], $template['title'], $template['body']);
		
		/**
		 * Randomise some of the text in the template based on the variety_1 field in the templates table
		 */
		$varieties1 = explode(";", trim($template['variety_1']));
		
		$result = str_replace('%VARIETY1%', $varieties1[rand(0, count($varieties1) - 1)], $result);
		
		/**
		 * Do the same for the varieties2 column
		 */
		$varieties2 = explode(";", trim($template['variety_2']));
		
		$result = str_replace('%VARIETY2%', $varieties2[rand(0, count($varieties2) - 1)], $result);		
		
		return $result;
	}	
	
	/**
	 * Take the template vars and the templates, and turns them into the story
	 * paramaters from the source templates 
	 * 
	 * @param array $template_vars
	 * @return array	An array of [title, body] which is used in the template, in the stories table
	 */
	public function parseStory($template_vars, $title, $body)
	{
		
		$data = [
			'title' => $title,
			'body' => $body
		];

		foreach ($template_vars as $key => $value)
		{
			$data['title'] = str_replace('%' . strtoupper($key) . '%', $value, $data['title']);
			$data['body'] = str_replace('%' . strtoupper($key) . '%', $value, $data['body']);
		}

		return $data;		
	}
	
	/**
	 * Get the name of the rank for a position
	 * 
	 * @param integer $rank
	 * @return string
	 */
	protected function getPlace($rank)
	{
		return $rank == 1 ? '1st' : ($rank == 2 ? '2nd' : '3rd');
	}
	
	/**
	 * Gets the text description of an RTB status
	 * 
	 * @param integer $statusId
	 * @return string
	 */
	public function getRTBStatus($statusId)
	{
		$status = "Killed in Action";
		
		switch(intval($statusId))
		{
			case 1: {
				$status = 'Returned to Base';
				break;
			}
			case 2: {
				$status = 'Rescued';
				break;
			}
			case 3: {
				$status = 'Missing in Action';
				break;
			}
			default:
			{
				// Do nothing
			}
		}
		
		return $status;
	}
	
	/**
	 * Retrieve the record of a single player, or an empty array if no record
	 * 
	 * @param integer $playerId The ID of the record
	 * @return array
	 */
	public function getPlayerById($playerId)
	{
		$dbHelper = new dbhelper($this->dbConnWWIIOnline);
		
		$query = $dbHelper
			->prepare("SELECT * from wwii_player WHERE playerid = ? LIMIT 1", [$playerId]);	

		return $dbHelper->getAsArray($query);					
	}
	
	/**
	 * Retrieve the record of a single sortie, or an empty array if no record
	 * 
	 * @param integer $playerId The ID of the record
	 * @return array
	 */
	public function getSortieById($sortieId)
	{
		$dbHelper = new dbhelper($this->dbConnWWIIOnline);
		
		$query = $dbHelper
			->prepare("SELECT * from wwii_sortie WHERE sortie_id = ? LIMIT 1", [$sortieId]);	

		return $dbHelper->getAsArray($query);					
	}
	
	/**
	 * Retrieve the record for a single facility
	 * 
	 * @param integer $facilityId
	 * @return array
	 */
	public function getFacilityById($facilityId)
	{
		$dbHelper = new dbhelper($this->dbConnWWIIOnline);
		
		$query = $dbHelper
			->prepare("SELECT * from strat_facility WHERE facility_oid = ? LIMIT 1", [$facilityId]);	

		return $dbHelper->getAsArray($query);					
	}
	
	/**
	 * Retrieve the record for a single vehicle
	 * 
	 * @param integer $vehicleId
	 * @return array
	 */
	public function getVehicleById($vehicleId)
	{
		$dbHelper = new dbhelper($this->dbConnWWIIOnline);
		
		$query = $dbHelper
			->prepare("SELECT * from wwii_vehtype WHERE vehtype_oid = ? LIMIT 1", [$vehicleId]);	

		return $dbHelper->getAsArray($query);					
	}
	
	/**
	 * Retrieve a vehicle by its classificaiton. This can be used to find a vehicle from a sortie
	 * (where the vehicle id is not directly referenced)
	 * 
	 * @param integer $countryId
	 * @param integer $categoryId
	 * @param integer $classId
	 * @param integer $typeId
	 * @return array
	 */
	public function getVehicleByClassification($countryId, $categoryId, $classId, $typeId)
	{
		$dbHelper = new dbhelper($this->dbConnWWIIOnline);
		
		$query = $dbHelper
			->prepare("SELECT * from wwii_vehtype WHERE countryID = ? AND categoryID = ? "
				. "AND classID = ? AND typeID = ? LIMIT 1", [$countryId, $categoryId, $classId, $typeId]);	

		return $dbHelper->getAsArray($query);					
	}	
}

