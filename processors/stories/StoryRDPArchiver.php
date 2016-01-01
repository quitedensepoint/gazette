<?php

/**
 * Executes the logic to generate a story from the 
 * "RDP Archive" source.
 */
class StoryRDPArchiver extends StoryRDPBase implements StoryInterface {
		
	public function isValid() {

		$archiveData = $this->getArchiveData($this->creatorData['side_id']);
		
		/**
		 * @todo Not sure where source data is populated at this point, cannot implement
		 */
		
		return true;
		
	}
	
	/**
	 * Retrieve the RDP Archive Data
	 * 
	 * @param integer $sideId
	 * @return array
	 */
	public function getArchiveData($sideId)
	{
		$dbHelper = new dbhelper($this->dbConn);
		
		$query = $dbHelper
			->prepare("select a.* from countries c, cycle_archives a where c.side_id = ? and c.cycle_id = a.cycle_id order by a.added DESC limit 17",[$sideId]);	
		
		return $dbHelper->getAsArray($query);			
	}

}