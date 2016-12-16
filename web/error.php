<?php
/**
 * This file is loaded when an error is caught by the PHP error handler
 * (see include/error-handlers.php and dbConn.php)
 */
?>
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>        
    <title>World@War Gazette</title>
	<link rel="stylesheet" href="/assets/css/gazette.css">
	<link rel="shortcut icon" href="/assets/img/favicon.ico" />
	<style>
		.error {
			max-width: 800px;
			margin:10px auto;			
		}
		.error h2 {
			font-size: 30px;
			margin: 5px;
			text-align: center;
			text-transform: uppercase;
			border-bottom:1px solid #333;
		}
		.error p {
			font-size: 16px;			
		}
		.error p:first-letter {
			font-size: 25px;			
		}
		.error.error-small {
			text-align: center;
		}
		.error.error-small h3 {
			font-size: 23px;
			margin:3px auto;
		}		
		.error.error-small p {
			font-size: 12px;
		}
		.error.error-data {
			border:1px solid #333;
			padding:4px;
			background-color:rgba(100,100,100,0.7);
			margin:10px auto;
		}		
		.error.error-image figure {
			text-align: center;
			border-bottom:1px solid #333;
		}
		.error.error-image img {
			max-height:400px
		}
	</style>
</head>

<body>
	<div id="container">
		<div id="top" style="position: relative">
			<img src='assets/img/header.gif'  id='headerMiddle'>
		</div>

        <div id="mainHeadline" class="mainheadline" style="position: relative;top:auto;width:inherit">
            <h1>World@War offices bombed!</h1>
        </div>
		<div class="error" style="clear:both;">
			<h2>enemy forces launch brazen attack</h2>
			<p>Overnight, bombers belonging to an unknown enemy squadron responded to this
			newspaper's accurate reporting of their activities with a wave of destruction
			intended to dampen our spirit and make us cease our journalism.</p>
			<p>We will not be threatened and while our presses are damaged, our staff are still 
				committed to working day and night to bring you the news! Watch this space!
			</p>
		</div>
		<div class="error error-image">
			<figure>
				<img src="/assets/img/error.jpg" alt="World@War Offices">
				<figcaption>World@War offices - we will rebuild!</figcaption>
			</figure>
		</div>
		
		<div class="error error-small">
			<h3>Enemy Communique Intercepted</h3>
			<p>A cryptic enemy dispatch was intercepted last night and is currently in the hands of our codebreakers.</p>
		</div>
		<?php if($showErrors) { ?>
		<div class="error error-data">
			<div><strong>Error Code</strong> : <?php echo $errno; ?></div>
			<div><strong>Error Message</strong> : <?php echo $errstr; ?></div>
			<!-- div><strong>Error File</strong> : <?php echo $errfile; ?></div>
			<div><strong>Error Line</strong> : <?php echo $errline; ?></div-->
		</div>
		<?php } ?>
	</div>	
</body>
</html>
 
