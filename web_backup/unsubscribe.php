<?php

require(__DIR__ . '/../DBconn.php');
require(__DIR__ . '/../include/dbhelper.php');
require(__DIR__ . '/../vendor/autoload.php');

$notificationManager = new Playnet\WwiiOnline\Common\PlayerMail\NotificationManager($dbconn, 'IgnoreHandler', $options['playerMail']['options']);

$notificationManager->unsubscribe($_GET['linkid'], $_GET['token']);

?>
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>        
    <title>World@War Gazette</title>
	<link rel='stylesheet' href='assets/css/gazette.css'>
	<link rel="shortcut icon" href="assets/img/favicon.ico" />
</head>

<body>
	<div id="container">
<!-- Header: Allied casualties | Camp info | Axis causualties --> 
		<div id='top' >
			<img src='assets/img/header.gif'  id='headerMiddle'>
			<span id="version">Version: 2.2</span>
		</div>
<!-- Version Info and Navigation Section -->
        <div id='info'>
            <p style="text-align: center; font-size:2em;">You have been unsubscribed from the Gazette email notifications.</p>
        </div>
	</div>
</body>
</html>
