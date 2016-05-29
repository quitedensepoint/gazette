<?php
// For Allied side specific information 

// Enable output buffering - this can increase performance and allows us to handle errors better
ob_start();

//include 'testkills.php';
include'assets/alliedstats.php';
require(__DIR__ . '/../DBconn.php');


$stat1 = file_get_contents(__DIR__ .'/../cache/playnow_allied_stats1.php');
$stat2 = file_get_contents(__DIR__ .'/../cache/playnow_allied_stats2.php');
$stat3 = file_get_contents(__DIR__ .'/../cache/playnow_allied_stats3.php');
$stat4 = file_get_contents(__DIR__ .'/../cache/playnow_allied_stats4.php');
$funit = file_get_contents(__DIR__ .'/../cache/playnow_allied_french_spawn.php');
$bunit = file_get_contents(__DIR__ .'/../cache/playnow_allied_british_spawn.php');
$uunit = file_get_contents(__DIR__ .'/../cache/playnow_allied_us_spawn.php');
$promo = file_get_contents(__DIR__ .'/../cache/playnow_allied_promotion.php');

?>
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>        
    <title>World@War Allied Gazette</title>
	<link rel='stylesheet' href='assets/css/alliedgazette.css'>
	<link rel="shortcut icon" href="assets/img/favicon.ico" />
</head>

<body>
    <div id='container'>
        <div id='top'>
            <table id='infoBar'>
                <tr>
                    <td style="width: 33%; text-align: left;"><span class='papertimesmedium'>WHATS COMING NEXT</span></td>
                    <td style="width: 33%; text-align: center;"><span class='papertimesmedium'><a href="/">READ THE FRONT PAGE</a></span></td>
                    <td style="width: 33%; text-align: right;"><span class='papertimesmedium'><a href='axis.php'>READ THE AXIS SECTION</a></span></td>
                </tr>
            </table>
        </div>
        <div>
            <span id ="mainHeadline" class="paperheadline"><?php echo "<b>ALLIED FORCES EDITION</b>"; ?></SPAN>
        </div>
        <div>
            <br>
            <img src="assets/img/header.gif" id="imagegazette">
			<span id="version">Version: 2.2</span>
        </div>
        <div id="topLeft" >
            <span class="papertimeshuge" ><?php echo"FROM THE<br /> ALLIED COMMAND"; ?></span>
        </div>
        <div class='clear'></div>
