<?php

use Phinx\Migration\AbstractMigration;

class alterSorties extends AbstractMigration
{

	public function up()
	{
		/** Alter some sorties to test allied daily stats */
		$this->execute("UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-24 08:00:00' WHERE `scoring_campaign_sorties`.`sortie_id` = 348 AND `scoring_campaign_sorties`.`persona_id` = 1164293 AND `scoring_campaign_sorties`.`player_id` = 1490480; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-24 08:00:00' WHERE `scoring_campaign_sorties`.`sortie_id` = 278 AND `scoring_campaign_sorties`.`persona_id` = 957244 AND `scoring_campaign_sorties`.`player_id` = 1398688; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-24 09:00:00' WHERE `scoring_campaign_sorties`.`sortie_id` = 103 AND `scoring_campaign_sorties`.`persona_id` = 1096087 AND `scoring_campaign_sorties`.`player_id` = 1461578; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-24 09:00:00' WHERE `scoring_campaign_sorties`.`sortie_id` = 12 AND `scoring_campaign_sorties`.`persona_id` = 444388 AND `scoring_campaign_sorties`.`player_id` = 1086812; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-24 10:00:00' WHERE `scoring_campaign_sorties`.`sortie_id` = 11 AND `scoring_campaign_sorties`.`persona_id` = 1164192 AND `scoring_campaign_sorties`.`player_id` = 1490440; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-24 10:00:00' WHERE `scoring_campaign_sorties`.`sortie_id` = 5 AND `scoring_campaign_sorties`.`persona_id` = 444388 AND `scoring_campaign_sorties`.`player_id` = 1086812; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-24 11:00:00' WHERE `scoring_campaign_sorties`.`sortie_id` = 4 AND `scoring_campaign_sorties`.`persona_id` = 1161480 AND `scoring_campaign_sorties`.`player_id` = 1489449; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-24 11:00:00' WHERE `scoring_campaign_sorties`.`sortie_id` = 1 AND `scoring_campaign_sorties`.`persona_id` = 212612 AND `scoring_campaign_sorties`.`player_id` = 83073; 
                        ");		

		
	}
	
	public function down() 
	{
        $this->execute("UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:46:47' WHERE `scoring_campaign_sorties`.`sortie_id` = 348 AND `scoring_campaign_sorties`.`persona_id` = 1164293 AND `scoring_campaign_sorties`.`player_id` = 1490480; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:42:52' WHERE `scoring_campaign_sorties`.`sortie_id` = 278 AND `scoring_campaign_sorties`.`persona_id` = 957244 AND `scoring_campaign_sorties`.`player_id` = 1398688; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:09:34' WHERE `scoring_campaign_sorties`.`sortie_id` = 103 AND `scoring_campaign_sorties`.`persona_id` = 1096087 AND `scoring_campaign_sorties`.`player_id` = 1461578; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:00:37' WHERE `scoring_campaign_sorties`.`sortie_id` = 12 AND `scoring_campaign_sorties`.`persona_id` = 444388 AND `scoring_campaign_sorties`.`player_id` = 1086812; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:00:37' WHERE `scoring_campaign_sorties`.`sortie_id` = 11 AND `scoring_campaign_sorties`.`persona_id` = 1164192 AND `scoring_campaign_sorties`.`player_id` = 1490440; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 13:58:35' WHERE `scoring_campaign_sorties`.`sortie_id` = 5 AND `scoring_campaign_sorties`.`persona_id` = 444388 AND `scoring_campaign_sorties`.`player_id` = 1086812; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 13:58:25' WHERE `scoring_campaign_sorties`.`sortie_id` = 4 AND `scoring_campaign_sorties`.`persona_id` = 1161480 AND `scoring_campaign_sorties`.`player_id` = 1489449; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 13:57:08' WHERE `scoring_campaign_sorties`.`sortie_id` = 1 AND `scoring_campaign_sorties`.`persona_id` = 212612 AND `scoring_campaign_sorties`.`player_id` = 83073; 
                        ");
	}
}
