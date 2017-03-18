<?php

/**
 * Executes the logic to generate a story from the 
 * "Server Status" source.
 * 
 * This is supposed to pull the current status of the server and show a 
 * Call To Action (e.g. A "Play Now" button) depending on the status of the
 * server.
 * 
 * @todo Determine if still required and implement
 */
class StoryServerStatus extends StoryBase implements StoryInterface {
	

	public function isValid() {
	
		return false;

	}

}
