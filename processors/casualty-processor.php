<?php
/**
 * Description of casualty-processor
 *
 * @author Jason Rout
 */

class CasualtyProcessor {

	protected $dbConn;
	
	public function __construct($dbConn) {
		
		$this->dbConn = $dbConn;
	}
	
	/**
	 * Processes the casualities database entries for display on the screen
	 * 
	 * @return array
	 */
	public function process()
	{
		$dbHelper = new dbhelper($this->dbConn);
		
		$campaignQuery = $dbHelper
			->prepare("SELECT id FROM `campaigns` WHERE `status` = 'Running' LIMIT 1");
		$campaignData = $dbHelper->getAsArray($campaignQuery);
		
		$campaignId = $campaignData[0]['id'];
		
		/**
		 * This will load all the latest casualty data along with the 
		 * associated country records
		 */
		$casualtyQuery = $dbHelper->prepare("SELECT casualty.kill_count, casualty.branch_id, countries.side FROM campaign_casualties AS casualty INNER JOIN "
			. " countries ON casualty.country_id = countries.country_id"
			. " WHERE period_end = (SELECT MAX(period_end) FROM campaign_casualties WHERE campaign_id = ?) AND campaign_id = ?",
			[$campaignId, $campaignId]);
		
		$casualtyData = $dbHelper->getAsArray($casualtyQuery);

		/**
		 * Loop through the database records and built a structure to display
		 */		
		$casualties = [
			'allied'	=>	['ground' => 0, 'air' => 0, 'sea' => 0],
			'axis'		=>	['ground' => 0, 'air' => 0, 'sea' => 0]
		];

		foreach($casualtyData as $casualty)
		{
			/* @var $casualty CampaignCasualty */
			$side = strtolower($casualty['side']);
			
			switch($casualty['branch_id'])
			{
				case 1 : {
					$casualties[$side]['air'] += $casualty['kill_count'];
					break;
				}
				case 3 : {
					$casualties[$side]['sea'] += $casualty['kill_count'];
					break;
				}
				default : {
					// includes infantry and tanks
					$casualties[$side]['ground'] += $casualty['kill_count'];
					break;					
				}
			}
		}
		
		return $casualties;
	}
}
