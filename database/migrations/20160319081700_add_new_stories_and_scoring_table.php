<?php

use Phinx\Migration\AbstractMigration;

class AddStories extends AbstractMigration
{

	public function up()
	{
		/** Add NEW Stories */
		$this->execute("INSERT INTO `stories` (`story_id`, `page_id`, `filename`, `content`, `template`, `used_id`, `size`, `alt`, `expires`, `story_key`, `title_case`, `expire`, `story_format_id`) VALUES 
                            ('55', '2', 'playnow_allied_us_spawn.html', '', '%TITLE% %BODY%', '17', '0', NULL, NULL, 'playnow_allied_us_spawn', 'Pass', b'0', '1'), 
                            ('56', '2', 'playnow_allied_promotion.html', '', '%TITLE% %BODY%', '121', '0', NULL, NULL, 'playnow_allied_promotion', 'Pass', b'0', '1'),
                            ('57', '2', 'playnow_allied_stats3.html', '', '%TITLE% %BODY%', '118', '0', NULL, NULL, 'playnow_allied_stats3', 'Pass', b'0', '1'),
                            ('58', '2', 'playnow_allied_stats4.html', '', '%TITLE% %BODY%', '114', '0', NULL, NULL, 'playnow_allied_stats4', 'Pass', b'0', '1'),
                            ('59', '1', 'index_general1.html', '', '%TITLE% %BODY%', '50', '0', NULL, NULL, 'index_general1', 'Pass', b'0', '1'), 
                            ('60', '1', 'index_general2.html', '', '%TITLE% %BODY%', '58', '0', NULL, NULL, 'index_general2', 'Pass', b'0', '1')
                            ");		

        /** Adjust story_countries for new stories */
		$this->execute("INSERT INTO `story_countries` (`story_id`, `country_id`) VALUES ('55', '2'), ('56', '1'), ('56', '2'), ('56', '3'), ('57', '1'), ('57', '2'), ('57','3'), ('58', '1'), ('58', '2'), ('58','3'), ('59', '3'), ('59', '1'), ('59', '4'), ('60', '3'), ('60', '1'), ('60', '4')");

		/** Adjust Story_types for new stories */
		$this->execute("INSERT INTO `story_types` (`story_id`, `type_id`) VALUES ('55', '7'), ('56', '5'), ('57', '5'), ('58', '5'), ('59', '3'), ('60', '3')");

		/** Create scoring_top_players table in the Gazette Database */
		$this->execute("CREATE TABLE `gazette`.`scoring_top_players` ( 
                        `id` INT(5) NOT NULL AUTO_INCREMENT ,
                        `side` INT(1) NOT NULL , 
                        `period` VARCHAR(4) NOT NULL , 
                        `capscallsign` VARCHAR(15) NOT NULL COMMENT 'Most total captures', 
                        `caps` INT(6) NOT NULL,
                        `killscallsign` VARCHAR(15) NOT NULL COMMENT 'Most total kills',
                        `kills` INT (6) NOT NULL,  
                        `tomcallsign` VARCHAR(15) NOT NULL COMMENT 'Most time on mission', 
                        `tom` INT(6) NOT NULL , 
                        `kdcallsign` VARCHAR(15) NOT NULL COMMENT 'highest k/d', 
                        `kd` INT(6) NOT NULL , 
                        `sortmkillcallsign` VARCHAR(15) NOT NULL COMMENT 'Most kills on a single sortie', 
                        `sortmkill` INT(6) NOT NULL , 
                        `sortcapscallsign` VARCHAR(15) NOT NULL COMMENT 'Most captures on a single sortie', 
                        `capstreak` INT(6) NOT NULL , 
                        `krowcallsign` VARCHAR(15) NOT NULL COMMENT 'Most consecutive sorties with a kill', 
                        `krowpoints` INT(6) NOT NULL , 
                        `crowcallsign` VARCHAR(15) NOT NULL COMMENT 'Most consecutive sorties with a capture', 
                        `crowpoints` INT(6) NOT NULL , 
                        `rcallsign` VARCHAR(15) NOT NULL COMMENT 'Top Rifle', 
                        `rpoints` DECIMAL(8,2) NOT NULL , 
                        `smgcallsign` VARCHAR(15) NOT NULL COMMENT ' Top smg', 
                        `smgpoints` DECIMAL(8,2) NOT NULL , 
                        `lmgcallsign` VARCHAR(15) NOT NULL COMMENT 'Top lmg', 
                        `lmgpoints` DECIMAL(8,2) NOT NULL , 
                        `snipercallsign` VARCHAR(15) NOT NULL COMMENT 'Top Sniper', 
                        `sniperpoints` DECIMAL(8,2) NOT NULL , 
                        `atrcallsign` VARCHAR(15) NOT NULL COMMENT 'Top atr', 
                        `atrpoints` DECIMAL(8,2) NOT NULL , 
                        `aaacallsign` VARCHAR(15) NOT NULL COMMENT 'Top AAA', 
                        `aaapoints` DECIMAL(8,2) NOT NULL , 
                        `atgcallsign` VARCHAR(15) NOT NULL COMMENT 'Top ATG', 
                        `atgpoints` DECIMAL(8,2) NOT NULL , 
                        `bombcallsign` VARCHAR(15) NOT NULL COMMENT 'Top Bomber', 
                        `bombpoints` DECIMAL(8,2) NOT NULL , 
                        `fightcallsign` VARCHAR(15) NOT NULL COMMENT 'Top Fighter', 
                        `fightpoints` DECIMAL(8,2) NOT NULL , 
                        `ddcallsign` VARCHAR(15) NOT NULL COMMENT 'Top Destroyer', 
                        `ddpoints` DECIMAL(8,2) NOT NULL , 
                        `pbcallsign` VARCHAR(15) NOT NULL COMMENT 'Top Patrol Boat', 
                        `pbpoints` DECIMAL(8,2) NOT NULL , 
                        `tankcallsign` VARCHAR(15) NOT NULL COMMENT 'Top tank', 
                        `tankpoints` DECIMAL(8,2) NOT NULL , 
                        `grencallsign` VARCHAR(15) NOT NULL COMMENT 'Top Grenadier', 
                        `grenpoints` DECIMAL(8,2) NOT NULL , 
                        `engcallsign` VARCHAR(15) NOT NULL COMMENT 'Top Engineer', 
                        `engpoints` DECIMAL(8,2) NOT NULL , 
                        `mortcallsign` VARCHAR(15) NOT NULL COMMENT 'Top Mortar', 
                        `mortpoints` DECIMAL(8,2) NOT NULL , 
                        `truckcallsign` VARCHAR(15) NOT NULL COMMENT 'Top Truck', 
                        `truckpoints` DECIMAL(8,2) NOT NULL , 
                        `ttcallsign` VARCHAR(15) NOT NULL COMMENT 'Top Freighter', 
                        `ttpoints` DECIMAL(8,2) NOT NULL , 
                        PRIMARY KEY (`id`))
                        ");
		
	}
	
	public function down() 
	{
        $this->execute("DELETE FROM `story_types` WHERE `story_id` = '55' OR `story_id` ='56' OR`story_id` = '57' OR`story_id` = '58' OR`story_id` = '59' OR`story_id` = '60'");
		$this->execute("DELETE FROM `story_countries` WHERE `story_id` = '55' OR `story_id` ='56' OR`story_id` = '57' OR`story_id` = '58' OR`story_id` = '59' OR`story_id` = '60'");
		$this->execute("DELETE FROM `stories` WHERE `story_id`='55' OR `story_id` ='56' OR `story_id` ='57' OR `story_id` ='58' OR `story_id` ='59' OR `story_id` ='60' ");
		$this->execute("DROP TABLE `scoring_top_players`");
	}
}
