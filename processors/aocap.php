<?php
/**
 * Description of the aocap processor
 *
 * @author B2k
 */

require(__DIR__ . '/../DBconn.php');
 
// Query for CP changing ownership

$capsQuery = ("SELECT *
			FROM `strat_captures` AS s 
				LEFT JOIN `wwii_country` AS w
					ON `s`.`cust_country` = `w`.`countryID`
				LEFT JOIN `strat_facility` AS i
					ON `s`.`facility_oid` = `i`.`facility_oid`
				LEFT JOIN `strat_cp` AS c
					ON `i`.`cp_oid` = `c`.`cp_oid`
			WHERE `took_ownership` = '1'
				AND `s`.`contention` = 'End' 
			ORDER BY `started_at` DESC
			LIMIT 5");
$capsData = $dbConnWWIIOL->query($capsQuery) or die ($dbConnWWIIOL->error.'_ownLINE_');
if (!$capsData) {
    throw new Exception("Database Error [{$dbConnWWIIOL->query($capsQuery)->errno}] {$dbConnWWIIOL->query($capsQuery)->error}");
}; 
 
// Query for Allied AOs 

$aosAlliedQuery = "SELECT `name`, `contention` AS alcon 
							FROM `strat_cp` 
							WHERE `is_objective` >= '1'
								AND `conside` = '2'";
$aosAlliedData = $dbConnWWIIOL->query($aosAlliedQuery) or die ($dbConnWWIIOL->error._LINE_);
if (!$aosAlliedData) {
    throw new Exception("Database Error [{$dbConnWWIIOL->query($aosAlliedQuery)->errno}] {$dbConnWWIIOL->query($aosAlliedQuery)->error}");
}
 
// Query for Axis AOs 

$aosAxisQuery = "SELECT `name`, `contention` AS axcon 
			FROM `strat_cp` 
			WHERE `is_objective` >= '1' 
				AND `conside` = '1'";
$aosAxisData = $dbConnWWIIOL->query($aosAxisQuery) or die ($dbConnWWIIOL->error._LINE_);
if (!$aosAxisData) {
    throw new Exception("Database Error [{$dbConnWWIIOL->query($aosAxisQuery)->errno}] {$dbConnWWIIOL->query($aosAxisQuery)->error}");
}

?>