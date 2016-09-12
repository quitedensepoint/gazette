<?php
/**
 * This is an interface. When applied against a class (see StoryLatestIntel as
 * an example), it forces that class to create its own version of the
 * functions in the interface.
 * 
 * This means that no matter which class you create, you can just call the
 * related interface and know that it will have the correct inputs and outputs
 */
interface StoryInterface {
	
	/**
	 * Check to see if the data that makes up the story is valid
	 * 
	 * @return boolean true if the story rules evaluate to true
	 */
	public function isValid();
	
	/**
	 * Generate the story text from the data pulled during the
	 * validation checks
	 * 
	 * @param string $template The string representing the template story
	 * @param boolean $compareVariables if true, will perform a variable comparison on the template to make sure it is ok
	 * @return array
	 */
	public function makeStory($template, $compareVariables = false);
	
	/**
	 * Check to see if the story centers around the actions of a player
	 * 
	 * @return boolean
	 */
	public function isPlayerCentric();
	
	/**
	 * @return integer|null A player Id
	 */
	public function getProtagonistId();
	
	/**
	 * Format and retrieve the information from the story to go into the email sender
	 * 
	 * @param type $template
	 */
	public function generateHtmlContent($template);
	
	/**
	 * 
	 * @param type $template
	 */
	public function generateTextContent($template);
}

