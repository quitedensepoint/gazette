<?php
/*
* Used to get the current date and time that the main webmap is using.
* This is used in order to show the same Brigade Flags that the main WebMap is using.
* It will also set the variable used to decide which environment it is.
*/
/**
 *  require once is used here because otherwise, when this file is included in index.php,
 *  the DBConn is loaded *twice*, effectively resetting everything and causing potential issues
 */
require_once(__DIR__ . '/../DBconn.php');

$webmapEnv = "https://webmap.wwiionline.com";


// Get the latest WebMap update DateTime
$datetime = $dbConnWebmap->query("SELECT datetime FROM captures ORDER BY datetime DESC LIMIT 1");
$webmap_row = $datetime->fetch_assoc();
$fileDt = date('Y-m-d_H-i', strtotime($webmap_row['datetime']));
$fileDtPrev = date('Y-m-d_H-i', strtotime($webmap_row['datetime'] . " - 15 minutes"));
