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
	 * Connection to the wwiionline DB
	 * @var resource 
	 */
	protected $dbConnWWIIOnline;
	
	/**
	 * Connection to the toe DB
	 * @var resource 
	 */	
	protected $dbConnToe;
		
	/**
	 * An array of data used to populate the stories
	 * @var array
	 */
	protected $creatorData;	
	
	public function __construct($dbConn, $dbConnWWIIOnline, $dbConnToe, $creatorData) {
		$this->dbConn = $dbConn;
		$this->dbHelper = new dbhelper($dbConn);
		$this->dbConnWWIIOnline = $dbConnWWIIOnline;
		$this->dbConnToe = $dbConnToe;
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
	 * Retrieves a random city for a specified country
	 * 
	 * @param integer $countryId
	 * @return array
	 */
	public function getRandomCityForCountry($countryId)
	{
		$dbHelper = new dbhelper($this->dbConnWWIIOnline);
		
		$query = $dbHelper
			->prepare("SELECT * FROM strat_cp WHERE country = ? AND cp_type != 5 order by RAND() limit 1",[$countryId]);	
		
		return $dbHelper->getAsArray($query);					
	}
	
	/**
	 * Retrieves a random factory city for a specified country
	 * 
	 * @param integer $countryId
	 * @return array
	 */
	public function getRandomFactoryCityForCountry($countryId)
	{
		$dbHelper = new dbhelper($this->dbConnWWIIOnline);
		
		$query = $dbHelper
			->prepare("SELECT * FROM strat_cp WHERE country = ? AND cp_type != 5 order by RAND() limit 1",[$countryId]);	
		
		return $dbHelper->getAsArray($query);					
	}
	
	/**
	 * Retrieves an adjective describing the rate of change in RDP
	 * 
	 * @param integer $change
	 * @return string
	 */
	public function getRDPChangeAdjective($change) {

		if($change < 3){
			return 'small';
		}
		else if($change < 5){
			return 'noticable';
		}
		else if($change < 10){
			return 'sizable';
		}
		else if($change < 20){
			return 'significant';
		}

		return 'huge';
	}	

}

