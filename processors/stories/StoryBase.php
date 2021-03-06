<?php

use Monolog\Logger;

/**
 * An abstract class to help parse the stories
 */
abstract class StoryBase
{
	
	const STORY_FORMAT_DEFAULT		= 'default';
	const STORY_FORMAT_HEADLINE		= 'headline';
	const STORY_FORMAT_TABLE		= 'table';
	
	const DAY_IN_SECONDS			= 86400;
	
	/**
	 * A database connection to the gazette DB
	 * @var resource 
	 */
	protected $dbConn;
	
	/**
	 * A database helper class
	 * @var dbhelper
	 */
	protected $dbHelper;

	/**
	 * Connection to the community DB
	 * @var resource 
	 */
	protected $dbConnCommunity;	
	
	/**
	 * Connection to the wwiionline DB
	 * @var resource 
	 */
	protected $dbConnWWIIOnline;
	
	/**
	 * Connection to the toe DB
	 * @var resource 
	 */	
	protected $dbConnToe;
		
	/**
	 * An array of data used to populate the stories
	 * @var array
	 */
	protected $creatorData;
	
	/**
	 * The ID of the player who is the protaganist (subject) of a story, if any
	 * @var integer
	 */
	protected $protagonistId = null;
	
	/**
	 * Logging issues in the story generation
	 * 
	 * @var Logger 
	 */
	protected $logger;
	
	/**
	 * An array of directions  for entering into stories.
	 * @var array
	 */
	public static $directions = [
		['name' => 'south', 'adjective' => 'southern'], 
		['name' => 'southeast', 'adjective' => 'southeastern'], 
		['name' => 'east', 'adjective' => 'eastern'],
		['name' => 'northeast', 'adjective' => 'northeastern'],
		['name' => 'north', 'adjective' => 'northern'],
		['name' => 'northwest', 'adjective' => 'northwestern'],
		['name' => 'west', 'adjective' => 'western'],
		['name' => 'southwest', 'adjective' => 'southwestern']
	];
	
	/**
	 * A set of hardcoded side information
	 * 
	 * @var array 
	 */
	public static $sideData = [
		1 => ['name' => 'allied', 'adjective' => 'allied'],
		2 => ['name' => 'axis', 'adjective' => 'axis'],
	];
	
	/**
	 *	A set ot intensitie descriptors
	 *	@var array
	 */
	public static $intensities = ['light', 'medium', 'heavy'];
	
	/**
	 * The Datetime that the gazette is running under
	 * 
	 * @var DateTimeZone 
	 */
	public static $timezone;
	
	/**
	 * An array of database connections
	 * 
	 * @var array
	 */
	protected $dbConnections;
	
	/**
	 * Is the story revolving around a player?
	 * 
	 * @var boolean 
	 */
	protected $isPlayerCentric = false;
	
	/**
	 * Contains the options passed in from the command line
	 * 
	 * @var type 
	 */
	protected $options;
	
	public function __construct(Logger $logger, $creatorData, array $dbConnections = array(), array $options = []) 
	{		
		$this->logger = $logger;
		$this->creatorData = $creatorData;
		
		$this->dbConnections = $dbConnections;
		$this->dbConn = $dbConnections['dbConn'];
		$this->dbConnCommunity = $dbConnections['dbConnCommunity'];
		$this->dbConnWWIIOnline = $dbConnections['dbConnWWIIOnline'];
		$this->dbConnToe = $dbConnections['dbConnToe'];		
		$this->dbHelper = new dbhelper($this->dbConn);
		
		$this->options = $options;

		self::$timezone = new DateTimeZone('America/Chicago');
	}
	
	/**
	 * 
	 * @inheritDoc
	 */
	public function makeStory($template, $comparePlaceholders = false) {

		$templateVars = $this->creatorData['template_vars'];
		
		$this->logger->debug('Template Variables', $templateVars);
		
		if($comparePlaceholders)
		{
			$this->comparePlaceholders($template, $templateVars);
		}
		
		$result = $this->parseStory($templateVars, $template['title'], $template['body']);
		
		return $this->makeVarieties($template, $result, $templateVars);
	}
	
