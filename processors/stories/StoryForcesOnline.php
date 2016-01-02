<?php

/**
 * Executes the logic to generate a story from the 
 * "Forces Online" source.
 * 
 * This is the story that generates the red and blue icons into the forum
 * 
 */
class StoryForcesOnline extends StoryBase implements StoryInterface {
	
	public function isValid() {

		return true;
	}
}
