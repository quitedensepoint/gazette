<?php

use Phinx\Migration\AbstractMigration;

class sqladjust extends AbstractMigration
{

	public function up()
	{
		/** Remove spurious headline from US spawnlist */
		$this->execute("UPDATE `stories` SET `template` = '%BODY%', `expire` = b'0' WHERE `stories`.`story_id` = 55;
                       ");		

		/** Add more sources for template 119  */
		$this->execute("INSERT INTO `template_sources` (`template_id`, `source_id`) VALUES ('119', '2'), ('119', '53'); ");
                              
		
	}
	
	public function down() 
	{
        $this->execute("DELETE FROM `template_sources` WHERE `template_id` = '119' AND `source_id` ='2'");
        $this->execute("DELETE FROM `template_sources` WHERE `template_id` = '119' AND `source_id` ='53'");

		
	}
}