	/**
	 * Creates a set of varieties out of the initial set of data. This varies selected statements in the
	 * story to make them a bit more random
	 * 
	 * @param array $template
	 * @param string $result
	 * @param array $template_vars
	 * @return string
	 */
	public function makeVarieties($template, $result, $template_vars)
	{
		/**
		 * Randomise some of the text in the template based on the variety_1 field in the templates table
		 * Some varieties also have placeholders that need to be replaced
		 */
		$varieties1 = explode(";", trim($template['variety_1']));
		
		$variety1 = $varieties1[rand(0, count($varieties1) - 1)];
		foreach ($template_vars as $key => $value)
		{
			$variety1 = str_replace('%' . strtoupper($key) . '%', $value, $variety1);
		}		
		
		$result = str_replace('%VARIETY1%', $variety1 , $result);
		
		/**
		 * Do the same for the varieties2 column
		 */
		$varieties2 = explode(";", trim($template['variety_2']));
		
		$variety2 = $varieties2[rand(0, count($varieties2) - 1)];
		foreach ($template_vars as $key => $value)
		{
			$variety2 = str_replace('%' . strtoupper($key) . '%', $value, $variety2);
		}		
		$result = str_replace('%VARIETY2%', $variety2, $result);		
		
		return $result;		
	}
	
	/**
	 * Take the template vars and the templates, and turns them into the story
	 * paramaters from the source templates 
	 * 
	 * @param array $template_vars
	 * @return array	An array of [title, body] which is used in the template, in the stories table
	 */
	public function parseStory($template_vars, $title, $body)
	{
		
		/**
		 * Turn CRLF in the body into P tags
		 */
		$bodyLines = explode("\n", $body);
		$body = "";
		foreach($bodyLines as $bodyLine)
		{
			if(strlen(trim($bodyLine)) > 0 && trim($bodyLine) != '')
			{
				$body .= "<p>" . $bodyLine . "</p>";
			}
		}
		
		$data = [
			'title' => $title,
			'body' => $body
		];

		foreach ($template_vars as $key => $value)
		{
			$data['title'] = str_replace('%' . strtoupper($key) . '%', '<span class="' . strtolower($key) . '">' . $value . '</span>', $data['title']);
			$data['body'] = str_replace('%' . strtoupper($key) . '%', '<span class="' . strtolower($key) . '">' . $value . '</span>', $data['body']);
		}

		return $data;		
	}
	
	/**
	 * Get the name of the rank for a position
	 * 
	 * @param integer $rank
	 * @return string
	 */
	protected function getPlace($rank)
	{
		$ends = array('th','st','nd','rd','th','th','th','th','th','th');
		if (($rank %100) >= 11 && ($rank%100) <= 13)
		   $abbreviation = $rank. 'th';
		else
		   $abbreviation = $rank. $ends[$rank % 10];
		
		return $abbreviation;
	}
	
	/**
	 * Gets the text description of an RTB status, or null if not found
	 * 
	 * @param integer $statusId
	 * @return string|null
	 */
	public function getRTBStatus($statusId)
	{
		$dbHelper = new dbhelper($this->dbConnWWIIOnline);

		$query = $dbHelper
			->prepare("SELECT `name` FROM `wwii_rtb_codes` WHERE `rtb_code` = ? LIMIT 1", [$statusId]);	
		
		$result = $dbHelper->getAsArray($query);
		
		return count($result) == 1 ? $result[0]['name'] : null;			
	}
	
	/**
	 * Retrieve a random direction 
	 * 
	 * Returns an array consisting of [name, adjective]
	 * 
	 * @return array
	 */
	public function getRandomDirection()
	{
		return self::$directions[rand(0, count(self::$directions) - 1)]; 
	}

	/**
	 * Retrieve the record of a single player, or an empty array if no record
	 * 
	 * @param integer $playerId The ID of the record
	 * @return array
	 */
	public function getPlayerById($playerId)
	{
		$dbHelper = new dbhelper($this->dbConnWWIIOnline);
		
		$query = $dbHelper
			->prepare("SELECT * from wwii_player WHERE playerid = ? LIMIT 1", [$playerId]);	

		$result = $dbHelper->getAsArray($query);
		
		if(count($result) == 0)
		{
			$this->logger->error(sprintf('Error generating story - player ID %d could not be found ', $playerId));
		}
		
		return $result;					
	}
	
