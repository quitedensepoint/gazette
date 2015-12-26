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
	 * An array of data used to populate the stories
	 * @var array
	 */
	protected $creatorData;	
	
	public function __construct($dbConn, $dbConnWWIIOnline, $creatorData) {
		$this->dbConn = $dbConn;
		$this->dbHelper = new dbhelper($dbConn);
		$this->dbConnWWIIOnline = $dbConnWWIIOnline;
		$this->creatorData = $creatorData;		
	}	
	
	/**
	 * Take the template vars and the templates, and turns them into the story
	 * paramaters from the source templates 
	 * 
	 * @param array $template_vars
	 * @return array	An array of [title, body] which is used in the template, in the stories table
	 */
	public function parseStory($template_vars)
	{
		
		$data = [
			'title' => $this->creatorData['title_template'],
			'body' => $this->creatorData['body_template']
		];

		foreach ($template_vars as $key => $value)
		{
			$data['title'] = str_replace('%' . strtoupper($key) . '%', $value, $data['title']);
			$data['body'] = str_replace('%' . strtoupper($key) . '%', $value, $data['body']);
		}

		return $data;		
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
}

