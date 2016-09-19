<?php 

require(__DIR__ . '/../DBconn.php');
 
// query for CP changing ownership.

$cap=("SELECT *
        FROM `strat_captures` AS s 
        LEFT JOIN `wwii_country` AS w
            ON s.cust_country=w.countryID
        LEFT JOIN `strat_facility` as i
            ON s.facility_oid=i.facility_oid

        LEFT JOIN `strat_cp` as c
            ON i.cp_oid=c.cp_oid
        WHERE `took_ownership` ='1' AND s.contention ='End' 
        ORDER BY `started_at` DESC
        LIMIT 5 " );


$caps=$dbConnWWIIOL->query($cap) or die ($dbConnWWIIOL->error.'_ownLINE_');
if (!$caps) {
    throw new Exception("Database Error [{$dbConnWWIIOL->query($cap)->errno}] {$dbConnWWIIOL->query($cap)->error}");
}; 

 ?>
<?php
 
// query for Allied AO's 

$aoal = "SELECT name, contention as alcon FROM strat_cp WHERE is_objective>='1' AND conside='2'";
$aoals = $dbConnWWIIOL->query($aoal) or die ($dbConnWWIIOL->error._LINE_);
if (!$aoals) {
    throw new Exception("Database Error [{$dbConnWWIIOL->query($aoal)->errno}] {$dbConnWWIIOL->query($aoal)->error}");
}

 ?>

 <?php
 
// query for Axis AO's 

$aoax = "SELECT name, contention as axcon FROM strat_cp WHERE is_objective>='1' AND conside='1'";
$aoaxs = $dbConnWWIIOL->query($aoax) or die ($dbConnWWIIOL->error._LINE_);
if (!$aoaxs) {
    throw new Exception("Database Error [{$dbConnWWIIOL->query($aoax)->errno}] {$dbConnWWIIOL->query($aoax)->error}");
}



 ?>