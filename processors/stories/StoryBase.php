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
}

