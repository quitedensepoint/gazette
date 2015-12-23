<?php
require(__DIR__ . '/../DBconn.php');
// WOrking page for Current AO and recent Captures
$caps=$dbconn->query("SELECT id, start_time FROM `campaigns` WHERE `status`='Running'");
$crow=$caps->fetch_assoc();



?>