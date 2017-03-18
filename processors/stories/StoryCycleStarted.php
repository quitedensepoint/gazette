<?php

/**
 * Executes the logic to generate a story from the 
 * "Cycle Started" source.
 */
class StoryCycleStarted extends StoryBase implements StoryInterface {
	
	public function isValid() {

		/**
		 * Get the number of factories producing output. If zero, this story is tue
		 */
		return $this->getCycleCount($this->creatorData['country_id']) > 0;

	}
	
	/**
	 * Get the current cycle count
	 * 
	 * @todo Figure out how the factories table is populated 
	 * 
	 * @return integer
	 */
	public function getCycleCount($countryId)
	{
		$dbHelper = new dbhelper($this->dbConn);
		
		$query = $dbHelper
			->prepare("select count(*) as cycle_count "
				. "from countries c INNER JOIN cycles cy ON c.cycle_id = cy.cycle_id "
				. "where c.country_id = ? and (UNIX_TIMESTAMP() - UNIX_TIMESTAMP(cy.start_time) < 10800)", [$countryId]);	
		
		return $dbHelper->getAsArray($query)[0]['cycle_count'];					
	}


}
