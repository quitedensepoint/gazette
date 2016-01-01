<?php
/* 
This page is the index (main) page for the Gazette
It has the layout and look of the original page.    
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
?>
<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>        
    <title>World@War Gazette</title>
	<link rel='stylesheet' href='assets/css/gazette.css'>
</head>

<body>
	<div id="container">
		<div id='top' >
			<img src='assets/img/header.gif'  id='headerMiddle'>
<!-- Allied Deaths --> 
			<table id='alliedDeaths'>
				<tr>
					<th class='paperwhite'><span>ALLIED CASUALTIES</span></th>
				<tr>
				<tr>
					<td class='paperarialsmall'>
						<?php
							echo "GROUND FORCES: ".$casualtyData['allied']['ground']."<br>AIR FORCES: ".$casualtyData['allied']['air']."<br>SEA FORCES: ".$casualtyData['allied']['sea']."\n";
						?>
					</td>
				</tr>
			</table>
<!-- Axis Deaths -->
			<table id='axisDeaths'>
				<tr>
					<th bgcolor='#333333' class='paperdefault'><span class='paperwhite'>AXIS CASUALTIES</span></th>
				</tr>
				<tr>
					<td class='paperarialsmall' style="text-align: right;">
						<?php
							echo "GROUND FORCES: ".$casualtyData['axis']['ground']."<br>AIR FORCES: ".$casualtyData['axis']['air']."<br>SEA FORCES: ".$casualtyData['axis']['sea']."\n";
						?>
					</td>
				</tr>
			</table>
		</div>
<!-- Start of re-imaged page 940px wide -->
		<div id='mainBody' >
			<table>
				<tr>
<!-- Version Info and Navigation Section -->
					<td colspan='5'>
							<table style="width: 100%; border: 1px black solid;">
								<tr>
									<td style="width: 33%; text-align: left;"><span class='papertimesmedium' style="color: #cc3333;">CURRENT VERSION: 1.34.15</span></td>
									<td style="width: 33%; text-align: center;"><span class='papertimesmedium'><?php echo "Campaign:".$row['id']." Day: ".$days; ?></span></td> 
									<td style="width: 33%; text-align: right;"><span class='papertimesmedium'>Coming Soon: <a href="">ALLIED Section</a> & <a href="">AXIS Section</a></span></td>
								</tr>
							</table>
					</td>
				</tr>
				<tr> 
<!-- Main Headline --> 
					<td colspan='5'>
						<span class='paperheadline'><?= $indexMainHeadline ?></span>
					</td>
				</tr>
<!-- Top Left Story | Map | Top Right Story Row -->
				<tr>
<!-- Allied Stats Top Left Story-->
					<td id='topLeftStory'>
						<?= $indexAlliedStats2 ?>
					</td>
<!-- Front Line Map -->
					<td rowspan='2' colspan='3'>
						<a href='https://webmap.wwiionline.com'><img src='https://webmap.wwiionline.com/Library/images/GazetteFrontPage.png'></a>
						<span id="mapText" class='paperwhite'>GAME MAP UPDATES EVERY 15 MINUTES - <a href='https://webmap.wwiionline.com' style="color: yellow;">CLICK FOR FULL SIZE</a></span>
					</td>
<!-- Attacks Captures Top Right Story -->
					<td id='topRightStory'> 
						<span class='paperarialbig'><b>Current<br>Attack Objectives:</b></span>
						<br><br><br>
						<b>Allied</b><br>
						<table style="width: 100%; align: center">
							<?php 
								while($row=$aoals->fetch_assoc())
									{{echo "<tr><td style='width:50%; text-align:center;'>".$row['name']."</td>";}
									if (isset($row['alcon']) && $row['alcon']=='1') 
									{echo "<td style='width: 50%; text-align: center; color:red;'><i>Contested</i></td></tr>";} 
									else {echo "<td style='width: 50%; text-align: center;'><br></td></tr>";}}
								   
							?>
						</table>
						<br>
						<b>Axis</b><br>
						<table style="width: 100%; align: center">
							<?php 
								while($row=$aoaxs->fetch_assoc())
									{{echo "<tr><td style='width:50%; text-align:center; '>".$row['name']."</td>";} 
									if (isset($row['axcon']) && $row['axcon']=='1')
									{echo "<td style='width: 50%; text-align: center; color:red;'><i>Contested</i></td></tr>";}
									else {echo "<td style='width: 50%; text-align: center;'><br></td></tr>";}}
							?>
						</table>

					</td>
				</tr>
<!-- Middle Top Left | Map | Middle Top Right Row -->
				<tr>
<!-- Old Advertising spot Left in in case still wanted/needed could also be used for Propa-->
					<td id="middleTopLeftStory">
						<hr style="width: 90%;">
						<!-- Code for image rotation -->
						<?php
							$iDir='assets/img/post/';
							$image = glob ($iDir.'*.{jpg,png,gif}', GLOB_BRACE);
							$selImage = $image[array_rand($image)];
						?>
						<!-- End rotation Code -->
						<img id="poster" src="<?php echo $selImage ?>"> 
					</td>
<!-- Recent Town Captures -->
					<td id="middleTopRightStory"> 
						<hr style="width: 90%;">
						<span class='paperarialbig'><b>Most Recent Captures:</b></span>
						<br><br>
						<table>
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
<!-- Middle Bottom Left | News | Middle Bottom Right Row -->
				<tr>
<!-- Allied Stats Middle Bottom Left Story-->
					<td id="middleBottomLeftStory">
						<?= $indexAlliedStats1 ?>
					</td>
<!-- Latest News -->
				<td id="news"colspan='3'>    
					<?php
						$newsPage = file_get_contents("http://www.battlegroundeurope.com/index.php");
								preg_match('/<table class="contentpaneopen">(.+?)<span class="article_separator">/s',$newsPage,$firstArticle);
						$firstArticle = str_replace('<span class="override">', '', $firstArticle[0]);
						$firstArticle = str_replace('<a href="/index.php', '<a href="http://www.battlegroundeurope.com/index.php', $firstArticle);
						$firstArticle = str_replace('class="contentpaneopen"', 'class="contentpaneopen" width="100%"', $firstArticle);
						echo $firstArticle;
					?><?php /*
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td colspan='2'><hr></td>
                        </tr>
                        <tbody>
							<tr>
                                <td align="left" valign="top">
                                    <!--Allied British RDP Story #1 -->
                                    <table width="100%" border="0" cellspacing="0" cellpadding="5">
										<tbody>
											<tr valign="top" align="left"> 
												<td> 
													<span class="paperdefault"><?= $indexAlliedBritishRDP1 ?></span>
												</td>
											</tr>
										</tbody>
									</table>
                                  </td>
                                <td align="left" valign="top">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="5">
										<tbody>
											<tr align="left" valign="top"> 
												<td> 
													<span class="paperdefault"><?= $indexAlliedFrenchRDP1 ?></span>
												</td>
											</tr>
										</tbody>
									</table>
                                </td>
                            </tr>
                        </tbody>
					</table>					
				</td>*/?>
