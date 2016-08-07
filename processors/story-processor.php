<?php
/**
 * copyright Playnet 2016
 * 
 * Processes stories and generates new ones for the Gazette
 * 
 * - How stories are chosen.
 * 
 * @author Jason Rout
 */

require_once(__DIR__ . "/../include/dbhelper.php");
require_once(__DIR__ . "/stories/require.php");

use Playnet\WwiiOnline\Common\PlayerMail\Message;
use Playnet\WwiiOnline\Common\PlayerMail\MessagePlayer;
use Playnet\WwiiOnline\WwiiOnline\Models\Side\Allied\Allied;
use Playnet\WwiiOnline\WwiiOnline\Models\Side\Axis\Axis;
use Playnet\WwiiOnline\Common\PlayerMail\NotificationManager;

class StoryProcessor {
	
	/**
	 * Regular connection to the gazette DB
	 * @var resource 
	 */
	protected $dbConn;
	
	/**
	 * Connection to the wwii db (kills db)
	 * @var resource 
	 */
	protected $dbConnWWII;	
	
	/**
	 * Connection to the wwiionline db (game db)
	 * @var resource 
	 */
	protected $dbConnWWIIOnline;
	
	/**
	 * Connection to the TOE database
	 * @var resource 
	 */
	protected $dbConnToe;

	/**
	 * Database helper
	 * @var dbhelper 
	 */
	protected $dbHelper;
	
	/**
	 * A loaded array of the countries contributing to the campaign at the current time
	 * @var array 
	 */
	private $activeCountries;
	
	/**
	 * An array of country keys, each with an array of story ids that the country can use
	 * @var array 
	 */
	private $countryStories = [];
	
	/**
	 * An array of the database connections the story generation can use
	 * 
	 * @var array
	 */
	private $dbConnections;
	
	/**
	 *
	 * @var NotificationManager
	 */
	private $notificationManager;
	
	public function __construct(NotificationManager $notificationManager, array $dbConnections = array()) 
	{
		$this->dbConnections = $dbConnections;
		$this->dbConn = $dbConnections['dbConn'];
		$this->dbConnWWII = $dbConnections['dbConnWWII'];
		$this->dbConnWWIIOnline = $dbConnections['dbConnWWIIOnline'];
		$this->dbConnToe = $dbConnections['dbConnToe'];
		
		$this->dbHelper = new dbhelper($this->dbConn);
		$this->notificationManager = $notificationManager;

		$this->init();
	}
	
	/**
	 * Process a specific area of a specific page
	 * 
	 * @param string $storyKey
	 * @param array $options Options for generating the stories
	 * @return boolean true if a story could be made
	 */
	public function process($storyKey, $options)
	{
		$sourceId	= $options['sourceId'];
		$templateId = $options['templateId'];
		$reportOnly = $options['reportOnly'];
	
		/**
		if($this->isIntermission())
		{
			$this->generateIntermissionStories();
			return false;
		}
		**/
		
		if(($storyData = $this->getStoryDataForKey($storyKey)) === false)
		{
			return false;
		}

		if(($story = $this->checkStory($storyData, $sourceId, $templateId)) === false)
		{	
			echo sprintf("*** No stories could be made for %s! - using story alt***\n", $storyKey);

			/**
			 * No valid story could be found for this section of the page, so use
			 * the "alt" value instead
			 */
			$this->outputStory($storyKey, $storyData['alt'], $reportOnly);
			
			return false;
		}
		
		$content = $this->parseTemplate($storyData['template'], $story['story'], $storyData['story_format']);
		
		/**
		* Add some debug content
		*/
		$content .= sprintf("<!-- StoryKey: %s SourceID : %s TemplateID : %s -->", 
			$storyKey, $story['debug_data']['source_id'], $story['debug_data']['template_id']); 
		
		$this->outputStory($storyKey, $content, $reportOnly);
		
		/**
		 * Update the expiry date on the story
		 */
		return true;
	}
	
