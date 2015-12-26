<?php

/**
 * Executes the logic to generate a story from the 
 * "Offensive Country" source.
 */
class StoryOffensiveCountry extends StoryBase implements StoryInterface {
	
	public function isValid() {

		/**
		 * Go through each country and see whom has the most firebases
		 */
		$countries = $this->getCountries();
		
		$fireBaseCount = 0;
		
		$countryWithMostFirebases = null;

		foreach($countries as $country)
		{
			$firebases =  $this->getFirebaseCount($country['country_id']);
			if($firebases > $fireBaseCount)
			{
				$fireBaseCount = $firebases;
				$countryWithMostFirebases = $country;
			}
		}
		
		if($countryWithMostFirebases != null)
		{
			$this->creatorData['template_vars']['offensive_country'] = $country['name'];
			$this->creatorData['template_vars']['offensive_country_adj'] = $country['adjective'];
			$this->creatorData['template_vars']['offensive_side'] = $country['side'];
			
			return true;
		}
		
		return false;
	}
	
	/**
	 * Get the countries that are in the papers system
	 * 
	 * @return array
	 */
	public function getCountries()
	{
		$gameDbHelper = new dbhelper($this->dbConn);
		
		$query = $gameDbHelper
			->prepare("SELECT country_id, name, adjective, side FROM countries");	
		
		return $gameDbHelper->getAsArray($query);					
	}
	
	/**
	 * Get the total number of firebases by country
	 * 
	 * @param integer $countryId
	 * @return integer
	 */
	public function getFirebaseCount($countryId)
	{
		$gameDbHelper = new dbhelper($this->dbConnWWIIOnline);
		
		$query = $gameDbHelper
			->prepare("SELECT count(facility_oid) as fb_count FROM strat_facility "
				. "where facility_type = 7 and open = 1 and country = ?", [$countryId]);	
		
		return $gameDbHelper->getAsArray($query)[0]['fb_count'];					
	}

}
