<?php

/**
 * Executes the logic to generate a story from the 
 * "Airfields Owned" source.
 */
class StoryAirfieldsOwned extends StoryBase implements StoryInterface {
	
	public function isValid() {

		/**
		 * Go through each country and see whom has the most firebases
		 */
		$countries = $this->getCountries();
		
		$airfieldCounts =[];
		$total = 0;
		$rank = 1;

		foreach($countries as $country)
		{
			$airfieldCounts[$country['country_id']] =  $this->getAirfieldsCount($country['country_id']);
			
			$total += $airfieldCounts[$country['country_id']];
		}
		
		// Rank the airfields from most to least
		sort($airfieldCounts);
		
		foreach($airfieldCounts as $key => $count)
		{
			$country = $countries[$key];
			$name = $country['name'];
			$side = $country['side'];
			$percent = intval(($count / $total) * 100);
			
			/*
			 * Start the side percents at zero if they are not yet defined
			 */
			if(!isset($this->creatorData['template_vars'][$side . '_count']))
			{
				$this->creatorData['template_vars'][$side . '_count'] = 
				$this->creatorData['template_vars'][$side . '_percent'] =
					0;
			}
			
			$this->creatorData['template_vars'][$side . '_count'] += $count;
			$this->creatorData['template_vars'][$side . '_percent'] += $percent;
			
			$this->creatorData['template_vars'][$name . '_count'] = $count;
			$this->creatorData['template_vars'][$name . '_percent'] = $percent;
			
			$place = $this->getPlace($rank);
			$this->creatorData['template_vars'][$place . '_count'] = $count;
			$this->creatorData['template_vars'][$place . '_percent'] = $percent;
			$this->creatorData['template_vars'][$place . '_country'] = $name;
		
			/**
			 * The next piece of code is the old Perl code. Not sure what it does yet
			 * 
			 * @todo Figure out what this is for. Emails?
			 */		
			/**
				if($country->{'country_id'} == $vars->{'country_id'}){
					$vars->{'SUBJECT_COUNT'} 	= $count;
					$vars->{'SUBJECT_PERCENT'} 	= $percent;
					$vars->{'SUBJECT_PLACE'} 	= lc(&getPlace($rank));
				}		
			 */	
			$rank++;
		}
		
		return true;
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
	 * Get the total number of airfields by country
	 * 
	 * @param integer $countryId
	 * @return integer
	 */
	public function getAirfieldsCount($countryId)
	{
		$gameDbHelper = new dbhelper($this->dbConnWWIIOnline);
		
		$query = $gameDbHelper
			->prepare("SELECT count(facility_oid) as airbase_count FROM strat_facility "
				. "where facility_type = 8 and open = 1 and country = ?", [$countryId]);	
		
		return $gameDbHelper->getAsArray($query)[0]['airbase_count'];					
	}
	
	/**
	 * Get the name of the rank for a position
	 * 
	 * @param integer $rank
	 * @return string
	 */
	private function getPlace($rank)
	{
		return $rank == 1 ? '1st' : ($rank == 2 ? '2nd' : '3rd');
	}

}
