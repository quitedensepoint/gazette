<?php
/*
* Created for checking the current game version in order to display it
*
* @author B2k / Updated Sniper62
*/

class GameVersionProcessor{

	protected $dbConn;
	
	public function __construct($dbConn){
		
		$this->dbConn = $dbConn;
	}
	
	/**
	 * Processes the auth database entries for display on the screen
	 * 
	 * @return variable
	 */
	public function process(){
		$dbHelper = new dbhelper($this->dbConn);
		
		$gameVersionQuery = $dbHelper->prepare("SELECT MAX(`maxClientVersion`) AS maxClientVersion FROM `app_host_versions` WHERE `appID` = 1");
		$gameVersionData = $dbHelper->getAsArray($gameVersionQuery);
		$gameVersion = $gameVersionData[0]['maxClientVersion'];
		
		if($gameVersionData[0]['maxClientVersion'] == null){
			/** No version, jut return an empty set **/
			$gameVersion = "?";
			
			return $gameVersion;
		}

		return $gameVersion;
	}
}

?>