	/**
	 * Retrieve the record of a single branch
	 * 
	 * @param integer $branchId The ID of the record
	 * @return array
	 */
	public function getBranchById($branchId)
	{
		$dbHelper = new dbhelper($this->dbConn);
		
		$query = $dbHelper
			->prepare("SELECT * from branches WHERE branch_id = ? LIMIT 1", [$branchId]);
		
		$result = $dbHelper->getAsArray($query);
		
		if(count($result) == 0)
		{
			$this->logger->error(sprintf('Error generating story - branch ID %d could not be found ', $branchId));
		}		

		return $result;					
	}	
	
	/**
	 * Retrieve the record of a single sortie, or null if not found
	 * 
	 * @param integer $sortieId The ID of the record
	 * @return array|null
	 */
	public function getSortieById($sortieId)
	{
		$dbHelper = new dbhelper($this->dbConnCommunity);
		
		$sortie = $dbHelper
			->first("SELECT * from scoring_campaign_sorties WHERE sortie_id = ? LIMIT 1", [$sortieId]);	
		
		if(empty($sortie))
		{
			$this->logger->error(sprintf('Error generating story - sortie ID %d could not be found ', $sortieId));
		}		

		return $sortie;						
	}
	
	/**
	 * Retrieve the record for a single facility
	 * 
	 * @param integer $facilityId
	 * @return array|null
	 */
	public function getFacilityById($facilityId)
	{
		$dbHelper = new dbhelper($this->dbConnWWIIOnline);
		
		$facility = $dbHelper
			->first("SELECT * from strat_facility WHERE facility_oid = ? LIMIT 1", [$facilityId]);	
		
		if(empty($facility))
		{
			$this->logger->error(sprintf('Error generating story - facility ID %d could not be found ', $facilityId));
		}		

		return $facility;					
	}
	
	/**
	 * Retrieve the record for a single vehicle
	 * 
	 * @param integer $vehicleId
	 * @return array
	 */
	public function getVehicleById($vehicleId)
	{
		$dbHelper = new dbhelper($this->dbConnCommunity);
		
		$vehicle = $dbHelper
			->first("SELECT * from scoring_vehicles WHERE vehicle_id = ? LIMIT 1", [$vehicleId]);	
		
		if(empty($vehicle))
		{
			$this->logger->error(sprintf('Error generating story - vehicle ID %d could not be found ', $vehicleId));
		}		

		return $vehicle;					
	}
	
	/**
	 * Retrieve a vehicle by its classificaiton. This can be used to find a vehicle from a sortie
	 * (where the vehicle id is not directly referenced)
	 * 
	 * @param integer $countryId
	 * @param integer $categoryId
	 * @param integer $classId
	 * @param integer $typeId
	 * @return array
	 */
	public function getVehicleByClassification($countryId, $categoryId, $classId, $typeId)
	{
		$dbHelper = new dbhelper($this->dbConnCommunity);
		
		$query = $dbHelper
			->prepare("SELECT * from scoring_vehicles WHERE country_id = ? AND category_id = ? "
				. "AND class_id = ? AND type_id = ? LIMIT 1", [$countryId, $categoryId, $classId, $typeId]);	

		return $dbHelper->getAsArray($query);					
	}
	
	/**
	 * Retrieve a vehicle class detail by the id of the class
	 * 
	 * @param integer $classId
	 * @return array
	 */
	public function getClassById($classId)
	{
		$dbHelper = new dbhelper($this->dbConn);
		
		$query = $dbHelper
			->prepare("SELECT * from vehicle_classes WHERE class_id = ? LIMIT 1", [$classId]);	

		return $dbHelper->getAsArray($query);					
	}	
	
	/**
	 * Get a list and count of enemy vehicles killed in a sortie
	 * 
	 * @param integer $sortieId
	 * @return array
	 */
	public function getVehicleKillCountsForSortie($sortieId)
	{
		$dbHelper = new dbhelper($this->dbConnCommunity);
		
		$kills = $dbHelper
			->get("SELECT sck.opponent_vehicle_id, sv.name, count(kill_id) as kill_count"
				. " FROM scoring_campaign_kills sck INNER JOIN scoring_vehicles sv ON sck.opponent_vehicle_id = sv.vehicle_id"
				. " WHERE sortie_id = ?"
				, [$sortieId]);	
		
		if(count($kills) == 0)
		{
			return [];
		}
	
		return $kills;
	}	
	
