<?php

/**
 * Executes the logic to generate a story from the 
 * "Casualty Reporter" source.
 * 
 * This story is current deprecated as the casualty reporting is now a separate
 * process
 * 
 */
class StoryCasualtyReporter extends StoryRDPBase implements StoryInterface {
		
	public function isValid() {

		/**
		 * @todo Story is deprecated
		 */
		
		return false;
		
	}
}