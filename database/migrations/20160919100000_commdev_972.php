<?php

use Phinx\Migration\AbstractMigration;

class Commdev972 extends AbstractMigration
{
    public function up()
    {
		/**
		 * Remove the extra space infront of the exclamation point
		 */
		$this->execute("UPDATE 
									`templates` 
								SET 
									`title` = 'Heroic effort by %PLAYER% in Battle!', 
									`modified` = NOW()
								WHERE 
									`template_id` = 90");
    }
	
    public function down()
    {
		/**
		 * Undo the change to template 90
		 */
		$this->execute("UPDATE 
									`templates` 
								SET 
									`title` = 'Heroic effort by %PLAYER% in Battle !', 
									`modified` = NOW()
								WHERE 
									`template_id` = 90");		
    }	
}