<!-- TOP ROW: HC recruiting | Small Story | Stats Leaderboard Block -->
        <div id="topLeftOuterStory">
            <span class="papertimesmedium"><b>HIGH COMMAND SEEKING OFFICERS</b><br />
                    <hr style='width:90%; margin-top:0;'>The Allied Side is calling out to all allied players of yesterday and today. 
                        The team is working together and we need <b>YOUR</b> help.  Victory can only be earned if all allied players team together and work towards a common goal.
                        The allied side needs players willing to step forth from the ranks to be leaders.<br /><b>YOU</b> can be that leader<br /><BR />
                        <a href='http://battlegroundeurope.net/ocs' target='_blank'>CLICK HERE TO LEARN MORE</a></span>
        </div>
        <div id="topLeftInnerStory">
            <span class="papertimesmedium"><?php echo $stat2;  ?></span>
        </div>
        <div id="leader">
            <span class="papertimeshuge"><b>ALLIED LEADERBOARD</b></span>
            <!--<span class="paptertimesmedium"><br>Updated Hourly</span>-->
            <hr style="width:90%; margin-top:0;">
			<div id="dailyLeader">
                <span class="papertimesmedium">
					<table style="width: 100%; border-spacing: 1px; border-collapse: separate;" >
                        <tr align='center'><th colspan='2'>Last 24 Hours</th></tr>
                        <tr align='center'><td colspan='2'><hr style="width:100%; margin-top:0;"></td></tr>
                        <tr align='center'><td><b>Total</b></td><td><b>Player</b></td></tr>
                        <tr align='center'><td><?php while ($row = $daa->fetch_assoc()){ echo round($row['aaapoints'],0)."</td><td><i>".ucfirst($row['aaacallsign'])."</i>";} ?></td></tr>
                        <tr align='center'><td><?php while ($row = $datg->fetch_assoc()){ echo round($row['atgpoints'],0)."</td><td><i>".ucfirst($row['atgcallsign'])."</i>";} ?></td></tr>
                        <tr align='center'><td><?php while ($row = $datr->fetch_assoc()){ echo round($row['atrpoints'],0)."</td><td><i>".ucfirst($row['atrcallsign'])."</i>";} ?></td></tr>
                        <tr align='center'><td><?php while ($row = $deng->fetch_assoc()){ echo round($row['engpoints'],0)."</td><td><i>".ucfirst($row['engcallsign'])."</i>";} ?></td></tr>
                        <tr align='center'><td><?php while ($row = $dgren->fetch_assoc()){ echo round($row['grenpoints'],0)."</td><td><i>".ucfirst($row['grencallsign'])."</i>";} ?></td></tr>
                        <tr align='center'><td><?php while ($row = $dlmg->fetch_assoc()){ echo round($row['lmgpoints'],0)."</td><td><i>".ucfirst($row['lmgcallsign'])."</i>";} ?></td></tr>
                        <tr align='center'><td><?php while ($row = $dmort->fetch_assoc()){ echo round($row['mortpoints'],0)."</td><td><i>".ucfirst($row['mortcallsign'])."</i>";} ?></td></tr>
                        <tr align='center'><td><?php while ($row = $drifle->fetch_assoc()){ echo round($row['rpoints'],0)."</td><td><i>".ucfirst($row['rcallsign'])."</i>";} ?></td></tr>
                        <tr align='center'><td><?php while ($row = $dsmg->fetch_assoc()){ echo round($row['smgpoints'],0)."</td><td><i>".ucfirst($row['smgcallsign'])."</i>";} ?></td></tr>
                        <tr align='center'><td><?php while ($row = $dsniper->fetch_assoc()){ echo round($row['sniperpoints'],0)."</td><td><i>".ucfirst($row['snipercallsign'])."</i>";} ?></td></tr>
                        <tr align='center'><td><?php while ($row = $dtank->fetch_assoc()){ echo round($row['tankpoints'],0)."</td><td><i>".ucfirst($row['tankcallsign'])."</i>";} ?></td></tr>
                        <tr align='center'><td><?php while ($row = $dtruck->fetch_assoc()){ echo round($row['truckpoints'],0)."</td><td><i>".ucfirst($row['truckcallsign'])."</i>";} ?></td></tr>
                        <tr align='center'><td><br /></td></tr>
                        <tr align='center'><td><?php while ($row = $dfight->fetch_assoc()){ echo round($row['fightpoints'],0)."</td><td><i>".ucfirst($row['fightcallsign'])."</i>";} ?></td></tr>
                        <tr align='center'><td><?php while ($row = $dbomb->fetch_assoc()){ echo round($row['bombpoints'],0)."</td><td><i>".ucfirst($row['bombcallsign'])."</i>";} ?></td></tr>
                        <tr align='center'><td><br /></td></tr>
                        <tr align='center'><td><?php while ($row = $ddd->fetch_assoc()){ echo round($row['ddpoints'],0)."</td><td><i>".ucfirst($row['ddcallsign'])."</i>";} ?></td></tr>
                        <tr align='center'><td><?php while ($row = $dpb->fetch_assoc()){ echo round($row['pbpoints'],0)."</td><td><i>".ucfirst($row['pbcallsign'])."</i>";} ?></td></tr>
                        <tr align='center'><td><?php while ($row = $dtt->fetch_assoc()){ echo round($row['ttpoints'],0)."</td><td><i>".ucfirst($row['ttcallsign'])."</i>";} ?></td></tr>
                        <tr><td colspan='2'><hr style="width:100%"></td></tr>   
                        <tr align='center'><td><b>Total</b></td><td><b>Player</b></td></tr>
                        <tr align='center'><td><?php while ($row = $dkills->fetch_assoc()){ echo $row['kills']."</td><td><i>".ucfirst($row['killscallsign'])."</i></td>";} ?> </td></tr>
                        <tr align='center'><td><?php while ($row = $dcaps->fetch_assoc()){ echo $row['caps']."</td><td><i>".ucfirst($row['capscallsign'])."</i></td>";} ?></td></tr>
                        <tr align='center'><td><?php  while ($row = $dkd->fetch_assoc()){ echo $row['kd']."</td><td><i>".ucfirst($row['kdcallsign'])."</i></td>";} ?></td></tr>
                        <tr align='center'><td><?php  while ($row = $dtom->fetch_assoc()){ echo $row['tom']."</td><td><i>".ucfirst($row['tomcallsign'])."</i></td>";} ?></td></tr>
                        <tr align='center'><td><br /></td></tr>
                        <tr align='center'><td><?php  while ($row = $dkrow->fetch_assoc()){ echo $row['krowpoints']."</td><td><i>".ucfirst($row['krowcallsign'])."</i></td>";} ?></td></tr>
                        <tr align='center'><td><?php  while ($row = $dcrow->fetch_assoc()){ echo $row['crowpoints']."</td><td><i>".ucfirst($row['crowcallsign'])."</i></td>";} ?></td></tr>
                        <tr><td colspan='2'><hr style="width:100%"></td></tr>
                        <tr align='center'><td><b>Total</b></td><td><b>Player</b></td</tr>
                        <tr align='center'><td><?php while ($row = $dkstreak->fetch_assoc()){ echo $row['sortmkill']."</td><td><i>".ucfirst($row['sortmkillcallsign'])."</i></td>";} ?></td></tr>
                        <tr align='center'><td><?php  while ($row = $dcapstreak->fetch_assoc()){ echo $row['capstreak']."</td><td><i>".ucfirst($row['sortcapscallsign'])."</i></td>";}  ?></td></tr>                    
					</table>
				</span>
			</div>
        <div id="catIDLeader">
            <span class="papertimesmedium">
                <table style="width: 100%; border-spacing: 1px; border-collapse: separate;" >
                    <tr><th>Category</th></tr>
                    <tr><td><hr style="width: 100%; margin-top: 0;"></td></tr>
                    <tr><td><b>By Unit - Ground</b></td></tr>
                    <tr><td>Anti-Air Gun</td></tr>
                    <tr><td>Anti-Tank Gun</td></tr>
                    <tr><td>ATR</td></tr>
                    <tr><td>Engineer</td></tr>
                    <tr><td>Grenadier</td></tr>
                    <tr><td>LMG</td></tr>
                    <tr><td>Mortarman</td></tr>
                    <tr><td>Rifleman</td></tr>
                    <tr><td>SMG</td></tr>
                    <tr><td>Sniper</td></tr>
                    <tr><td>Tanker</td></tr>
                    <tr><td>Trucker</td></tr>
                    <tr><td><b>By Unit - Air</b></td></tr>
                    <tr><td>Fighter</td></tr>
                    <tr><td>Bomber</td></tr>
                    <tr><td><b>ByUnit - Navy</b></td></tr>
                    <tr><td>Destroyer</td></tr>                                                        
                    <tr><td>Patrol Boat</td></tr>
                    <tr><td>Freighter</td></tr>
                    <tr><td><hr style="width: 100%;"></td></tr>
                    <tr><th>By Action</th></tr>
                    <tr><td>Most Kills</td></tr>
                    <tr><td>Most Caps</td></tr>
                    <tr><td>Best K/D</td></tr>
                    <tr><td>Top TOM</td></tr>
                    <tr><td><b>Cons. Sorties</b></td></tr>
                    <tr><td>With a kill</td></tr>
                    <tr><td>With a Capture</td></tr>
                    <tr><td><hr style="width: 100%;"></td></tr>
                    <tr><td><b>Single Sortie</b></td></tr>
                    <tr><td>Most kills</td></tr>
                    <tr><td>Most Capture</td></tr>
                    <tr><td><span class="paperdefault"><i>*Updated Hourly*</i></span></td></tr>
                </table>
            </span>
        </div>
        <div id="campaignLeader">
            <span class="papertimesmedium">
                <table style="width: 100%; border-spacing: 1px; border-collapse: separate;" >
                    <tr align='center'><th colspan='2'>Campaign Leaders</th></tr>
                    <tr><td colspan='2'><hr style="width:100%; margin-top:0;"></td></tr>
                    <tr align='center'><td><b>Player</b></td><td><b>Total</b></td></tr>
                    <tr align='center'><td><?php while ($row = $taa->fetch_assoc()){ echo "<i>".ucfirst($row['aaacallsign'])."</i></td><td>".round($row['aaapoints'],0)."";} ?></td></tr>
                    <tr align='center'><td><?php while ($row = $tatg->fetch_assoc()){ echo "<i>".ucfirst($row['atgcallsign'])."</i></td><td>".round($row['atgpoints'],0)."";} ?></td></tr>
                    <tr align='center'><td><?php while ($row = $tatr->fetch_assoc()){ echo "<i>".ucfirst($row['atrcallsign'])."</i></td><td>".round($row['atrpoints'],0)."";} ?></td></tr>
                    <tr align='center'><td><?php while ($row = $teng->fetch_assoc()){ echo "<i>".ucfirst($row['engcallsign'])."</i></td><td>".round($row['engpoints'],0)."";} ?></td></tr>
                    <tr align='center'><td><?php while ($row = $tgren->fetch_assoc()){ echo "<i>".ucfirst($row['grencallsign'])."</i></td><td>".round($row['grenpoints'],0)."";} ?></td></tr>
                    <tr align='center'><td><?php while ($row = $tlmg->fetch_assoc()){ echo "<i>".ucfirst($row['lmgcallsign'])."</i></td><td>".round($row['lmgpoints'],0)."";}?></td></tr>
                    <tr align='center'><td><?php while ($row = $tmort->fetch_assoc()){ echo "<i>".ucfirst($row['mortcallsign'])."</i></td><td>".round($row['mortpoints'],0)."";} ?></td></tr>
                    <tr align='center'><td><?php while ($row = $trifle->fetch_assoc()){ echo "<i>".ucfirst($row['rcallsign'])."</i></td><td>".round($row['rpoints'],0)."";} ?></td></tr>
                    <tr align='center'><td><?php while ($row = $tsmg->fetch_assoc()){ echo "<i>".ucfirst($row['smgcallsign'])."</i></td><td>".round($row['smgpoints'],0)."";} ?></td></tr>
                    <tr align='center'><td><?php while ($row = $tsniper->fetch_assoc()){ echo "<i>".ucfirst($row['snipercallsign'])."</i></td><td>".round($row['sniperpoints'],0)."";} ?></td></tr>
                    <tr align='center'><td><?php while ($row = $ttank->fetch_assoc()){ echo "<i>".ucfirst($row['tankcallsign'])."</i></td><td>".round($row['tankpoints'],0)."";} ?></td></tr>
                    <tr align='center'><td><?php while ($row = $ttruck->fetch_assoc()){ echo "<i>".ucfirst($row['truckcallsign'])."</i></td><td>".round($row['truckpoints'],0)."";} ?></td></tr>
                    <tr><td><br /></td></tr>
                    <tr align='center'><td><?php while ($row = $tfight->fetch_assoc()){ echo "<i>".ucfirst($row['fightcallsign'])."</i></td><td>".round($row['fightpoints'],0)."";} ?></td></tr>
                    <tr align='center'><td><?php while ($row = $tbomb->fetch_assoc()){ echo "<i>".ucfirst($row['bombcallsign'])."</i></td><td>".round($row['bombpoints'],0)."";} ?></td></tr>
                    <tr align='center'><td><br /></td></tr>
                    <tr align='center'><td><?php while ($row = $tdd->fetch_assoc()){ echo "<i>".ucfirst($row['ddcallsign'])."</i></td><td>".round($row['ddpoints'],0)."";} ?></td></tr>
                    <tr align='center'><td><?php while ($row = $tpb->fetch_assoc()){ echo "<i>".ucfirst($row['pbcallsign'])."</i></td><td>".round($row['pbpoints'],0)."";} ?></td></tr>
                    <tr align='center'><td><?php while ($row = $ttt->fetch_assoc()){ echo "<i>".ucfirst($row['ttcallsign'])."</i></td><td>".round($row['ttpoints'],0)."";} ?></td></tr>
                    <tr><td colspan='2'><hr style="width:100%"></td></tr>
                    <tr align='center'><td><b>Player</b></td><td><b>Total</b></td></tr>
                    <tr align='center'><td><?php while ($row = $tkills->fetch_assoc()){ echo "<i>".ucfirst($row['killscallsign'])."</i></td><td>".$row['kills']."</td>";} ?> </td></tr>
                    <tr align='center'><td><?php while ($row = $tcaps->fetch_assoc()){ echo "<i>".ucfirst($row['capscallsign'])."</i></td><td>".$row['caps']."</td>";} ?></td></tr>
                    <tr align='center'><td><?php  while ($row = $tkd->fetch_assoc()){ echo "<i>".ucfirst($row['kdcallsign'])."</i></td><td>".$row['kd']."</td>";} ?></td></tr>
                    <tr align='center'><td><?php  while ($row = $ttom->fetch_assoc()){ echo "<i>".ucfirst($row['tomcallsign'])."</i></td><td>".$row['tom']."</td>";} ?></td></tr>
                    <tr align='center'><td><br /></td></tr>
                    <tr align='center'><td><?php  while ($row = $tkrow->fetch_assoc()){ echo "<i>".ucfirst($row['krowcallsign'])."</i></td><td>".$row['krowpoints']."</td>";} ?></td></tr>
                    <tr align='center'><td><?php  while ($row = $tcrow->fetch_assoc()){ echo "<i>".ucfirst($row['crowcallsign'])."</i></td><td>".$row['crowpoints']."</td>";} ?></td></tr>
                    <tr><td colspan='2'><hr style="width:100%"></td></tr>
                    <tr align='center'><td><b>Player</b></td><td><b>Total</b></td</tr>
                    <tr align='center'><td><?php while ($row = $tkstreak->fetch_assoc()){ echo "<i>".ucfirst($row['sortmkillcallsign'])."</i></td><td>".$row['sortmkill']."</td>";} ?></td></tr>
                    <tr align='center'><td><?php  while ($row = $tcapstreak->fetch_assoc()){ echo "<i>".ucfirst($row['sortcapscallsign'])."</i></td><td>".$row['capstreak']."</td>";}  ?></td></tr>
                </table>
            </span>
        </div>
        </div>