	/**
	 * Retrieves an adjective describing the rate of change in RDP
	 * 
	 * @param integer $change
	 * @return string
	 */
	public function getRDPChangeAdjective($change) {

		if($change < 3){
			return 'small';
		}
		else if($change < 5){
			return 'noticable';
		}
		else if($change < 10){
			return 'sizable';
		}
		else if($change < 20){
			return 'significant';
		}

		return 'huge';
		
	}
	
	/**
	 * Retrieves a random city for a specified country
	 * 
	 * @param integer $countryId
	 * @return array
	 */
	public function getRandomCityForCountry($countryId)
	{
		$dbHelper = new dbhelper($this->dbConnWWIIOnline);
		
		$query = $dbHelper
			->prepare("SELECT * FROM strat_cp WHERE country = ? AND cp_type != 5 order by RAND() limit 1",[$countryId]);	
		
		return $dbHelper->getAsArray($query);					
	}
	
	/**
	 * Retrieves a random factory city for a specified country
	 * 
	 * @param integer $countryId
	 * @return array
	 */
	public function getRandomFactoryCityForCountry($countryId)
	{
		$dbHelper = new dbhelper($this->dbConnWWIIOnline);
		
		$query = $dbHelper
			->prepare("SELECT sc.name FROM strat_factory_outputs sfo inner join strat_facility sf on sfo.facility_oid = sf.facility_oid"
				. " INNER JOIN strat_cp sc ON  sc.cp_oid = sf.cp_oid WHERE sc.country = ? AND cp_type != 5 order by RAND() limit 1",[$countryId]);	
		
		return $dbHelper->getAsArray($query);					
	}
	
	/**
	 * Retrieves a random front line city for a country
	 * 
	 * @param integer $countryId
	 * @return array
	 */
	public function getRandomFrontlineCityForCountry($countryId)
	{
		return $this->getFrontlineCitiesForCountry($countryId, true, 1);

		/**
		$dbHelper = new dbhelper($this->dbConnWWIIOnline);
		
		$query = $dbHelper
			->prepare("select distinct c.name, distance
				from strat_link l
				INNER JOIN strat_facility f ON  l.startdepot_oid = f.facility_oid
				INNER JOIN strat_cp c ON f.cp_oid = c.cp_oid
				INNER JOIN strat_facility of ON l.enddepot_oid = of.facility_oid
				INNER JOIN strat_cp oc ON of.cp_oid = oc.cp_oid
				WHERE c.country = ? AND oc.side != c.side
				ORDER BY RAND() LIMIT 1",[$countryId]);	
		
		return $dbHelper->getAsArray($query);	

		 */				
	}
	
