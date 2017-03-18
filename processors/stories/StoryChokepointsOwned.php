<?php

use Playnet\WwiiOnline\WwiiOnline\Models\Chokepoint\Bridge;

/**
 * Executes the logic to generate a story from the 
 * "Chokepoints Owned" source.
 */
class StoryChokepointsOwned extends StoryBase implements StoryInterface {
	
	public function isValid() {

		/**
		 * Go through each country and see whom has the most firebases
		 */
		$countries = $this->getActiveCountries();
		
		$chokepointCounts =[];
		$total = $this->getTotalChokepointCount();
		$rank = 1;

		foreach($countries as $country)
		{
			$chokepointCounts[$country['country_id']] =  $this->getChokepointsCount($country['country_id']);
		}
		
		// Rank the airfields from most to least
		sort($chokepointCounts);
		
		foreach($chokepointCounts as $key => $count)
		{
			$country = $countries[$key];
			$name = $country['name'];
			$side = strtolower($country['side']);
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
			
			$place = $this->getPlace($rank);
			$this->creatorData['template_vars'][$place . '_count'] = $count;
			$this->creatorData['template_vars'][$place . '_percent'] = $percent;
			$this->creatorData['template_vars'][$place . '_country'] = $name;
		
			if($country['country_id'] == $this->creatorData['country_id'])
			{
				$this->creatorData['template_vars']['country_adj'] = $country['adjective'];
			}
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

		$city = $this->getRandomCityForCountry($this->creatorData['country_id']);
		$city = count($city) == 1 ? $city[0]['name'] : 'an undisclosed location';
		$this->creatorData['template_vars']['city'] = $city;
		
		return true;
	}
	
	/**
	 * Get the total number of airfields by country
	 * 
	 * @param integer $countryId
	 * @return integer
	 */
	public function getChokepointsCount($countryId)
	{
		$dbHelper = new dbhelper($this->dbConnWWIIOnline);
		
		$params = [$countryId, Bridge::getTypeId()];
		
		$result = $dbHelper
			->first("SELECT count(*) as chokepoint_count FROM strat_cp WHERE country = ? and cp_type != ?", $params);	
		
		return $result['chokepoint_count'];					
	}
	
	/**
	 * Get the total number of chokepoints
	 * 
	 * @return integer
	 */
	public function getTotalChokepointCount() {
		
		$dbHelper = new dbhelper($this->dbConnWWIIOnline);
		
		$params = [Bridge::getTypeId()];
		
		$result = $dbHelper->first("SELECT count(*) as chokepoint_count from strat_cp WHERE cp_type != ?", $params);	
		
		return $result['chokepoint_count'];
	}

}
