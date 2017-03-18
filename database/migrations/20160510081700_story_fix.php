<?php

use Phinx\Migration\AbstractMigration;

class storyfix extends AbstractMigration
{

	public function up()
	{
		/** Add missing template classes */
		$this->execute("INSERT INTO `template_classes` (`template_id`, `class_id`) VALUES ('121', '13'), ('121', '12'), ('121', '9'),
                        ('121', '7'), ('121', '6'), ('121', '4'), ('121', '2'), ('121', '1'), ('120', '13'), ('120', '12'), ('120', '9'),
                        ('120', '7'), ('120', '6'), ('120', '4'), ('120', '2'), ('120', '1'), ('119', '13'), ('119', '12'), ('119', '9'),
                        ('119', '7'), ('119', '6'), ('119', '4'), ('119', '2'), ('119', '1'), ('118', '13'), ('118', '12'), ('118', '9'),
                        ('118', '7'), ('118', '6'), ('118', '4'), ('118', '2'), ('118', '1');
                       ");		

        /** UPdate US Story for alt-information */
		$this->execute("UPDATE `stories` SET `alt` = '<br><br>Despite persistent rumors of their impending arrival, U.S. Forces have not yet reached the front', `expire` = b'0' WHERE `stories`.`story_id` = 55;");

		/** Adjust template_countries  */
		$this->execute("INSERT INTO `template_countries` (`template_id`, `country_id`) VALUES ('17', '2');");

		
                        
		
	}
	
	public function down() 
	{
        $this->execute("DELETE FROM `template_classes` WHERE `template_id` = '117' OR `template_id` ='118' OR `template_id` = '119' OR `template_id` = '120' OR `template_id` = '121'");

		
	}
}
