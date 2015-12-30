<?php

/**
 * This class is a base class for the RDP story functionality. It allows us
 * access to some common functions so we don't have to duplicate them across
 * the code base
 * 
 * It is abstract, meaning you have to define child classes to use it
 */
abstract class StoryRDPBase extends StoryBase implements StoryInterface {
	
	
	/**
	 * Get RDP Action data
	 * 
	 * @param string $action An RDP action of either +, <, >, =, -
	 * @return array

	 */
	public function getRDPActionData($action)
	{
		$dbHelper = new dbhelper($this->dbConnToe);
		
		$query = $dbHelper
			->prepare("SELECT * FROM v_rdp_in_progress_2 WHERE action = ? ORDER BY RAND() LIMIT 1",[$action]);	
		
		return $dbHelper->getAsArray($query);					
	}
}