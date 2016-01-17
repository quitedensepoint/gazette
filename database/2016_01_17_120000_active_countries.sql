-- Change the table information to be able to mark countries as active
-- All countries are inactive at the start of the campaign until their
-- first sortie is recorded
ALTER TABLE `countries` 
    ADD COLUMN `is_active` TINYINT(1) NOT NULL DEFAULT 0, 
    ADD COLUMN `activated_at` DATETIME NULL;

-- Add the new countries to the Gazette
INSERT INTO `countries` (`country_id`, `side_id`, `cycle_id`, `name`, `adjective`, `side`, `added`, `modified`) VALUES
(5, 2, 0, 'Italy', 'Italian', 'Axis', NOW(), NOW()),
(6, 2, 0, 'Japan', 'Japanese', 'Axis', NOW(), NOW()),
(7, 1, 0, 'Commonwealth', 'Commonwealth', 'Allied', NOW(), NOW()),
(8, 1, 0, 'China', 'Chinese', 'Allied', NOW(), NOW()),
(9, 1, 0, 'Russia', 'Russian', 'Allied', NOW(), NOW());


-- ** ROLLBACK **
-- DELETE FROM `countries` WHERE `country_id` >= 5;
-- ALTER TABLE `countries` DROP COLUMN is_active, DROP COLUMN activated_at;
