<?php
/*
 * Copyright Playnet 2016
 */

use Playnet\WwiiOnline\WwiiOnline\Models\Facility\Airbase\Airbase;

/**
 * Executes the logic to generate a story from the 
 * "Airfields Owned" source.
 */
class StoryAirfieldsOwned extends StoryBase implements StoryInterface {
	
	public function isValid() {

		/**
		 * Go through each country and see whom has the most firebases
		 */
		$countries = $this->getActiveCountries();
		
		$airfieldCounts =[];
		$total = 0;
		$rank = 1;

		$rankingCountries  = [];
		foreach($countries as $country)
		{
			$rankingCountries[$country['country_id']] = $country;
			$airfieldCounts[$country['country_id']] =  $this->getAirfieldsCount($country['country_id']);
			
			$total += $airfieldCounts[$country['country_id']];
		}
		
		// Rank the airfields from most to least
		arsort($airfieldCounts);

		foreach($airfieldCounts as $key => $count)
		{
			$country = $rankingCountries[$key];
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
	 * Get the total number of airfields by country
	 * 
	 * @param integer $countryId
	 * @return integer
	 */
	public function getAirfieldsCount($countryId)
	{
		$dbHelper = new dbhelper($this->dbConnWWIIOnline);
		
		$params = [Airbase::getTypeId(), $countryId];
		
		$result = $dbHelper->first("SELECT count(facility_oid) as airbase_count FROM strat_facility "
				. "where facility_type = ? and open = 1 and country = ?", $params);	
		
		return $result['airbase_count'];					
	}

}
