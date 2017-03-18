<?php

use Phinx\Migration\AbstractMigration;

class AddStoryKeys extends AbstractMigration
{

	public function up()
	{
		/** Update the stories table to add more readily identifiable story names */
		$this->execute("ALTER TABLE `stories` ADD `story_key` VARCHAR(30) UNIQUE");

		/** Add the keys for the current existing stories */
		$this->execute("UPDATE `stories` SET `story_key` = REPLACE(`filename`, '.html', '')");

		/** Kill off the evil use of enums */
		$this->execute("ALTER TABLE `stories` ADD `title_case2` VARCHAR(10) NOT NULL DEFAULT '', ADD `expire2` BIT(1) NOT NULL DEFAULT 1");

		/** update the new columns to have the value of the enums */
		$this->execute("UPDATE `stories` SET `title_case2` = `title_case`, `expire2` = CASE `expire` WHEN 'True' THEN 1 ELSE 0 END");

		/** drop the enumeration */
		$this->execute("ALTER TABLE `stories` DROP COLUMN `title_case`, DROP COLUMN `expire`");

		/** rename the column back to the original */
		$this->execute("ALTER TABLE `stories` CHANGE `title_case2` `title_case` VARCHAR(10)");
		$this->execute("ALTER TABLE `stories` CHANGE `expire2` `expire` BIT(1)");
	}
	
	public function down() 
	{
		$this->execute("ALTER TABLE `stories` CHANGE `expire` `expire2` BIT(1)");
		$this->execute("ALTER TABLE `stories` CHANGE `title_case` `title_case2` VARCHAR(10)");
		$this->execute("ALTER TABLE `stories` ADD `expire` ENUM('True','False'), ADD `title_case` ENUM('Pass','Upper','Random')");
		$this->execute("UPDATE `stories` SET `title_case` = `title_case2`, `expire` = CASE `expire2` WHEN 1 THEN 'True' ELSE 'False' END");
		$this->execute("ALTER TABLE `stories` DROP COLUMN `title_case2`, DROP COLUMN `expire2`");
		$this->execute("ALTER TABLE `stories` DROP COLUMN `story_key`");
	}
}
