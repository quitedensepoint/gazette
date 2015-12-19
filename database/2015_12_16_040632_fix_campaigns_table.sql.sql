-- This one is a little tricky. I am removing enums from the table because
-- they are a Bad Thing. You can't rename a column in the table if it
-- contains an enum. Don't use 'em!
--
-- drloon

-- create the column to hold the new state
ALTER TABLE `campaigns` ADD `state` VARCHAR(15) NOT NULL DEFAULT '';

-- update the new column to have the value of the enum
UPDATE `campaigns` SET `state` = `status`;

-- drop the enumeration
ALTER TABLE `campaigns` DROP COLUMN `status`;

-- rename the column back to the original
ALTER TABLE `campaigns` CHANGE `state` `status` VARCHAR(15);

-- Rename campaign_id to id as it as an auto increment
ALTER TABLE `campaigns` CHANGE `campaign_id` `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;

-- Add a column to store the game campaign ID
ALTER TABLE `campaigns` ADD COLUMN `campaign_id` INT(11) UNSIGNED NOT NULL DEFAULT 0;

-- Update all the old data for compatibility
UPDATE campaigns SET campaign_id = id;


-- ** ROLLBACK **
-- ALTER TABLE `campaigns` DROP COLUMN `campaign_id`;
-- ALTER TABLE `campaigns` CHANGE `id` `campaign_id` INT(11) UNSIGNED;
-- ALTER TABLE `campaigns` CHANGE `status` `state` VARCHAR(15);
-- ALTER TABLE `campaigns` ADD `status` ENUM('Running', 'Completed') NOT NULL DEFAULT 'Completed';
-- UPDATE `campaigns` SET `status` = `state`;
-- ALTER TABLE `campaigns` DROP COLUMN `state`;