<?php

/**
 * Executes the logic to generate a story from the 
 * "Latest Intel" source.
 */
class StoryCampaignStarted implements StoryInterface {
	
	protected $dbConn;
	protected $dbHelper;
	
	protected $dbConnWWIIOnline;
	protected $creatorData;
	
	protected $cpOwnershipPercent;
	
	public $title;
	
	public $body;
	
	public function __construct($dbConn, $dbConnWWIIOnline, $creatorData) {
		$this->dbConn = $dbConn;
		$this->dbHelper = new dbhelper($dbConn);
		$this->dbConnWWIIOnline = $dbConnWWIIOnline;
		$this->creatorData = $creatorData;
		
	}
	
	
	public function isValid() {

		/**
		 * If the campaign has been going less than a day, this is a valid story
		 */
		 return $this->getCampaignStartedLessThanOneDayAgo();		
	}

	public function makeStory() {
		

		$this->title = $this->creatorData['template']['title'];
		$this->body = $this->creatorData['template']['body'];
		
	}
	
	/**
	 * Check if there are any campaigns started less than a day ago
	 * 
	 * @return boolean
	 */
	public function getCampaignStartedLessThanOneDayAgo()
	{
		$gazetteDbHelper = new dbhelper($this->dbConn);
		
		$query = $gazetteDbHelper
			->prepare("select count(*) as campaign_count from campaigns where status = 'Running' and (UNIX_TIMESTAMP() - UNIX_TIMESTAMP(start_time)) < 86400");	
		
		return $gazetteDbHelper->getAsArray($query)[0]['campaign_count'] > 0;					
	}

}
