<?php
/*
* Created for capturing the current campaign number and using the start date for determining the number of days the campaign has been running
*
* @author B2k / Updated Sniper62
*/

class CampaignInfoProcessor{

	protected $dbConn;
	
	public function __construct($dbConn){
		
		$this->dbConn = $dbConn;
	}
	
	/**
	 * Processes the campaign database entries for display on the screen
	 * 
	 * @return array
	 */
	public function process(){
		$dbHelper = new dbhelper($this->dbConn);
		
		$campaignQuery = $dbHelper->prepare("SELECT `campaign_id`, `start_time` FROM `campaigns` WHERE `status` = 'Running'");
		$campaignData = $dbHelper->getAsArray($campaignQuery);
		
		if(count($campaignData) == 0){
			/** No campaign running, it must be intermission **/
			$campaignInfo = ['campaignNumber'=>'Intermission', 'campaignDays'=>'0'];
			
			return $campaignInfo;
		}
		
		$today = new DateTime("today");
		$campaignStart = new DateTime($campaignData[0]['start_time']);
		$campaignDays = $campaignStart->diff($today);
		
		$campaignInfo = ['campaignNumber'=>$campaignData[0]['campaign_id'], 'campaignDays'=>$campaignDays->format('%a')];

		return $campaignInfo;
	}
}

?>