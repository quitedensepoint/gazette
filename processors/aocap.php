<?php 

require(__DIR__ . '/../DBconn.php');
 
// query for CP changing ownership.

$cap=("SELECT *
        FROM `strat_captures` AS s 
        LEFT JOIN `wwii_country` AS w
            ON s.facil_country=w.countryID
        LEFT JOIN `strat_facility` as i
            ON s.facility_oid=i.facility_oid

        LEFT JOIN `ids_cp` as c
            ON i.cp_oid=c.cp_oid
        WHERE `took_ownership` ='1' AND contention='End' 
        ORDER BY `started_at` DESC
        LIMIT 5 " );


$caps=$dbConnWWIIOL->query($cap) or die ($dbConnWWIIOL->error.'_ownLINE_');
if (!$caps) {
    throw new Exception("Database Error [{$dbConnWWIIOL->query($cap)->errno}] {$dbConnWWIIOL->query($cap)->error}");
}; 

 ?>
<?php
 
// generic start query for AO's ownership.

$ao = "SELECT name FROM strat_cp WHERE is_objective>='1'";
$aos = $dbConnWWIIOL->query($ao) or die ($dbConnWWIIOL->error._LINE_);
if (!$aos) {
    throw new Exception("Database Error [{$dbConnWWIIOL->query($ao)->errno}] {$dbConnWWIIOL->query($ao)->error}");
}

 ?>