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
	 * @return array
	 */
	public function makeStory($template);
}

