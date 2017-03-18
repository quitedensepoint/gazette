<?php

use Phinx\Migration\AbstractMigration;

class StoryOwnershipIssue extends AbstractMigration
{
    public function up()
    {
		/**
		 * Change ENEMY_SIDE to SIDE_ADJ and remove the 's
		 */
		$this->execute("UPDATE 
									`templates` 
								SET 
									`title` = '%SIDE_ADJ% Tanks Ambushed Near %SPAWN%', 
									`modified` = NOW()
								WHERE 
									`template_id` = 120");
    }
	
    public function down()
    {
		/**
		 * Undo the change
		 */
		$this->execute("UPDATE 
									`templates` 
								SET 
									`title` = '%ENEMY_SIDE%'s Tanks Ambushed Near %SPAWN%', 
									`modified` = NOW()
								WHERE 
									`template_id` = 120");
		
    }	
}
