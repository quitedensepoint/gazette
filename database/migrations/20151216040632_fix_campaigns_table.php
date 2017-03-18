<?php

use Phinx\Migration\AbstractMigration;

class FixCampaignsTable extends AbstractMigration
{

	public function up()
	{		
		/** create the column to hold the new state */
        $this->execute("ALTER TABLE `campaigns` ADD `state` VARCHAR(15) NOT NULL DEFAULT ''");	
		
		/** update the new column to have the value of the enum */
		$this->execute("UPDATE `campaigns` SET `state` = `status`");
		
		/** drop the enumeration */
		$this->execute("ALTER TABLE `campaigns` DROP COLUMN `status`");

		/** rename the column back to the original */
		$this->execute("ALTER TABLE `campaigns` CHANGE `state` `status` VARCHAR(15)");

		/** Rename campaign_id to id as it as an auto increment */
		$this->execute("ALTER TABLE `campaigns` CHANGE `campaign_id` `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT");

		/** Add a column to store the game campaign ID */
		$this->execute("ALTER TABLE `campaigns` ADD COLUMN `campaign_id` INT(11) UNSIGNED NOT NULL DEFAULT 0");

		/** Update all the old data for compatibility */
		$this->execute("UPDATE campaigns SET campaign_id = id");		
	}
	
	public function down() 
	{
		$this->execute("ALTER TABLE `campaigns` DROP COLUMN `campaign_id`");	
		$this->execute("ALTER TABLE `campaigns` CHANGE `id` `campaign_id` INT(11) UNSIGNED");	
		$this->execute("ALTER TABLE `campaigns` CHANGE `status` `state` VARCHAR(15)");	
		$this->execute("ALTER TABLE `campaigns` ADD `status` ENUM('Running', 'Completed') NOT NULL DEFAULT 'Completed'");	
		$this->execute("UPDATE `campaigns` SET `status` = `state`");	
		$this->execute("ALTER TABLE `campaigns` DROP COLUMN `state`");	
	}
}
