<?php
/**
 * Processes stories and generates new ones for the Gazette
 * 
 * - How stories are chosen.
 * 
 *
 * @author Jason Rout
 */

require_once(__DIR__ . "/../include/dbhelper.php");
require_once(__DIR__ . "/stories/require.php");

class StoryProcessor {
	
	/**
	 * Regular connection to the gazette DB
	 * @var resource 
	 */
	protected $dbConn;
	
	/**
	 * Connection ot the wwiionline db (game db)
	 * @var resource 
	 */
	protected $dbConnWWIIOnline;

	/**
	 * Database helper
	 * @var dbhelper 
	 */
	protected $dbHelper;
	
	public function __construct($dbConn, $dbConnWWIIOnline) {
	
		$this->dbConn = $dbConn;
		$this->dbConnWWIIOnline = $dbConnWWIIOnline;
		$this->dbHelper = new dbhelper($this->dbConn);
	}
	
	/**
	 * Process a specific area of a specific page
	 * 
	 * @param string $storyKey
	 * @param integer $sourceId The ID of a specific source to draw from
	 * @return boolean true if a story could be made
	 */
	public function process($storyKey, $sourceId = null)
	{
		
		$storyQuery = $this->dbHelper
			->prepare("SELECT * FROM `stories` WHERE `story_key` = ?", [$storyKey]);
		
		$storyData = $this->dbHelper->getAsArray($storyQuery);
		if(count($storyData) !== 1)
		{
			echo sprintf('Could not find a story with key %s - skipping', $storyKey);
			return false;
		}
		
		$storyData = $storyData[0];
		$storyId = $storyData['story_id'];

		$storyTypes = $this->getTypeData($storyId);
		if(count($storyTypes) == 0)
		{
			echo sprintf('There are no story types for key %s - skipping', $storyKey);
			return false;			
		}

		if(($story = $this->checkStoryTypes($storyTypes, $sourceId)) === false)
		{		
			/**
			 * No valid story could be found for this section of the page, so use
			 * the "alt" value instead
			 */
			$this->pushToFile($storyKey, $storyData['alt']);
			
			echo sprintf("\tNo stories could be made for %s! - using story alt\n", $storyKey);
			return false;
		}
		
		$content = $this->parseTemplate($storyData['template'], $story);
		
		$this->pushToFile($storyKey, $content);
		echo sprintf("\tStory could be made for %s!\n", $storyKey);
		
		/**
		 * Update the expiry date on the story
		 */
		return true;
	}
	
	/**
	 * Check each story type and see if a story can be generated for that country
	 * 
	 * @param array $storyTypes
	 * @param integer $sourceId
	 * @return array|false	Returns title and body of the story, or false if no story found
	 */
	private function checkStoryTypes($storyTypes, $sourceId = null)
	{
		
		foreach($storyTypes as $storyType)
		{
			if($content = $this->prepareStoryType($storyType, $sourceId))
			{
				return $content;
			}			
		}
		
		return false;
	}
	
	/**
	 * 
	 * @param type $storyType
	 * @param type $sourceId
	 * @return boolean
	 */
	private function prepareStoryType($storyType, $sourceId)
	{
		$creatorData = [
			'template_vars' => [
				'country' => $storyType['country'],
				'side' => $storyType['side'],
				'country_adjective' => $storyType['country_adj']
			]
		];			

		/**
		 * Pull all the possible sources for a specific story type
		 * 
		 * e.g. for index_main_headline, the single story type is "Headline News" in the types table
		 * We then look in the sources table for all sources of that type to get the weighting of that
		 * story and how long it lasts
		 */
		$countryId = $storyType['country_id'];
		$typeId = $storyType['type_id'];

		$creatorData['side_id'] = strtolower($storyType['side_id']);
		$creatorData['country_id'] = $countryId;

		/**
		 * If source ID is not null, we have asked for specific source of 
		 * the story, which narrows the templates down
		 */
		if($sourceId !== null)
		{
			$sourceData = $this->getStorySource($sourceId, $countryId);
		}
		else {
			$sourceData = $this->getSourceData($typeId, $countryId);
			$sourceId = $sourceData[0]['source_id'];			
		}

		/**
		 * Here we group the templates by their weight. This allows to ensure the more
		 * important stories float to the top of options available
		 */
		$organisedSources = $this->organiseSources($sourceData);
		$weights = $this->getWeights($storyType['weighting'], array_keys($organisedSources));

		/**
		 * Go through each weight form most to least likely
		 */
		foreach($weights as $weight)
		{
			/**
			 * Go through each possible source of a story of that weight and check to see
			 * if the data that makes the story valid true. If so, we have
			 * found our story - generate it and return
			 */
			foreach($organisedSources[$weight] as $source)
			{
				if(($content = $this->processSource($source, $creatorData)) !== false) {
					return $content;
				}
			}				
		}
		
		return false;
	}
	
