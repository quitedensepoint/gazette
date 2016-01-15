<?php
/*
  This script is for the removal of US forces to the Gazette Reporting System. (aka 'the Gazette')
 
 
*/
include '../DBconn.php';

       // $usadd = new dbhelper($this->dbConn);
// removes from gazette countries should remove US from anything that requires ordering.       
       mysqli_query($dbconn, "DELETE FROM countries WHERE countries.country_id ='2'");
// remove US from template_countries -- will not generate stories from the 'view' of US
       mysqli_query($dbconn, "DELETE FROM template_countries WHERE `template_countries`.`country_id` = '2'");
// remove US from template 50
       mysqli_query($dbconn, "UPDATE `templates` SET `body` = 'An independent analysis of military airfields controlled by the major combatants indicate that %1ST_COUNTRY% now controls %1ST_PERCENT%% of the airfields (%1ST_COUNT% total), followed by %2ND_COUNTRY% with %2ND_PERCENT%% (%2ND_COUNT%) and %3RD_COUNTRY% with %3RD_PERCENT%% (%3RD_COUNT%). ' WHERE `templates`.`template_id` = '50'");
//remove US from template 15
       mysqli_query($dbconn, "UPDATE `templates` SET `body` = 'Near the %COUNTRY_ADJ% controlled town of %CITY%, an unamed %COUNTRY_ADJ% official leaked a new intelligence report showing that %1ST_COUNTRY% now controls %1ST_PERCENT%% of the European Theatre of operations. %2ND_COUNTRY% owns %2ND_PERCENT%% and %3RD_COUNTRY% owns %3RD_PERCENT%%. The report also shows that all combatants are stepping up efforts to push the front forward and take control of the front.', `modified` = '2016-01-14 15:44:44' WHERE `templates`.`template_id` = '15';");
//remove US from template 104
       mysqli_query($dbconn, "UPDATE `templates` SET `body` = 'Word from officials in the %COUNTRY_ADJ% controlled town of %CITY%, shows that %1ST_COUNTRY% now controls %1ST_PERCENT%% of the Western European Theatre of operations. %2ND_COUNTRY% owns %2ND_PERCENT%% and %3RD_COUNTRY% owns %3RD_PERCENT%%.  The battle for control of the continent continues to descend into a fury of fire and hell.', `modified` = '2016-01-14 10:48:22' WHERE `templates`.`template_id` = '104';");







        
    mysqli_close($dbconn);     
    

   
?>