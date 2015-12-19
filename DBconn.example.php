<?php 
/**
 * This file allows connections to the wwii database required by the gazette
 * for information
 * 
 */

$dbconn = mysqli_connect("p:db_host", "db_user", "db_password", "gazette");
if (mysqli_connect_errno()){
    echo "Failed to connect to the Gazette database: " . mysqli_connect_error();
}

$dbConnWWII = mysqli_connect("p:db_host","db_user","db_password","wwii");
if (mysqli_connect_errno()){
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

