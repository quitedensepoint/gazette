-- Modify the camapaign casualties database
-- 

ALTER TABLE `campaign_casualties` DROP COLUMN `side_id`, ADD COLUMN `country_id` INT(11) UNSIGNED;

-- ** ROLLBACK **
-- ALTER TABLE `campaign_casualties` DROP COLUMN `country_id`, ADD COLUMN `side_id` INT(11) UNSIGNED;