	/**
	 * 
	 * @param arra $storyData
	 * @param integer $sourceId
	 * @param integer $templateId
	 * @return array
	 */
	private function checkStory($storyData, $sourceId, $templateId)
	{
		$storyKey = $storyData['story_key'];
		$storyId= $storyData['story_id'];
		
		$storyTypes = $this->getTypeData($storyId);
		if(count($storyTypes) == 0)
		{
			echo sprintf("There are no story types for key %s - skipping\n", $storyKey);
			return false;			
		}
		
		return $this->checkStoryTypes($storyData, $storyTypes, $sourceId, $templateId);
	}
	
	/**
	 * Check each story type and see if a story can be generated for that country
	 * 
	 * @param array $storyData
	 * @param array $storyTypes
	 * @param integer $sourceId
	 * @param integer $templateId
	 * @return string|false	Returns the content of the story, or false if no story found
	 */
	private function checkStoryTypes($storyData, $storyTypes, $sourceId = null, $templateId = null)
	{
		echo sprintf("    Story has %d storytypes\n", count($storyTypes));
		
		foreach($storyTypes as $storyType)
		{
			echo sprintf("    Checking Story Type %s\n", $storyType['name']);
			
			if(($content = $this->prepareStory($storyData, $storyType, $sourceId, $templateId)))
			{
				return $content;
			}			
		}
		
		return false;
	}
	
	/**
	 * 
	 * @param array $storyData
	 * @param array $storyType
	 * @param integer $sourceId
	 * @param integer $templateId
	 * 
	 * @return boolean
	 */
	private function prepareStory($storyData, $storyType, $sourceId, $templateId)
	{		
		$typeId = $storyType['type_id'];

		/**
		 * If source ID is not null, we have asked for specific source of 
		 * the story, which narrows the templates down
		 */
		if($sourceId !== null)
		{
			$sourceData = $this->getStorySource($sourceId);
		}
		else {
			/**
			 * Pull all the possible sources for a specific story type
			 * 
			 * e.g. for index_main_headline, the single story type is "Headline News" in the types table
			 * We then look in the sources table for all sources of that type to get the weighting of that
			 * story and how long it lasts
			 */			
			$sourceData = $this->getSourcesForType($typeId);
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
				if($source['type_id'] != $typeId)
				{
					echo sprintf("  source type mismatch %s : %s\n", $source['type_id'], $typeId);
					continue;
				}
				
				if(($content = $this->processSource($storyData, $source, $templateId)) !== false) {
					return $content;
				}
			}				
		}
		
