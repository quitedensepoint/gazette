/**
 * Creates a new table to hold the hourly results for the casualties in a
 * campaign
 *
 */

CREATE TABLE `campaign_casualties` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
	`updated_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
	`deleted_at` TIMESTAMP NULL DEFAULT NULL,
	`campaign_id` INT(10) UNSIGNED NOT NULL,
	`branch_id` INT(10) UNSIGNED NOT NULL,
	`kill_count` INT(10) UNSIGNED NOT NULL,
	`period_start` DATETIME NOT NULL,
	`period_end` DATETIME NOT NULL,
	`country_id` INT(10) UNSIGNED NOT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_unicode_ci'
ENGINE=InnoDB
;

-- ** ROLLBACK SCRIPT **
--
-- DROP TABLE `campaign_casualties`;

