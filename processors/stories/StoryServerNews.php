<?php

/**
 * Executes the logic to generate a story from the 
 * "Server News" source.
 * 
 * This pulls data from a long under-used new feeds coming from the MOTD on the server
 * 
 * @todo Currently there is no way of showing the axis or allied MOTD
 */
class StoryServerNews extends StoryBase implements StoryInterface {
	
	const NEWSTYPE_CRS = 0;
	const NEWSTYPE_ALLIED = 1;
	const NEWSTYPE_AXIS = 2;	
	
	public function isValid() {

		if($news = $this->getServerNews())
		{
			$this->creatorData['template_vars']['news'] = $news;
			return true;
		}
		
		return false;

	}
	
	/**
	 * This overrides the standard story making to build specific stories from server news
	 * 
	 * @param array $template
	 * @param boolean $comparePlaceholders
	 * @return array
	 */
	public function makeStory($template, $comparePlaceholders = false) {
		
		$template_vars = [
			'news' => $this->creatorData['template_vars']['news']['motd']
		];
		
		if($comparePlaceholders)
		{		
			$this->comparePlaceholders($template, $template_vars);
		}	
		
		$output = $this->parseStory($template_vars, $template['title'], $template['body']);
		
		return [
			'title' => $this->makeVarieties($template, $output['title'], $template_vars), 
			'body' => $this->makeVarieties($template, $output['body'], $template_vars)
			];
	}
	
	/**
	 * Retrieves the CRS MOTD news
	 * 
	 * Returns
	 * 
	 * @return array|null
	 */
	public function getServerNews()
	{
	
		$dbHelper = new dbhelper($this->dbConnWWIIOnline);
		
		$query = $dbHelper
			->prepare("SELECT unix_timestamp(m.added), IF(p.isplaynet,UPPER(p.callsign),p.callsign), m.motd "
				. "FROM wwii_motd m LEFT JOIN wwii_player p ON (m.added_by = p.playerid)  "
				. "WHERE m.type = ? ORDER BY m.added DESC LIMIT 1", [self::NEWSTYPE_CRS]);	
		
		$result = $dbHelper->getAsArray($query);
				
		return count($result) == 1 ? $result[0]:null;					
	}
	

}
