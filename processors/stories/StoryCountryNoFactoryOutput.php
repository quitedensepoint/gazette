<?php

/**
 * Executes the logic to generate a story from the 
 * "Latest Intel" source.
 */
class StoryCountryNoFactoryOutput extends StoryBase implements StoryInterface {
	
	public function isValid() {

		/**
		 * Get the number of factories producing output. If zero, this story is tue
		 */
		return $this->getProductionCount($this->creatorData['country_id']) == 0;

	}
	
	/**
	 * Get the number of factories producing for the country
	 * 
	 * @todo Figure out how the factories table is populated 
	 * 
	 * @return integer
	 */
	public function getProductionCount($countryId)
	{
		$dbHelper = new dbhelper($this->dbConn);
		
		$query = $dbHelper
			->prepare("select count(*) as production_count from factories where ocountry_id = ? and status in ('Full Production','Reduced Production')", [$countryId]);	
		
		return $dbHelper->getAsArray($query)[0]['production_count'];					
	}

}