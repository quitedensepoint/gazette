<?php
/* 
This page is the index (main) page for the Gazette
It has the layout and look of the original page. 
This page is a table layout that consists of 5 cells across   
*/

require(__DIR__ . '/../DBconn.php');
require(__DIR__ . '/../include/dbhelper.php');
require(__DIR__ . '/../processors/casualty-processor.php');
require(__DIR__ . '/../processors/campaign.php');
require(__DIR__ . '/../processors/aocap.php');

$casualtyProcessor = new CasualtyProcessor($dbconn);
$casualtyData = $casualtyProcessor->process();

/**
 * Pull the generated reports from the file system. They were stored here as the 
 * easiest way to cache the story data until it is regenerated.
 * 
 * @todo Need a better caching system
 */
$indexMainHeadline = file_get_contents(__DIR__ .'/../cache/index_main_headline.php');
$indexAlliedStats2 = file_get_contents(__DIR__ .'/../cache/index_allied_stats2.php');
$indexAlliedStats1 = file_get_contents(__DIR__ .'/../cache/index_allied_stats1.php');
$indexAlliedBritishRDP1 = file_get_contents(__DIR__ .'/../cache/index_allied_british_rdp1.php');
$indexAlliedFrenchRDP1 = file_get_contents(__DIR__ .'/../cache/index_allied_french_rdp1.php');
$indexRandomStat = file_get_contents(__DIR__ .'/../cache/index_randomstats.php');
$indexAxisStats2 = file_get_contents(__DIR__ .'/../cache/index_axis_stats2.php');




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
			<span id="version">Version: 2.0</span>
<!-- Allied Deaths --> 
			<table id='alliedDeaths'>
				<tr>
					<th class='paperwhite'>ALLIED CASUALTIES</th>
				</tr>
				<tr>
					<td class='paperarialsmall'>
						<?php
							echo "GROUND FORCES: ".number_format($casualtyData['allied']['ground'])."<br>AIR FORCES: ".number_format($casualtyData['allied']['air'])."<br>SEA FORCES: ".number_format($casualtyData['allied']['sea'])."\n";
						?>
					</td>
				</tr>
			</table>
<!-- Axis Deaths -->
			<table id='axisDeaths'>
				<tr>
					<th class='paperwhite'>AXIS CASUALTIES</th>
				</tr>
				<tr>
					<td class='paperarialsmall' style="text-align: right;">
						<?php
							echo "GROUND FORCES: ".number_format($casualtyData['axis']['ground'])."<br>AIR FORCES: ".number_format($casualtyData['axis']['air'])."<br>SEA FORCES: ".number_format($casualtyData['axis']['sea'])."\n";
						?>
					</td>
				</tr>
			</table>
		</div>
<!-- Version Info and Navigation Section -->
        <div id='info'>
            <table id='infoBar'>
                <tr>
				    <td style="width: 33%; text-align: left;"><span class='papertimesmedium' style="color: #cc3333;">CURRENT VERSION: 1.34.15</span></td>
					<td style="width: 33%; text-align: center;"><span class='papertimesmedium'><?php echo "Campaign:".$row['id']." Day: ".$days->format('%a'); ?></span></td> 
					<td style="width: 33%; text-align: right;"><span class='papertimesmedium'><a href="allied.php">ALLIED Section</a> & AXIS Section</span></td>
			    </tr>
		    </table>
        </div>
<!-- Main Headline --> 
        <div id="mainHeadline" class="mainheadline">
            <?= $indexMainHeadline ?>
            <div class='top'>  </div> <!-- sets an absolute point at the story bottom for the poster to be relative to -->
        </div>
