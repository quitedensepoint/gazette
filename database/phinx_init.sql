/**
 * This SQL file is to be used ONLY when phinx is first set up on your local environment.
 * As Phinx was introduced part way into the rebuild of the application, several SQL migrations
 * in plain SQL files had already been introduced. These have been converted to migration files.
 *
 * This script must be run after the installation of Phinx on the system.
 *
 **********/

CREATE TABLE `phinxlog` (
	`version` BIGINT(20) NOT NULL,
	`start_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`end_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`version`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;

INSERT INTO `phinxlog` (`version`) VALUES 
(20151214105805),
(20151216040632),
(20151220133800),
(20151220185600),
(20160109114400),
(20160115211444);