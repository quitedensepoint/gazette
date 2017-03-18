<?php

use Phinx\Migration\AbstractMigration;

class AddUnitedStates extends AbstractMigration
{
	public function up()
	{
		$this->execute("INSERT INTO `countries` 
                                (`country_id`, `side_id`, `cycle_id`, `name`, `adjective`, `side`, `added`, `modified`) 
                         VALUES ('2', '1', '0', 'United States', 'US', 'Allied', '2016-01-12 00:00:00', '2016-01-12 00:00:00')");
		
		$this->execute("INSERT INTO `template_countries` (`template_id`, `country_id`) SELECT `template_id`, 2 FROM `templates`");
		
		// Add US to template 50
		$this->execute("UPDATE `templates` SET `body` = 'An independent analysis of military airfields controlled by the major combatants indicate that %1ST_COUNTRY% now controls %1ST_PERCENT%% of the airfields (%1ST_COUNT% total), followed by %2ND_COUNTRY% with %2ND_PERCENT%% (%2ND_COUNT%) and %3RD_COUNTRY% with %3RD_PERCENT%% (%3RD_COUNT%). TEST 4th Country - %4TH_COUNTRY% owns %4TH_PERCENT%% END TEST ' WHERE `templates`.`template_id` = '50'");        
		// Add US to template 15
		$this->execute("UPDATE `templates` SET `body` = 'Near the %COUNTRY_ADJ% controlled town of %CITY%, an unamed %COUNTRY_ADJ% official leaked a new intelligence report showing that %1ST_COUNTRY% now controls %1ST_PERCENT%% of the European Theatre of operations. %2ND_COUNTRY% owns %2ND_PERCENT%%, %3RD_COUNTRY% owns %3RD_PERCENT%%, and %4TH_COUNTRY% owns %4TH_PERCENT%%.  The report also shows that all combatants are stepping up efforts to push the front forward and take control of the front.', `modified` = '2016-01-13 15:44:44' WHERE `templates`.`template_id` = '15'");
		// Add US to template 104
		$this->execute("UPDATE `templates` SET `body` = 'Word from officials in the %COUNTRY_ADJ% controlled town of %CITY%, shows that %1ST_COUNTRY% now controls %1ST_PERCENT%% of the Western European Theatre of operations. %2ND_COUNTRY% owns %2ND_PERCENT%%, %3RD_COUNTRY% owns %3RD_PERCENT%%, with %4TH_COUNTRY% owning %4TH_PERCENT%%. The battle for control of the continent continues to descend into a fury of fire and hell.', `modified` = '2016-01-14 10:48:22' WHERE `templates`.`template_id` = '104'");
		
	}
	
	public function down()
	{
		$this->execute("DELETE FROM `template_countries` WHERE `country_id` = 2");		
		
		$this->execute("DELETE FROM `countries` WHERE `country_id` = 2");
		
		// remove US from template 50
		$this->execute("UPDATE `templates` SET `body` = 'An independent analysis of military airfields controlled by the major combatants indicate that %1ST_COUNTRY% now controls %1ST_PERCENT%% of the airfields (%1ST_COUNT% total), followed by %2ND_COUNTRY% with %2ND_PERCENT%% (%2ND_COUNT%) and %3RD_COUNTRY% with %3RD_PERCENT%% (%3RD_COUNT%). ' WHERE `templates`.`template_id` = '50'");
		//remove US from template 15
       $this->execute("UPDATE `templates` SET `body` = 'Near the %COUNTRY_ADJ% controlled town of %CITY%, an unamed %COUNTRY_ADJ% official leaked a new intelligence report showing that %1ST_COUNTRY% now controls %1ST_PERCENT%% of the European Theatre of operations. %2ND_COUNTRY% owns %2ND_PERCENT%% and %3RD_COUNTRY% owns %3RD_PERCENT%%. The report also shows that all combatants are stepping up efforts to push the front forward and take control of the front.', `modified` = '2016-01-14 15:44:44' WHERE `templates`.`template_id` = '15'");
		//remove US from template 104
       $this->execute("UPDATE `templates` SET `body` = 'Word from officials in the %COUNTRY_ADJ% controlled town of %CITY%, shows that %1ST_COUNTRY% now controls %1ST_PERCENT%% of the Western European Theatre of operations. %2ND_COUNTRY% owns %2ND_PERCENT%% and %3RD_COUNTRY% owns %3RD_PERCENT%%.  The battle for control of the continent continues to descend into a fury of fire and hell.', `modified` = '2016-01-14 10:48:22' WHERE `templates`.`template_id` = '104'");
		
	}
}
