<?php

/**
 * Executes the logic to generate a story from the 
 * "RDP Archive Filler" source.
 * 
 * Doesn't really do anything it appears except fill a blank space with some
 * images. See template_id 39 to see what goes in.
 * 
 * @todo Consult with XOOM on value of this story.
 */
class StoryRDPArchiveFiller extends StoryRDPBase implements StoryInterface {
		
	public function isValid() {

		/**
		 * @todo Not currently implemented, this was designed to fill empty sapce. Not sure if still useful
		 */
		
		return false;
		
	}
}