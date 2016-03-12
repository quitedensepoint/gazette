<?php
/*
* Used to get the current date and time that the main webmap is using.
* This is used in order to show the same Brigade Flags that the main WebMap is using.
* It will also set the variable used to decide which environment it is.
*/
require(__DIR__ . '/../DBconn.php');
	
if (isset($options) && isset($options['webmap-environment']) && $options['webmap-environment'] === 'live'){
	$webmapEnv = "https://webmap.wwiionline.com";
}else{
	$webmapEnv = "https://webmap.community-dev.playnet.com";
}

// Get the latest WebMap update DateTime
$datetime=$dbConnWebmap->query("SELECT datetime FROM captures ORDER BY datetime DESC LIMIT 1");
$row=$datetime->fetch_assoc();
$fileDt = date('Y-m-d_H-i', strtotime($row['datetime']));

?>