	/**
	 * Retrieves a random front line city for a country
	 * 
	 * @param integer $countryId
	 * @param boolean $randomOrder
	 * @param integer $limit
	 * @return array
	 */
	public function getFrontlineCitiesForCountry($countryId, $randomOrder = false, $limit = null)
	{
		$dbHelper = new dbhelper($this->dbConnWWIIOnline);
		
		$randomness = $randomOrder ? " ORDER BY RAND()" : "";
		$limitation = $limit != null ? sprintf("LIMIT %d", $limit) : "";
		
		$query = $dbHelper
			->prepare(trim(sprintf("SELECT distinct c.name, distance, oc.name as opposite_city
				FROM strat_link l
				INNER JOIN strat_facility f ON  l.startdepot_oid = f.facility_oid
				INNER JOIN strat_cp c ON f.cp_oid = c.cp_oid
				INNER JOIN strat_facility of ON l.enddepot_oid = of.facility_oid
				INNER JOIN strat_cp oc ON of.cp_oid = oc.cp_oid
				WHERE c.country = ? AND oc.side != c.side %s %s", $randomness, $limitation)),[$countryId]);	
		
		return $dbHelper->getAsArray($query);					
	}	
	
	/**
	 * Retrieves a random city for a specified country
	 * 
	 * @param integer $countryId An optional country ID to filter on
	 * @return array
	 */
	public function getRandomContestedCity($countryId = null)
	{
		$dbHelper = new dbhelper($this->dbConnWWIIOnline);
		
		$countryFilter = $countryId != null ? "AND country = ?" : "";
		$countryBindings = $countryId != null ? [$countryId] : [];
		
		$query = $dbHelper
			->prepare("SELECT * FROM strat_cp WHERE cp_type != 5 AND contention = 1 " . $countryFilter . " ORDER BY RAND() limit 1",$countryBindings);	
		
		return $dbHelper->getAsArray($query);					
	}
	
	/**
	 * Retrieves information about the enemy side of the side represented by the input side
	 * 
	 * i.e. Inputting the Id for the Allied side, will return the Axis side data
	 * 
	 * @param type $sideId
	 */
	public function getEnemySide($sideId)
	{
		/**
		 * swap the sides
		 */
		$sideId = intval($sideId) == 1 ? 2 : 1;
		
		return $this->getSide($sideId);
	}
	
	/**
	 * Will retrieve a set of information about requested side
	 * 
	 * @param integer $sideId
	 */
	public function getSide($sideId)
	{
		return self::$sideData[$sideId];
	}
	
	/**
	 * Retrieve a random enemy country
	 * 
	 * Retrieves a randomly selected country not on a specific side, or null if cannot find one
	 * 
	 * @param integer $sideId
	 * @param boolean $active if true, will choose from only active countries
	 * @return array|null
	 */
	public function getRandomEnemyCountry($sideId, $active = true)
	{
		$dbHelper = new dbhelper($this->dbConn);
		
		$sql = "select * from countries where side_id != ? ";
		if($active)
		{
			$sql .= " AND is_active = 1";
		}
		$sql .= " order by RAND() limit 1";
		
		$country = $dbHelper->first($sql,[$sideId]);
		
		return $country;		
	}
	
	/**
	 * Retrieves a random vehicle class, filtered by category if needed
	 * 
	 * @param integer $categoryId Option filter for category
	 * @return array|null
	 */
	public function getRandomVehicleClass($categoryId = null)
	{
		$dbHelper = new dbhelper($this->dbConn);
		
		$filter = $categoryId != null ? " WHERE category_id = ?" : "";
		$filterParams = $categoryId != null ? [$categoryId] : [];
		
		$query = $dbHelper
			->prepare(sprintf("select * from vehicle_classes %s order by RAND() limit 1", $filter),$filterParams);	
		
		$result = $dbHelper->getAsArray($query);
		
		return count($result) == 1 ? $result[0] : null;		
	}
	
	/**
	 * Retrieves a random intensity to assigned to a story
	 * 
	 * @return string
	 */
	public function getRandomIntensity()
	{
		return self::$intensities[rand(0, count(self::$intensities) - 1)];
	}
	
	/**
	 * Retrieve a specific country by ID
	 * 
	 * @param integer $countryId
	 * @return array|null
	 */
	public function getCountryById($countryId)
	{
		$dbHelper = new dbhelper($this->dbConn);
		
		$query = $dbHelper
			->prepare("select * from countries where story_countries.country_id = ? limit 1",[$countryId]);	
		
		$result = $dbHelper->getAsArray($query);
		
		return count($result) == 1 ? $result[0] : null;			
	}
	
	/**
	 * Retrieve a vehicle category detail by the id of the category
	 * 
	 * @param integer $categoryId
	 * @return array
	 */
	public function getCategoryById($categoryId)
	{
		$dbHelper = new dbhelper($this->dbConn);
		
		$query = $dbHelper
			->prepare("SELECT * from vehicle_categories WHERE category_id = ? LIMIT 1", [$categoryId]);	

		return $dbHelper->getAsArray($query);					
	}
	
	/**
	 * Retrieve a rank by ID
	 * 
	 * @param integer $rankId
	 * @return array
	 */
	public function getRankById($rankId)
	{
		$dbHelper = new dbhelper($this->dbConnWWIIOnline);
		
		$query = $dbHelper
			->prepare("SELECT * from wwii_rank WHERE rankid = ? LIMIT 1", [$rankId]);	

		return $dbHelper->getAsArray($query);					
	}
	
	/**
	 * Get a random vehicle for a country
	 * 
	 * @param integer $countryId
	 * @return array|null
	 */
	public function getRandomVehicle($countryId)
	{
		$dbHelper = new dbhelper($this->dbConn);

		$query = $dbHelper
			->prepare("SELECT * FROM `vehicles` WHERE `country_id` = ? ORDER BY RAND() LIMIT 1", [$countryId]);	
		
		$result = $dbHelper->getAsArray($query);
		
		return count($result) == 1 ? $result[0] : null;		
	}
	
	/**
	 * Get a list of all countries that are marked as is_active
	 * 
	 * Return an array of arrays, or an empty array if there are no active countries
	 * 
	 * @return array
	 */
	public function getActiveCountries()
	{
		$dbHelper = new dbhelper($this->dbConn);

		$query = $dbHelper
			->prepare("SELECT * FROM `countries` WHERE `is_active` = 1");	
		
		return $dbHelper->getAsArray($query);
	}
	
	/**
	 * Is this story about a specific player?
	 * 
	 * @return boolean
	 */
	public function isPlayerCentric()
	{
		return $this->isPlayerCentric;
	}
	
	/**
	 * Get the player id associated with the story, if any
	 * @return integer|null
	 */
	public function getProtagonistId()
	{
		return $this->protagonistId;
	}
	
	/**
	 * Generates the HTML content for the player email from file
	 * 
	 * @param array $template
	 * @return string
	 */
	public function generateHtmlContent($template, array $extraData = []) 
	{
		$story = $this->makeStory($template);
		
		$emailTemplate = file_get_contents(__DIR__ . '/../../templates/email/player-story.html');
		
		$emailTemplate = str_replace('%TITLE%', $story['title'], $emailTemplate);
		$emailTemplate = str_replace('%STORY_CONTENT%', $story['body'], $emailTemplate);
		
		foreach($extraData as $key => $value)
		{
			$emailTemplate = str_replace('%' . strtoupper($key) . '%', trim($value), $emailTemplate);
		}		

		return $emailTemplate;

	}
	
	/**
	 * Generates the text content for the player email from a file
	 * 
	 * @param array $template
	 * @return string
	 */
	public function generateTextContent($template, array $extraData = []) 
	{
		// Strip out the HTML where we can.
		$story = $this->makeStory($template);
		
		$emailTemplate = file_get_contents(__DIR__ . '/../../templates/email/player-story.txt');
		
		$emailTemplate = str_replace('%TITLE%', strip_tags($story['title']), $emailTemplate);
		$emailTemplate = str_replace('%STORY_CONTENT%', strip_tags($story['body']), $emailTemplate);
		foreach($extraData as $key => $value)
		{
			$emailTemplate = str_replace('%' . strtoupper($key) . '%', strip_tags(trim($value)), $emailTemplate);
		}
		
		return $emailTemplate;

	}
	
	/**
	 * Extracts all the placeholders from a template into a unique array
	 * 
	 * @param array $template
	 * @return array
	 */
	protected function getTemplatePlaceholders(array $template)
	{
		$title = null; $body = null;
		$variety1 = null; $variety2 = null;
		preg_match_all("/%([A-Z0-9_]+)%/", $template['title'], $title);
		preg_match_all("/%([A-Z0-9_]+)%/", $template['body'], $body);
		preg_match_all("/%([A-Z0-9_]+)%/", $template['variety_1'], $variety1);
		preg_match_all("/%([A-Z0-9_]+)%/", $template['variety_2'], $variety2);
		
		return array_unique(array_merge($title[1], $body[1], $variety1[1], $variety2[1]));
	}
	
	/**
	 * Takes the placeholders that are in the template, and compates them to the template
	 * vars generated by the story. Reports on any discrepancies and returns an array of the 
	 * placeholders that are missing
	 * 
	 * @param array $template
	 * @param array $templateVars
	 * @return array
	 */
	protected function comparePlaceholders(array $template, array $templateVars)
	{
		$placeholders = $this->getTemplatePlaceholders($template);
		
		$this->logger->debug("Template placeholders are : " . implode(",", $placeholders));
		
		$tvars = array_change_key_case($templateVars, CASE_UPPER);
	
		$this->logger->debug("Story Vars are : " . implode(",", array_keys($tvars)));
		
		$missingPlaceholders = array_diff($placeholders, array_keys($tvars));
		if(count($missingPlaceholders) > 0)
		{
			$this->logger->warn("Missing Placeholders in Template Vars: " . implode(",", $missingPlaceholders));
		}
		
		return $missingPlaceholders;
	}
}