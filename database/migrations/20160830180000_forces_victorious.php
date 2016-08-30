<?php

use Phinx\Migration\AbstractMigration;

class ForcesVictorious extends AbstractMigration
{
    public function up()
    {
		/**
		 * Change VICTORIOUS_SIDE to SIDE_ADJ
		 */
		$this->execute("UPDATE 
									`templates` 
								SET 
									`title` = '%SIDE_ADJ% Forces Victorious!', 
									`body` = '%SIDE_ADJ% Forces Victorious!',
									`modified` = NOW()
								WHERE 
									`template_id` = 86");
    }
	
    public function down()
    {
		/**
		 * Undo the change
		 */
		$this->execute("UPDATE 
									`templates` 
								SET 
									`title` = '%VICTORIOUS_SIDE% Forces Victorious!', 
									`body` = '%VICTORIOUS_SIDE% Forces Victorious!',
									`modified` = NOW()
								WHERE 
									`template_id` = 86");
		
    }	
}