		return false;
	}
	
	/**
	 * Checks that the source template (see templates table) is valid
	 * 
	 * @param array $storyData
	 * @param array $source
	 * @param array $creatorData
	 * @param integer $templateId
	 * @return array|false
	 */
	private function processSource($storyData, $source, $templateId) 
	{
		$activeCountries = $this->activeCountries;
		
		/**
		 * Randomise the order of the countries so that the perspective of the story is
		 * changed for this type
		 */
		shuffle($activeCountries);

		/**
		 * Go through each active country and see if the source story is true
		 */
		foreach($activeCountries as $activeCountry)
		{			
			echo sprintf("        Checking source %s on country %s", $source['name'], $activeCountry['name']);			
			
			// If the country has no stories, go to the next country
			if(!isset($this->countryStories[$activeCountry['country_id']]))
			{
				echo sprintf(" -- No stories for %s\n", $activeCountry['name']);
				
				continue;
			}
			echo "\n";
			
			// Check to see if the current story can be used by the active country
			// COMMDEV-808
			if(!in_array($storyData['story_id'], $this->countryStories[$activeCountry['country_id']]))
			{
				echo sprintf(" -- Story %d is not available for %s\n", $storyData['story_id'], $activeCountry['name']);				
				continue;				
			}
			
			if(($result = $this->processSourceForCountry($storyData, $source, $activeCountry, $templateId)) !== false)
			{
				return $result;
			}
		}
		
		return false;
	}
	
	/**
	 * Processes a specific source for the currently active country
	 * 
	 * Returns the content of the story, or false if no content could be generated
	 * 
	 * @param array $storyData
	 * @param array $source
	 * @param array $activeCountry
	 * @param integer $templateId
	 * @return string|false
	 */
	public function processSourceForCountry($storyData, $source, $activeCountry, $templateId)
	{
		$creatorData = [
			'template_vars' => [
				'country' => $activeCountry['name'],
				'side' => $activeCountry['side'],
				'side_adj' => $activeCountry['side'] == Allied::getSideKey() ? Axis::getSideAdjective() : Allied::getSideAdjective(),
				'enemy_side' => strtolower($activeCountry['side']) == Allied::getSideKey() ? Axis::getSideName() : Allied::getSideName(),
				'enemy_side_adj' => strtolower($activeCountry['side']) == Allied::getSideKey() ? Axis::getSideAdjective() : Allied::getSideAdjective(),
				'country_adj' => $activeCountry['adjective'],
				'side_id' => $activeCountry['side_id'],
				'country_id' => $activeCountry['country_id'],				
			],
			'side_id' => $activeCountry['side_id'],
			'country_id' => $activeCountry['country_id']
		];			
		
		/* @var $sourceName string */
		$sourceName = $source['name'];
		
		/* @var $pageId integer */
		$pageId = $storyData['page_id'];
		
		/* @var $storyId integer */
		$storyId = $storyData['story_id'];

		$storyCreatorClass = "Story" . str_replace(" ", "", $sourceName);

		/**
		 * If it doesn't exists as a story to generate, skip it
		 */
		if(!class_exists($storyCreatorClass))
		{
			echo "          No story class has been defined for $storyCreatorClass - skipping...\n";
			return false;
		}

		/* @var $storyCreator StoryInterface */
		$storyCreator = new $storyCreatorClass($creatorData, $this->notificationManager->getHandler(), $this->dbConnections);
		echo sprintf("          Checking story %s\n" , $storyCreatorClass);

		if($storyCreator->isValid())
		{
			if($templateId !== null)
			{
				$template = $this->getTemplateById($templateId);
			}
			else
			{
				$template = $this->getRandomTemplateForSource($source['source_id'], $creatorData['country_id'], $pageId, $storyId);
			}
			
			if($template == null)
			{
				echo sprintf("            ** No valid templates for source %s**\n" , $sourceName);
				return false;
			}
			
			echo sprintf("            Using Template %s\n" , $template['template_id']);
			
			/**
			 * Content is an array of [title, body]
			 */
			$content['story'] =  $storyCreator->makeStory($template);
			$content['debug_data'] = ['source_id' => $source['source_id'], 'template_id' => $template['template_id']];
			
			$this->updateStory($storyData['story_key'], $source['life'], $template['template_id']);
			
			$this->checkPlayerStory($storyCreator, $template);
			
			return $content;
		}
		else
		{
			return false;
		}		
	}
	
	/**
	 * Updates the story to set the expiry date
	 * 
	 * @param string $storyKey the story_key in the stories table
	 * @param integer $lifetime the number of minutes to add to NOW for expiry date of this story.
	 * @param integer $templateId The last templateId used to populate this story. 
	 */
	public function updateStory($storyKey, $lifetime, $templateId)
	{
		$query = $this->dbHelper
		->prepare("UPDATE `stories` SET `expire` = 0, `expires` = DATE_ADD(NOW(), INTERVAL ? second), "
			. "used_id = ? WHERE `story_key` = ? LIMIT 1", [$lifetime * 60, $templateId, $storyKey]);
		
		$query->execute();
	}
	
	/**
	 * Forces the story to be expired so it is regenerated next run
	 * 
	 * @param string $storyKey the story_key in the stories table
	 */
	public function forceStoryExpiry($storyKey)
	{
		$query = $this->dbHelper
		->prepare("UPDATE `stories` SET `expire` = 1, `expires` = NOW() WHERE `story_key` = ? LIMIT 1", [$storyKey]);
		
		$query->execute();
	}	
	
	/**
	 * Retrieves the valid sources and their related templates for a story
	 * 
	 * @param integer $sourceId
	 * @return array
	 */
	private function getStorySource($sourceId)
	{
		return $this->dbHelper
			->get("SELECT s.source_id, s.type_id, s.name, s.weight, s.life FROM `sources` as s WHERE s.source_id = ? limit 1", [$sourceId]);
	
	}
	
	/**
	 * Retrieves a random template for a specific story source, relative to the country
	 * 
	 * @param integer $sourceId
	 * @param integer $countryId
	 * @return array|null
	 */
	private function getRandomTemplateForSource($sourceId, $countryId, $pageId, $storyId)
	{
		$query = $this->dbHelper
		->prepare("SELECT t.template_id,t.title,t.body,t.variety_1,t.variety_2,t.duplicates,tc.country_id "
			." FROM template_sources AS ts"
			." INNER JOIN templates AS t ON ts.template_id = t.template_id "
			." INNER JOIN template_countries AS tc ON t.template_id = tc.template_id"
			." WHERE ts.source_id = ? AND tc.country_id = ? ORDER BY RAND()", [$sourceId, $countryId]);

		$templates = $this->dbHelper->getAsArray($query);
		
		echo sprintf("            There are %d templates for source %d, country %d\n",count($templates), $sourceId, $countryId);		
		
		/**
		 * Go through every returned template. If we find one that allows duplicates, accept it.
		 * 
		 * If a template is disallowed, move onto the next until you either find one that hasn't
		 * been used on the same page, or run out of templates
		 */
		
		foreach($templates as $template)
		{
			if(strtolower($template['duplicates']) == 'allow') {
				return $template;
			}

			if(!$this->checkIfTemplateDuplicatedOnPage($pageId, $storyId, $template['template_id']))
			{
				echo sprintf("            Template %d ok\n",$template['template_id']); 
				return $template;
			}
			
			echo sprintf("            Template %d is already duplicated on page %s\n",$template['template_id'], $pageId);
		}
		
		return null;
	}	

	/**
	 * Retrieves a specific template by Id, or null if not found
	 * 
	 * @param integer $templateId
	 * @return array|null
	 */
	private function getTemplateById($templateId)
	{
		$query = $this->dbHelper
		->prepare("SELECT t.template_id,t.title,t.body,t.variety_1,t.variety_2,t.duplicates,tc.country_id "
			." FROM template_sources AS ts"
			." INNER JOIN templates AS t ON ts.template_id = t.template_id "
			." INNER JOIN template_countries AS tc ON t.template_id = tc.template_id"
			." WHERE t.template_id = ? LIMIT 1", [$templateId]);

		$result = $this->dbHelper->getAsArray($query);
		
		return count($result) > 0 ? $result[0] : null;
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
			->prepare("SELECT t.* 
			FROM story_types st
			INNER JOIN types t ON st.type_id = t.type_id
			WHERE st.story_id = ?", [$storyId]);	
		
		return $this->dbHelper->getAsArray($query);
	}
	
	/**
	 * Retrieve all the story sources related to a given story type
	 * @param integer $typeId
	 * @return array
	 */
	private function getSourcesForType($typeId)
	{
		$query = $this->dbHelper
		->prepare("SELECT s.source_id, s.type_id, s.name, s.weight, s.life"
			." FROM sources AS s "
			." WHERE s.type_id = ? ORDER BY RAND()", [$typeId]);

		return $this->dbHelper->getAsArray($query);		
	}	
	
	/**
	 * Retrieves the sources for all the story types used by a story filtered by the country
	 * @param integer $storyId
	 * @param integer $countryId
	 * @return array
	 * 
	 * @deprecated since version 1.0.0
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
	
	/**
	 * Takes a template and replaces placeholders with the values from key/value pairs in the data attribute
	 * 
	 * Returns an array of title and body elements containing the text of the stories
	 * 
	 * @param array $template
	 * @param array $data
	 * @param string $storyFormat
	 * @return array
	 */
	private function parseTemplate($template, $data, $storyFormat)
	{
		/**
		 * Stripe extra whitespace
		 */
		$output = trim($template);
		
		switch($storyFormat)
		{
			case StoryBase::STORY_FORMAT_HEADLINE : {
				$output = $this->parseHeadlineStoryFormat($output, $data);
				break;
			}
			default : {
				// StoryBase::STORY_FORMAT_DEFAULT
				$output = $this->parseDefaultStoryFormat($output, $data);
				break; 
			}
		}
		
		return $output;
	}
	
	/**
	 * Checks the story to confirm it is about a player and prepares to send the
	 * email to that player
	 * 
	 * @param StoryBase $storyCreator
	 * @param array $template
	 * @return void
	 */
	public function checkPlayerStory(StoryBase $storyCreator, $template)
	{
		if($storyCreator->isPlayerCentric())
		{
			// There is only a protagonist if the story is player centric, and we check to see if they
			// are already unsubscribed
			if(!$this->notificationManager->isPlayerUnsubscribed($storyCreator->getProtagonistId()))
			{			
				// Get the player that is to be sent this story
				$player = $this->notificationManager->getPlayer($storyCreator->getProtagonistId());
				
				// if the player is empty, add them as a new record
				if(empty($player))
				{
					$player = $this->notificationManager->addPlayer($storyCreator->getProtagonistId());
				}
				else if(!$this->notificationManager->canSendNotification($storyCreator->getProtagonistId()))
				{
					// Not allowed to send a notification because of whatever factor (see function for reasons)
					return;
				}

				$extraData = [
					'assets_base_url' => $this->notificationManager->getHandler()->getOption('assets_base_url')
				]; 

				$message = new Message();
				
				// Generate the unsubscribe link and place it in out content
				// TODO: If ever we end up with a solution to generate the unsub link on the API side, this will need to change
				$unsubscribeLink = $this->notificationManager->getUnsubscribeLink($storyCreator->getProtagonistId());

				$content = str_replace('%UNSUBSCRIBE%', $unsubscribeLink, $storyCreator->generateHtmlContent($template, $extraData));
				$textContent = str_replace('%UNSUBSCRIBE%', $unsubscribeLink, $storyCreator->generateTextContent($template, $extraData));
				$message->setContent($content);
				$message->setTextContent($textContent);
				$message->addPlayer(new MessagePlayer($storyCreator->getProtagonistId()));

				$this->notificationManager->getHandler()->addMessage($message);
				
				// Ensure we don't send another email for defined period of time
				$this->notificationManager->updateLastSend($storyCreator->getProtagonistId());
			}

		}
	}

	
	/**
	 * Parse and return data generated for headlines
	 * 
	 * @param string $template
	 * @param array $data
	 */
	private function parseHeadlineStoryFormat($template, $data)
	{
		$tag = 'h1';
		return str_replace('%TITLE%', sprintf("<%s>%s</%s>\n", $tag, $data['title'], $tag), $template);
	}	
	
	
	private function parseTableStoryFormat($template, $data)
	{		
		$tag = 'h3';
		$template =  str_replace('%TITLE%', sprintf("<%s>%s</%s>\n", $tag, $data['title'], $tag), $template);
		
		$tag = 'div';
		return str_replace('%TITLE%', sprintf("<%s>%s</%s>\n", $tag, $data['body'], $tag), $template);
		
	}
	
	private function parseDefaultStoryFormat($template, $data)
	{
		$tag = 'h3';
		$template =  str_replace('%TITLE%', sprintf("<%s>%s</%s>\n", $tag, $data['title'], $tag), $template);
		
		$tag = 'div';
		return str_replace('%BODY%', sprintf("<%s>%s</%s>\n", $tag, $data['body'], $tag), $template);		
	}	
	
	/**
	 * Push a file to the cache directory in the file system
	 * 
	 * @param string $storyKey The key name(see storyKey in stories table) where to save the cache file
	 * @param stringe $storyData The string to record to the file
	 */
	private function pushToFile($storyKey, $storyData)
	{
		$cacheFile = __DIR__ . '/../cache/' . $storyKey . '.php';
		
		file_put_contents($cacheFile, $storyData);
	}
	
	
	/**
	 * Retrieves whether a story on a page has used the same template as another on the same page
	 * 
	 * @param integer $pageId
	 * @param integer $storyId
	 * @param integer $templateId
	 * @return boolean
	 */
	public function checkIfTemplateDuplicatedOnPage($pageId, $storyId, $templateId) {
	
		$dbHelper = new dbhelper($this->dbConn);

		$query = $dbHelper
			->prepare("SELECT count(*) as used_count FROM stories WHERE page_id = ? and story_id != ? and used_id = ?", 
				[$pageId, $storyId, $templateId]);	
		
		$result = $dbHelper->getAsArray($query);

		return $result[0]['used_count'] > 0;
	}
	
	/**
	 * Initialise internal data arrays
	 * 
	 * @return array
	 */
	private function init()
	{
		/**
		 * Load in the active countries. Stories will only be generated for those countries
		 */
		$dbHelper = new dbhelper($this->dbConn);
		
		$query = $dbHelper
			->prepare("SELECT * FROM `countries` WHERE `is_active` = 1");	
		
		$this->activeCountries = $dbHelper->getAsArray($query);	
		
		/**
		 * Load in all the story ids that are relevant to active country
		 */
		$activeIds = [];
		foreach ($this->activeCountries as $country)
		{
			$activeIds[] = $country['country_id'];
		}
		
		if(count($activeIds) == 0)
		{
			return;
		}
		
		$query2 = $dbHelper
			->prepare(sprintf("SELECT * FROM `story_countries` WHERE `country_id` in (%s)", join(',', $activeIds)));
		
		$results = $dbHelper->getAsArray($query2);
		
		foreach($results as $result)
		{
			$this->countryStories[$result['country_id']][] = $result['story_id'];
		}
	
	}
	
	/**
	 * Retrieve a single story based on the key.
	 * 
	 * Returns an array of the story data, or false if no story could be found
	 * @param string $storyKey
	 * @return array|false
	 */
	private function getStoryDataForKey($storyKey)
	{
		echo sprintf("Checking page area %s\n", $storyKey);
		
		$storyQuery = $this->dbHelper
			->prepare("SELECT s.*, sf.`key` as `story_format` FROM `stories` AS s INNER JOIN `story_formats` AS sf ON s.`story_format_id` = sf.`id` "
				. "WHERE `story_key` = ?", [$storyKey]);
		
		$storyData = $this->dbHelper->getAsArray($storyQuery);
		if(count($storyData) !== 1)
		{
			echo sprintf("Could not find a story with key %s - skipping\n", $storyKey);
			return false;
		}
		
		return $storyData[0];		
	}
	
	/**
	 * Output the story
	 * 
	 * Outputs the story either to a file, or to the console
	 * 
	 * @param string $storyKey
	 * @param string $storyContent
	 * @param boolean $reportOnly
	 * @return void
	 */
	private function outputStory($storyKey, $storyContent, $reportOnly)
	{
		if(!$reportOnly) {
			$this->pushToFile($storyKey, $storyContent);
		}
		else
		{
			echo $storyContent;
		}		
	}

}
