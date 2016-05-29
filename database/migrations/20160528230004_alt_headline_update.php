<?php

use Phinx\Migration\AbstractMigration;

class AltHeadlineUpdate extends AbstractMigration
{

	public function up()
	{
		/* Add h1 to the alternate headline */
		$this->execute("UPDATE `stories` SET `alt` = '<h1>Heavy Fighting across Europe!</h1>' WHERE `story_id` = 20;");
	}
	
	public function down() 
	{
		$this->execute("UPDATE `stories` SET `alt` = 'Heavy Fighting across Europe!' WHERE `story_id` = 20;");
	}
}