<!-- Old Advertising spot Right in in case still wanted/needed could also be used for Propa-->
					<td id="middleBottomRightStory">
						<hr style="width: 90%;">
						<!-- Code for image rotation -->
						<?php
							$iDir='assets/img/post/';
							$image = glob ($iDir.'*.{jpg,png,gif}', GLOB_BRACE);
							$selImage = $image[array_rand($image)];
						?>
						<!-- End rotation Code -->
						<img id="poster" src="<?php echo $selImage ?>"> 
					</td>
				</tr>
		 <?php /*   
			<!-- Breakpoint for Short Page -->
			<tr>
				<td>Currently Unused - Dropdown for allied stats 1</td>
				<td align='center' valign='middle'>This space is wide open for whatever.  Maybe a good infantry soldier pic, or an image that somehow ties into the article... Ie if it's about an air sortie, maybe show the type of aircraft the article features....</td>
				<td colspan='2' valign='top'>
				 <!-- START Second story -->
			<!-- #include file='/home/bv55/scriptinc/paper/index_axis_stats2.html' --> 
														<table width='100%' height='100' valign='bottom' border='0' cellspacing='0' cellpadding='0'>
																	<tr align='left' valign='top'> 
																		<td> 
																			<font size='3' face='Times New Roman, Times, serif'>
																			<b><span class='papertimesbig'>Random Allied player stats generated Story 1</span>                                                               </b>
																			</font>
																			<br>
																			<font size='1' face='Arial, Helvetica, sans-serif'>
																			<span class='paperdefault'>May have to re-organize the page a bit as it wasn't built with 3 allied armies in mind.  And the Italians are also coming (eventually).  This entire little blurb is about 47 words total, as you can see plenty of room for additional stuff to be added. </span>
																			</font>
																		</td>
																	</tr>
																	<tr>
																		<td><br /><a href='allied.php' class='paperdefault'>[more in Allied section]</a></td>
																	</tr>
														</table>
			<!-- #include file='/home/bv55/scriptinc/paper/index_axis_stats2.html' --> 
			<!-- end 2nd story -->
				</td>
				
			<td>
			<!--Axis Stats Story #1 -->
		<!-- #include file='/home/bv55/scriptinc/paper/index_axis_stats1.html' -->
									  <table width='100%' border='0' cellspacing='0' cellpadding='5'>
										<tr align='left' valign='top'> 
										<td> 
										<font size='4' face='Times New Roman, Times, serif'>
										<b>
										<span class='papertimeshuge'>Index_axis Stats 1</span>
										</b>
										</font>
										<br>
										<font size='1' face='Arial, Helvetica, sans-serif'>
										<span class='paperdefault'>Allied armor units probed the 327 mile German frontlines last night. Flashes could be seen from miles around as the two sides clashed in small engagements designed to  punch holes in the Axis lines. Rumours of PzIVD reinforcements circulated among the ranks of the German army.</span>
												
										</font>
										</td>
										</tr>
										</table>
		<!-- #include file='/home/bv55/scriptinc/paper/index_axis_stats1.html' -->
			</td>
			</tr>
			<tr>
		 
			   <td colspan='2' valign='top'> <!-- START ALLIED RDP REPORT -->
					<table width='100%' border='0' cellspacing='0' cellpadding='0' name='below_map_content2' bordercolor='#FF3366'>
						<tr valign='top' >
							<td bgcolor='#000000'><img height=1></td>
						</tr>
					   <tr valign='top' >
							<td>
								<table align ='center' border='0' cellspacing='0' cellpadding='5' name='allied_rdp_headline_content' bordercolor='#000000'>
									<tr>
										<td class='paperarialhuge'> <b>Allied RDP Headline</b> </td>
									</tr>
								</table>
							</td>
						</tr>
						<tr valign='top'>
							<td>
								<table border='0' cellspacing='0' cellpadding='0'>
									<tr align='center'>
										<td size='188' > 
		<!--Allied British RDP Story #1 -->
		<!-- #include file='/home/bv55/scriptinc/paper/index_allied_british_rdp1.html' -->
											<table  border='0' cellspacing='0' cellpadding='5'>
											<tr valign='top' align='left'> 
											<td> 
											<font face='Arial, Helvetica, sans-serif' size='1'>
											<span class='paperdefault'>British RDP_1<br/> This will go down the page a bit as story data fills it</span>
											</font>
											</td>
											</tr>
											</table>
		<!-- #include file='/home/bv55/scriptinc/paper/index_allied_british_rdp1.html' -->
										</td>
										<td size='188'> 
		 <!--Allied French RDP Story #1 -->
		 <!-- #include file='/home/bv55/scriptinc/paper/index_allied_french_rdp1.html' -->
											<table border='0' cellspacing='0' cellpadding='5'>
											<tr align='left' valign='top'> 
											<td> 
											<font face='Arial, Helvetica, sans-serif' size='1'>
											<span class='paperdefault'>French RDP 1<br/> This will go down the page a bit as story data fills it</span>
											</font>
											</td>
											</tr>
											</table>
		 <!-- #include file='/home/bv55/scriptinc/paper/index_allied_french_rdp1.html' -->
									   </td>
									</tr>
									<tr>
										<td align='center' colspan='2'> <a href='allied.php' class='paperarialmedium'><b>[See 'RDP Reports' in Allied Section]</b></a> </td>
									</tr>
									<tr valign='bottom' align='left'>
										<td colspan='2' align='center'> 
		<!--Random Stats Story -->
		<!-- #include file='/home/bv55/scriptinc/paper/index_randomstats.html' -->

											<table border='0' cellspacing='0' cellpadding='5'>
											<tr align='left' valign='top'> 
											<td> 
											<font size='4' face='Times New Roman, Times, serif'>
											<b>
											<span class='papertimesbig'>Index_randomstats</span></b>
											</font>
											<br>
											<font size='1' face='Arial, Helvetica, sans-serif'>
											<span class='paperdefault'>The latest heavy attacks by Allied bombers at D�sseldorf has caused factory output to drop to 97% at that city.</span></
											></font>
											</td>
											</tr>
											</table> 
		<!-- #include file='/home/bv55/scriptinc/paper/index_randomstats.html' -->

										</td>
								</table> 
							</td>
						</table>  
				</td>              
				<td align='center' valign='middle'>This Space Available for Rent</td>
				<td align='center' valign='middle'>This Space Available for Rent</td>
				<td align='center' valign='middle'>This Space Available for Rent</td>                        
			 </tr>     
		<!-- END ALLIED RDP REPORT -->
		   

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
</body>
</html>
 