<!-- Second Row: Know Enemy | Stats Continue  -->
        <div id="knowEnemy">
            <hr style="width:90%; margin-top:0;">
            <span class="papertimeshuge"><?php echo "Know Your Enemy<br />"; ?>
						<!-- Code for image rotation -->
						<?php
							$iDir='assets/img/allied/units/';
							$image = glob ($iDir.'*.{jpg,png,gif}', GLOB_BRACE);
							$selImage = $image[array_rand($image)];
						?>
                        <img id="poster" src="<?php echo $selImage; ?>" style="width: 460px; height: 260px;"> 
						<!-- End rotation Code --> </span>
        </div>
<!-- Third Row: Units in Field | Story/image | stats1 story -->

        <div id="secondRightOuterStory">
            <span class="papertimesmedium"><?php echo $stat1; ?></span>
            <div>
                <hr style="width:90%; margin-top:0;">
            </div>
        </div>        

        <div id="unitsInField">
            <span class="papertimeshuge">Units In The Field</span>
                <div id="frenchField" valign='top'>
                   <span class="papertimesmedium"><div> <?php echo "<b>French</b>:".$funit; ?></div></span>
                </div>
                <div id="britField" valign='top'>
                   <span class="papertimesmedium"><div> <?php echo "<b>British</b>:".$bunit; ?></div></span>
                </div>
                <div id="USField" valign='top'>
                   <span class="papertimesmedium"><div> <?php echo "<b>United States</b>:".$uunit;  ?> </div></span>
                </div>
        </div>
        <div id="bottomMiddle">
            <div id="bottomMidtop">
                <span class="papertimesmedium"><?php echo $stat3; ?> </span>
            </div>
            <div>
                <hr style="width:90%; margin-top:0;">
            </div>
            <div id="bottomMidbottom">
                <span class="papertimesmedium"><?php echo $stat4; ?> </span>
            </div>
        </div>


<!-- 4th Row: By Country Factory stats/RDP completion -->
        <div id="bottomRightOuterStory">
            <span class="papertimesmedium"><?php echo $promo; ?></span>
        </div> 

    </div> <?php /* end of background container */ ?>
</body>
</html>
<?php
// This MUST be present as the last line of this file to ensure buffered content is sent to the browser
ob_end_flush();
// Don't put a closing PHP tag here. It isn't necessary and can introduce problems