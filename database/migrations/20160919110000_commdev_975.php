<?php

use Phinx\Migration\AbstractMigration;

class Commdev975 extends AbstractMigration
{
    public function up()
    {									
		/**
		 * Adds an exclamation mark to the end of the title
		 */
		$this->execute("UPDATE 
									`templates` 
								SET 
									`title` = 'Fighting Spirit Shown by %PLAYER%!', 
									`modified` = NOW()
								WHERE 
									`template_id` = 101");
									
		/**
		 * Adds an exclamation mark to the end of the title
		 */
		$this->execute("UPDATE 
									`templates` 
								SET 
									`body` = 'Word came back from the %VARIETY1% that %PLAYER% killed %KILLS% %ENEMY_COUNTRY_ADJ% units in a combat mission that lasted %DURATION% minutes. After outsmarting and eliminating his opponents in a desperate battle he was posted as <i>%RTB%</i>.
These are the kinds of inspirational feats on the field of battle that spur our boys on to victory!', 
									`modified` = NOW()
								WHERE 
									`template_id` = 101");
    }
	
    public function down()
    {									
		/**
		 * Undo the change to template 101
		 */
		$this->execute("UPDATE 
									`templates` 
								SET 
									`title` = 'Fighting Spirit Shown by %PLAYER%', 
									`modified` = NOW()
								WHERE 
									`template_id` = 101");
									
		/**
		 * Undo the change to template 101
		 */
		$this->execute("UPDATE 
									`templates` 
								SET 
									`body` = 'Word came back from the %VARIETY1% that %PLAYER% killed %KILLS% %ENEMY_COUNTRY_ADJ% units in a combat mission that lasted %DURATION% minutes. After outsmarting and eliminating his opponents in a desperate battle he was posted as <i>%RTB%</i>
These are the kinds of inspirational feats on the field of battle that spur our boys on to victory !', 
									`modified` = NOW()
								WHERE 
									`template_id` = 101");
		
    }	
}
