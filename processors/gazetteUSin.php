<?php
/*
  This script is for the addition of US forces to the Gazette Reporting System. (aka 'the Gazette')
  It will do the following:
  a) Add United States to the Gazette_Country Table
  b) Add United States Country Code 2 to template_countries
  c) Add United States to templates 50, 15, and 104
*/
include '../DBconn.php';
// Add US to the template_countries table for each template
  $test = $dbconn->query("SELECT template_id FROM template_countries") or die ($link->error._fmbbawardLINE_);
  while ($row = $test->fetch_assoc())
    {
        $dbconn->query("INSERT INTO `template_countries` (`template_id`, `country_id`) VALUES ('".$row['template_id']."', '2');");
    }

// Add US to gazette_countries table      
    mysqli_query($dbconn, "INSERT INTO gazette.countries 
                                (`country_id`, `side_id`, `cycle_id`, `name`, `adjective`, `side`, `added`, `modified`) 
                         VALUES ('2', '1', '0', 'United States', 'USA', 'Allied', '2016-01-12 00:00:00', '2016-01-12 00:00:00')");
// Add US to template 50
    mysqli_query($dbconn, "UPDATE `templates` SET `body` = 'An independent analysis of military airfields controlled by the major combatants indicate that %1ST_COUNTRY% now controls %1ST_PERCENT%% of the airfields (%1ST_COUNT% total), followed by %2ND_COUNTRY% with %2ND_PERCENT%% (%2ND_COUNT%) and %3RD_COUNTRY% with %3RD_PERCENT%% (%3RD_COUNT%). TEST 4th Country - %4TH_COUNTRY% owns %4TH_PERCENT%% END TEST ' WHERE `templates`.`template_id` = '50';");        
// Add US to template 15
    mysqli_query($dbconn, "UPDATE `templates` SET `body` = 'Near the %COUNTRY_ADJ% controlled town of %CITY%, an unamed %COUNTRY_ADJ% official leaked a new intelligence report showing that %1ST_COUNTRY% now controls %1ST_PERCENT%% of the European Theatre of operations. %2ND_COUNTRY% owns %2ND_PERCENT%%, %3RD_COUNTRY% owns %3RD_PERCENT%%, and %4TH_COUNTRY% owns %4TH_PERCENT%%.  The report also shows that all combatants are stepping up efforts to push the front forward and take control of the front.', `modified` = '2016-01-13 15:44:44' WHERE `templates`.`template_id` = '15'; ");
// Add US to template 104
    mysqli_query($dbconn, "UPDATE `templates` SET `body` = 'Word from officials in the %COUNTRY_ADJ% controlled town of %CITY%, shows that %1ST_COUNTRY% now controls %1ST_PERCENT%% of the Western European Theatre of operations. %2ND_COUNTRY% owns %2ND_PERCENT%%, %3RD_COUNTRY% owns %3RD_PERCENT%%, with %4TH_COUNTRY% owning %4TH_PERCENT%%. The battle for control of the continent continues to descend into a fury of fire and hell.', `modified` = '2016-01-14 10:48:22' WHERE `templates`.`template_id` = '104' ; ");
     



   
    mysqli_close($dbconn);     
    

   
?>