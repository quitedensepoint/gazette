<?php

use Playnet\WwiiOnline\WwiiOnline\Models\Facility\Firebase\Firebase;

/**
 * Executes the logic to generate a story from the 
 * "Offensive Country" source.
 */
class StoryOffensiveCountry extends StoryBase implements StoryInterface {
	
	public function isValid() {

		/**
		 * Go through each country and see whom has the most firebases
		 */
		$countries = $this->getActiveCountries();
		
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
	 * Get the total number of firebases by country
	 * 
	 * @param integer $countryId
	 * @return integer
	 */
	public function getFirebaseCount($countryId)
	{
		$dbHelper = new dbhelper($this->dbConnWWIIOnline);
		
		$result = $dbHelper
			->first("SELECT count(facility_oid) as fb_count FROM strat_facility "
				. "where facility_type = ? and open = 1 and country = ?", [Firebase::getTypeId(), $countryId]);	
		
		return $result['fb_count'];					
	}

}