	/**
	 * Checks that the source template (see templates table) is valid
	 * 
	 * @param array $source
	 * @param array $creatorData
	 * @return array|false
	 */
	private function processSource($source, $creatorData) {

		$sourceName = $source['name'];
		$creatorData['title_template'] = $source['title'];
		$creatorData['body_template'] = $source['body'];

		$storyCreatorClass = "Story" . str_replace(" ", "", $sourceName);

		/**
		 * If it doesn't exists as a story to generate, skip it
		 */
		if(!class_exists($storyCreatorClass))
		{
			echo "\tNo story class has been defined for $storyCreatorClass - skipping...\n";
			return false;
		}

		/* @var $storyCreator StoryInterface */
		$storyCreator = new $storyCreatorClass($this->dbConn, $this->dbConnWWIIOnline, $creatorData);
		echo sprintf("\tChecking story %s for country %s with template %d" , $storyCreatorClass, $creatorData['template_vars']['country'], $source['template_id']);

		if($storyCreator->isValid())
		{
			echo " -- valid\n";
			return $storyCreator->makeStory();
		}
		else
		{
			echo "-- not valid\n";
			return false;
		}
		
	}
	
	/**
	 * Retrieves the valid sources and their related templates for a story
	 * 
	 * @param type $sourceId
	 * @param type $countryId
	 * @return type
	 */
	private function getStorySource($sourceId, $countryId)
	{
		$query = $this->dbHelper
		->prepare("SELECT s.source_id,s.name, s.weight,s.life,t.template_id,t.title,t.body,t.variety_1,t.variety_2,t.duplicates,tc.country_id "
			." FROM sources AS s "
			." INNER JOIN template_sources AS ts ON s.source_id = ts.source_id"
			." INNER JOIN templates AS t ON ts.template_id = t.template_id "
			." INNER JOIN template_countries AS tc ON t.template_id = tc.template_id"
			." WHERE s.source_id = ? AND tc.country_id = ? LIMIT 1", [$sourceId, $countryId]);

		return $this->dbHelper->getAsArray($query);		
	}	

	
	/**
	 * Retrieves all the story types associated with story
	 * 
	 * @param integer $storyId
	 * @return array
	 */
	private function getTypeData($storyId)
	{
		/**
		 * Load in the story types for this story, we may have multiple types, for each country
		 * 
		 */
		$query = $this->dbHelper
			->prepare("SELECT t.*, c.country_id,c.side_id,c.name as country,c.side,c.adjective as country_adj 
			FROM stories s 
			INNER JOIN story_types st ON s.story_id = st.story_id
			INNER JOIN types t ON st.type_id = t.type_id
			INNER JOIN story_countries sc ON s.story_id = sc.story_id
			INNER JOIN countries c ON sc.country_id = c.country_id
			WHERE s.story_id = ? order by RAND()", [$storyId]);	
		
		return $this->dbHelper->getAsArray($query);
	}
	
	/**
	 * Retrieves the sources for all the story types used by a story filtered by the country
	 * @param integer $storyId
	 * @param integer $countryId
	 * @return array
	 */
	private function getSourceData($storyId, $countryId)
	{
		$query = $this->dbHelper
		->prepare("SELECT s.source_id,s.name, s.weight,s.life,t.template_id,t.title,t.body,t.variety_1,t.variety_2,t.duplicates,tc.country_id "
			." FROM sources AS s "
			." INNER JOIN template_sources AS ts ON s.source_id = ts.source_id"
			." INNER JOIN templates AS t ON ts.template_id = t.template_id "
			." INNER JOIN template_countries AS tc ON t.template_id = tc.template_id"
			." WHERE s.type_id = ? AND tc.country_id = ? ORDER BY RAND()", [$storyId, $countryId]);

		return $this->dbHelper->getAsArray($query);		
	}
	
	/**
	 * Sorts the sources into an array based on their weighting
	 * @param array $sourceData
	 * @return array
	 */
	private function organiseSources($sourceData)
	{
		$organised = [];
		foreach($sourceData as $source)
		{
			$organised[$source['weight']][] = $source;
		}
		return $organised;
	}
	
	/**
	 * Modidy the weights to be in a randomised order
	 * @param type $weights
	 * @return type
	 */
	private function modifyWeightings($weights)
	{
		shuffle($weights);
		
		return $weights;
		/*
		$sorted = [];
		
		foreach($weights as $weight ) {
		
			while(($random = rand(1, 100)) > $weight)
			{
				$random = rand(1, 100);
			}
		
			$sorted[$weight] = $random;
		
		}
		asort($sorted);
		return $sorted;
		//return sort {$sorted{$b} <=> $sorted{$a}} (keys(%sorted));	
		 * 
		 */	
	}
	
	/**
	 * Calculate the weightings of the templates
	 * 
	 * @param type $weighting
	 * @param type $keys
	 * @return type
	 */
	private function getWeights($weighting, $keys)
	{
		if(strtolower($weighting) == 'absolute') {
			/**
			 * An absolute weighting for story(currently only for headlines)
			 * means that the templates will be chosen strictly on their weight
			 */
			sort($keys);
			$weights = array_reverse($keys);
		}
		else
		{
			/**
			 * Otherwise we add a random factor to the weightings
			 */
			$weights = $this->modifyWeightings($keys);
		}
		
		return $weights;
	}
	
	private function parseTemplate($template, $data)
	{
		foreach($data as $key => $value)
		{
			$template = str_replace('%' . strtoupper($key) . '%', $value, $template);
		}
		
		return $template;
	}
	
	private function pushToFile($storyKey, $storyData)
	{
		$cacheFile = __DIR__ . '/../cache/' . $storyKey . '.php';
		
		file_put_contents($cacheFile, $storyData);
	}
}
