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
	
	protected $dbConn;
	protected $dbConnWWIIOnline;


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
			/**
			 * Pull all the possible sources for a specific story type
			 * 
			 * e.g. for index_main_headline, the single story type is "Headline News" in the types table
			 * We then look in the sources table for all sources of that type to get the weighting of that
			 * story and how long it lasts
			 */

			$countryId = $storyType['country_id'];
			$typeId = $storyType['type_id'];
			$sideId = strtolower($storyType['side_id']);
			
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
			$organisedTemplates = $this->organiseTemplates($sourceData);
			$weights = $this->getWeights($storyType['weighting'], array_keys($organisedTemplates));

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
				foreach($organisedTemplates[$weight] as $template)
				{
					$sourceName = $template['name'];

					$storyCreatorClass = "Story" . str_replace(" ", "", $sourceName);
					
					/**
					 * If it doesn't exists as a story to generate, skip it
					 */
					if(!class_exists($storyCreatorClass))
					{
						echo "\tNo story class has been defined for $storyCreatorClass - skipping...\n";
						continue;
					}
					
					/** 
					 * Bit of a hack - puts side into the template data
					 * @todo Figure out a better data structure to passed into the
					 */
					$creatorData = [
						'side_id'	=> $sideId,
						'template' => $template
					];

					/* @var $storyCreator StoryInterface */
					$storyCreator = new $storyCreatorClass($this->dbConn, $this->dbConnWWIIOnline, $creatorData);
					echo "\tChecking story $storyCreatorClass for country $countryId";
					
					if($storyCreator->isValid())
					{
						echo " -- valid\n";
						$storyCreator->makeStory();
						
						return [
							'title' => $storyCreator->title,
							'body' => $storyCreator->body
						];
					}
					else
					{
						echo "-- not valid\n";
					}
				}				
			}
		}
		
		return false;
	}
	
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
	 * Sorts the templates into an array based on their weighting
	 * @param array $templateData
	 * @return array
	 */
	private function organiseTemplates($templateData)
	{
		$organised = [];
		foreach($templateData as $template)
		{
			$organised[$template['weight']][] = $template;
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
