/*****************************
 * The next changes cannot be rolled back. Backup first and proceed with caution
 *
 * This MUST be run manually directly after execution of the AddStoryFormat migration. This
 * is due to the destructive nature of the change 
 *****************************/

UPDATE `templates` SET `title` = REPLACE(`title`,'<br>','');
UPDATE `templates` SET `body` = REPLACE(`body`,'<br>','\n');

-- Keep running the next query until is says there are no more records affected
UPDATE `templates` SET `body` = REPLACE(`body`,'\r\n\r\n','\n');

-- Keep running the next query until is says there are no more records affected
UPDATE `templates` SET `body` = REPLACE(`body`,'\n\n','\n');

-- TITLE only stories
UPDATE `stories` SET `story_format_id` = 2 WHERE `story_key` in ('index_main_headline');