<!-- Top Row: topLeftStory | Map | Top Right Story Row  (AO's)-->
<!-- top L Story -->
        <div id="top">
            <div id="topLeftStory">
				<hr style="width: 90%; margin-top: -5px;">
                <div class="story-detail topLeftStory"><?= $indexAlliedStats2 ?></div>
            </div>
<!-- map -->
            <div id="map">
				<a href="https://webmap.wwiionline.com"><img src="https://webmap.wwiionline.com/Library/images/GazetteFrontPage.png" width="535"></a>
				<span id="mapText" class='paperwhite'>GAME MAP UPDATES EVERY 15 MINUTES - <a href='https://webmap.wwiionline.com' style="color: yellow;" target="_blank">CLICK FOR FULL SIZE</a></span>
            </div>
<!-- AO info -->
            <div id="ao">
				<hr style="width: 90%; margin-top: 0;">
				<table width="172px">
					<tr>
						<td>
							<b><span class='paperarialbig'><b>Current<br>Attack Objectives:</b></span>
							<br><br>
							<b>Allied</b><br>
							<table style="width:100%">
								<?php 
									while($row=$aoals->fetch_assoc()){
										echo "<tr><td style='width:50%; text-align:center;'>".$row['name']."</td>";
										if(isset($row['alcon']) && $row['alcon']=='1'){
											echo "<td style='width: 50%; text-align: center; color:red;'><i>Contested</i></td></tr>";
										}else{
											echo "<td style='width: 50%; text-align: center;'>Not Contested</td></tr>";
										}
									}
								?>
							</table>
							<br>
							<b>Axis</b>
							<br>
							<table style="width:100%">
								<?php 
									while($row=$aoaxs->fetch_assoc()){
										echo "<tr><td style='width:50%; text-align:center; '>".$row['name']."</td>"; 
										if(isset($row['axcon']) && $row['axcon']=='1'){
											echo "<td style='width: 50%; text-align: center; color:red;'><i>Contested</i></td></tr>";
										}else{
											echo "<td style='width: 50%; text-align: center;'>Not Contested</td></tr>";
										}
									}
								?>
							</table>
	<!-- Recent Captures under AO's to allow for capture cell to shift up/down for Ao # changes -->
							<hr style="width: 90%;">
							<span class='paperarialbig'><b>Most Recent Captures:</b></span>
							<br><br>
							<table style='width:100%'>
								<tr align='center'>
									<th>City</th>
									<th>By</th>
								</tr>
								<?php 
									while($row=$caps->fetch_assoc())
						 
									If (($row['fullName'])=='GERMANY')
									{
										echo "<tr style='text-align: center;'><td>".$row['name']."</td><td style='color: red; text-align: center'><i>".$row['fullName']."</i></td></tr>"; 
									}
									else
									{   echo "<tr><td style='text-align: center;'>".$row['name']."</td><td style='color: blue; text-align: center'><i>".$row['fullName']."</i></td></tr>";}
								?>
							</table>
						</td>
					</tr>		    
				</table>
            <!-- Poster @ R lower edge of map -->
                    <div id='middleBottomRightPic'>
						<hr style="width: 90%;">
						<!-- Code for image rotation -->
						<?php
							$iDir='assets/img/post/';
							$image = glob ($iDir.'*.{jpg,png,gif}', GLOB_BRACE);
							$selImage = $image[array_rand($image)];
						?>
						<!-- End rotation Code -->
						<img id="poster" src="<?php echo $selImage ?>" style="width: 170px;"> 
                    </div>
                     <div id='top'>  </div>  
<!-- Article Below Poster Right Side-->
			<div  id='bottomRightStory' class="story-detail">
                <?= $indexAxisStats2 ?>
            </div>
         </div>
<!-- Row 2: Left (image) Middle Top Left pic | | -->
<!-- Poster @ Lower Left of Map Image -->
            <div id='middleTopLeftpic'>
            <hr style='width: 90%;'>
						    <!-- Code for image rotation -->
						    <?php
							    $iDir='assets/img/post/';
							    $image = glob ($iDir.'*.{jpg,png,gif}', GLOB_BRACE);
							    $selImage = $image[array_rand($image)];
						    ?>
						    <!-- End rotation Code -->
						    <img id="poster" src="<?php echo $selImage ?>"> 
            </div><div id='top'>
<!-- Article Below Poster Left Side-->
			<div  id='middleBottomLeftStory' class="story-detail">
                <?= $indexAlliedStats1 ?>
            </div>
        </div>



        <div id='underNews'>
            
<!-- latest community event/News -->
            <div id="news">    
					    <?php
						    $newsPage = file_get_contents("http://www.battlegroundeurope.com/index.php");
								    preg_match('/<table class="contentpaneopen">(.+?)<span class="article_separator">/s',$newsPage,$firstArticle);
						    $firstArticle = str_replace('<span class="override">', '', $firstArticle[0]);
						    $firstArticle = str_replace('<a href="/index.php', '<a href="http://www.battlegroundeurope.com/index.php', $firstArticle);
						    $firstArticle = str_replace('class="contentpaneopen"', 'class="contentpaneopen" width="100%"', $firstArticle);
						    echo $firstArticle;
					    ?>
            
            </div>

<!-- START ALLIED RDP -->
            <div id='belowNews'>
            <table style='width: 537px'>
				<tr>
					<td>
						<table border="0" style="width: 100%; cellspacing: 0; cellpadding: 0;">
							<tr>
								<td><hr width="90%"></td>
							</tr>
							 <tr>
								<td colspan="2" class="paperarialhuge" style="text-align: center;"><b>Allied Industry Answers the Call</b></td>
							</tr>
							<tr>
								<td>
									<table>
										<tr>
<!--Allied British RDP Story #1 -->
											<td style="text-align: center;"> 
												<span class="papertimesbig">British Notes</span>
												<br>
												<span class="paperarialmedium story-detail"><?= $indexAlliedBritishRDP1 ?></span>
											</td>
<!--Allied French RDP Note --> 
											<td style="text-align: center;"> 
												<span class="papertimesbig">French Notes</span>
												<br>
												<span class="paperarialmedium story-detail"><?= $indexAlliedFrenchRDP1 ?></span>
										   </td>
										</tr>
										<?php /*
							<!-- comment out until allied page is complete
										<tr>
											<td align='center' colspan='2'> <a href='allied.php' class='paperarialmedium'><b>[See 'RDP Reports' in Allied Section]</b></a> </td>
										</tr>
										<tr>
											<td colspan='2' align='center'><hr width='90%'>
	<!--Random Stats Story ----- Commented out as the story has to be short... longer stories throw the page off. 
												<span class='paperdefault' style='text-align:center'><?= $indexRandomStat?></span>
												</td>
												</tr>*/?>
									</table> 
								</td>
							</tr>
						</table> 
					</td>
				</tr>
            </table>  
			
				</td>              

			 </tr>  
        </table>
        </div>   
<!-- END ALLIED RDP REPORT -->
            </div>
 <?php /*
			</tr>
			<tr>
				<td colspan='5' class='story'>
					<table width='100%' border='0' cellspacing='2' cellpadding='0'>
						<tr>
							<td align='center' valign='middle' bgcolor='#000000' class='paperhidden'>Admit it, you missed this ...... </td>
						</tr>
						<tr>
							<td align='center' valign='middle' bgcolor='#000000' class='paperhidden'>RIP Buckeyes! | WILLYTEE RAWKS | SNAKES ON A PLANE | BLANGETT IS SEXEH | CLINTNOMOD</td>
						</tr>
					 </table>
				 </td>
			</tr>
		<! -- END of SHort Page Breakpoint -->
		 */?>  
			</table>
		</div>
	</div>
<?php
	if (isset($options) && isset($options['ga-active']) && $options['ga-active'] === true)
	{
?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-71615394-1', 'auto');
  ga('send', 'pageview');

</script>	
<?php
	}
?>
</body>
</html>
 
