<?php

use Phinx\Migration\AbstractMigration;

class AddStoryFormat extends AbstractMigration
{

	public function up()
	{
		/** Update the stories table to add more readily identifiable story names */
		$this->execute("CREATE TABLE `story_formats` (
			`id` TINYINT UNSIGNED NOT NULL,
			`key` VARCHAR(20) NOT NULL,
			`label` VARCHAR(45) NOT NULL,
			`description` VARCHAR(200) NOT NULL,
			`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
			`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY (`id`),
			UNIQUE INDEX `story_formats_key_uniq` (`key`)
			)
			COMMENT='A list of story formatting options'
			COLLATE='latin1_swedish_ci'
			ENGINE=InnoDB;"
		);

		/** Add some default story types */
		$this->execute("INSERT INTO `story_formats` (`id`, `key`, `label`, `description`) VALUES 
			(1, 'default', 'Default', 'Standard story format consisting of a title followed by a body'),
			(2, 'headline', 'Headline', 'A story consisting solely of a large headline'),
			(3, 'table', 'Table', 'A story consisting of a set of data in a tabular format')
		");

		/** Alter the stories table */
		$this->execute("ALTER TABLE `stories` ADD COLUMN `story_format_id` TINYINT UNSIGNED NOT NULL DEFAULT '1'");

		/** Add a foreign key reference */
		$this->execute("ALTER TABLE `stories` ADD CONSTRAINT `fk_stories_story_formats_story_format_id` 
			FOREIGN KEY (`story_format_id`) REFERENCES `story_formats` (`id`) ON UPDATE CASCADE"
		);

		echo "**** IF YOU RUN THIS MIGRATION - BE SURE TO RUN 20160109114400_data_update.sql manually DIRECTLY AFTERWARD***\n";
	}
	
	public function down() 
	{
		$this->execute("ALTER TABLE `stories` DROP FOREIGN KEY `fk_stories_story_formats_story_format_id`");

		$this->execute("ALTER TABLE `stories` DROP COLUMN `story_format_id`");

		$this->execute("DROP TABLE `story_formats`");
	}
}
