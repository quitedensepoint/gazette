<?php

/**
 * Executes the logic to generate a story from the 
 * "Recent Promotion" source.
 */
class StoryRecentPromotion extends StoryBase implements StoryInterface {
	
	
	
	public function isValid() {

		/**
		 * Get all the promotions that have occurred in the last day
		 */
		$time = new DateTime();
		$time->setTimezone(self::$timezone);
		$time->setTimestamp(time() - self::DAY_IN_SECONDS);
		
		$promotions = $this->getRecentPromotions($this->creatorData['country_id'], $time->getTimestamp());
		foreach($promotions as $promotion)
		{
			if(!$this->promotionStoryExists($promotion['customerid'], $promotion['rankid']))
			{
				$this->creatorData['template_vars']['user_id']		= $promotion['customerid'];
				$this->creatorData['template_vars']['player'] 		= $promotion['callsign'];
				
				$rankData = $this->getRankById( $promotion['rankid']);
				$this->creatorData['template_vars']['rank']			= $rankData[0]['description'];
				
				$branchData = $this->getBranchById( $promotion['branchid']);
				$this->creatorData['template_vars']['rank_branch']	= $branchData[0]['name'];
				$this->creatorData['template_vars']['significance']	= intval($promotion['rankid']) > 8 ? 'high level': 'mid-level';				
				
				$this->recordPromotionStory($promotion['customerid'], $promotion['rankid']);
				
				$dt = DateTime::createFromFormat("Y-m-d H:i:s", $promotion['modified'], self::$timezone);		
				$this->creatorData['template_vars']['military_hours'] = $dt->format('Hi');
				$this->creatorData['template_vars']['month'] = $dt->format('F');
				$this->creatorData['template_vars']['day_ord'] = $dt->format('j');
				
				$frontlineCity = $this->getRandomFrontlineCityForCountry($this->creatorData['country_id']);
				$this->creatorData['template_vars']['front_city'] = $frontlineCity[0]['name'];
				
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Retrieve a set of promitons
	 * 
	 * @param integer $countryId
	 * @param integer $time Unix timestamp
	 * @return integer
	 */
	public function getRecentPromotions($countryId, $time)
	{
		$gameDbHelper = new dbhelper($this->dbConnWWIIOnline);
		
		$query = $gameDbHelper
			->prepare("SELECT pl.customerid, pl.callsign, pe.rankid, pe.branchid, pe.modified "
				. "FROM wwii_player pl INNER JOIN  wwii_persona pe ON pl.playerid = pe.playerid "
				. "WHERE pl.modified > FROM_UNIXTIME(?) and pl.isplaynet != 1 AND pe.rankid > 5 AND pe.rankid < 14 "
				. "AND pe.rankpoints = 0 AND pe.countryid = ? and pl.modified < pe.modified "
				. "ORDER BY pe.rankid DESC LIMIT 10", [$time, $countryId]);	
		
		return $gameDbHelper->getAsArray($query);					
	}
	
	/**
	 * Has a story already been created for this promotion?
	 * 
	 * @param integer $customerId
	 * @param integer $rankId
	 * 
	 * @return boolean
	 */
	public function promotionStoryExists($customerId, $rankId)
	{
		$dbHelper = new dbhelper($this->dbConn);
		
		$query = $dbHelper
			->prepare("select count(*) as story_count from promotions where customer_id = ? and rank_id = ?", [$customerId, $rankId]);	
		
		return intval($dbHelper->getAsArray($query)[0]['story_count']) > 0;			
	}
	
	/**
	 * Record a promotion in the gazette promotions table
	 * 
	 * @param integer $customerId
	 * @param integer $rankId
	 * @return void
	 */
	public function recordPromotionStory($customerId, $rankId)
	{
		
		$dbHelper = new dbhelper($this->dbConn);
		
		$query = $dbHelper
			->prepare("INSERT INTO promotions (customer_id, rank_id, added) VALUES (?,?,NOW())", [$customerId, $rankId]);	
		
		$query->execute();			
	}

}
