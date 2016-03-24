<?php
// For Allied side specific information 

//include 'testkills.php';
include'assets/alliedstats.php';
require(__DIR__ . '../DBconn.php');


$stat1 = file_get_contents(__DIR__ .'/../cache/playnow_allied_stats1.php');
$stat2 = file_get_contents(__DIR__ .'/../cache/playnow_allied_stats2.php');
$funit = file_get_contents(__DIR__ .'/../cache/playnow_allied_french_spawn.php');
$bunit = file_get_contents(__DIR__ .'/../cache/playnow_allied_british_spawn.php');
$uunit = file_get_contents(__DIR__ .'/../cache/playnow_allied_us_spawn.php');
$promo = file_get_contents(__DIR__ .'/../cache/playnow_allied_promotion.php');
$stat3 = file_get_contents(__DIR__ .'/../cache/playnow_allied_stats3.php');
$stat4 = file_get_contents(__DIR__ .'/../cache/playnow_allied_stats4.php');

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
                    <td style="width: 33%; text-align: center;"><span class='papertimesmedium'><a href="index.php">READ THE FRONT PAGE</a></span></td>
                    <td style="width: 33%; text-align: right;"><span class='papertimesmedium'><a href='axis.php'>READ THE AXIS SECTION</a></span></td>
                </tr>
            </table>
        </div>
        <div>
            <span id ="mainHeadline" class="paperheadline"><?php echo "<b>ALLIED FORCES EDITION</b>" ?></SPAN>
            
        </div>
        <div>
            <img src="assets/img/header.gif" id="imagegazette">
        </div>
        <div id="topLeft" >
            <span class="papertimeshuge" ><?php echo"FROM THE<br /> ALLIED COMMAND";?></span>
        </div>
        <div class='clear'></div>
<!-- TOP ROW: HC recruiting | Small Story | Stats Leaderboard Block -->
        <div id="topLeftOuterStory">
            <span class="papertimesmedium"><?php echo "<b>HIGH COMMAND SEEKING OFFICERS</b><br />
                    <hr style='width:90%; margin-top:0;'>The Allied Side is calling out to all allied players of yesterday and today. 
                        The team is working together and we need <b>YOUR</b> help.  Victory can only be earned if all allied players team together and work towards a common goal.
                        The allied side needs players willing to step forth from the ranks to be leaders.<br /><b>YOU</b> can be that leader<br /><BR />
                        <a href='http://battlegroundeurope.net/ocs'>CLICK HERE TO LEARN MORE</a>" ?> </span>
        </div>
        <div id="topLeftInnerStory">
            <span class="papertimesmedium"><?php echo $stat2  ?></span>
        </div>
        <div id="leader">
            <span class="papertimeshuge"><b>ALLIED LEADERBOARD</b></span>
            <span class="paptertimesmedium"><br>Updated Hourly</span>
            <hr style="width:90%; margin-top:0;">
                <div id="dailyLeader">
                    <span class="papertimesmedium">
                    <table align='center' valign='top' cellpadding='0' cellspacing='1' width='100%' text-align='center'>
                        <tr align='center'><th colspan='2'>Last 24 Hours:</th></tr>
                        <tr align='center'><td colspan='2'><hr style="width:100%; margin-top:0;"></td></tr>
                        <tr align='center'><td><b>Total Pts.</b></td><td><b>Player</b></td></tr>
                        <tr align='center'><td><?php while ($row = $daa->fetch_assoc())     { echo ROUND($row['aaapoints'],0)."</td><td><b>".$row['aaacallsign']."</b>";} ?></td></tr>
                        <tr align='center'><td><?php while ($row = $datg->fetch_assoc())    { echo ROUND($row['atgpoints'],0)."</td><td><b>".$row['atgcallsign']."</b>";} ?></td></tr>
                        <tr align='center'><td><?php while ($row = $datr->fetch_assoc())    { echo ROUND($row['atrpoints'],0)."</td><td><b>".$row['atrcallsign']."</b>";} ?></td></tr>
                        <tr align='center'><td><?php while ($row = $deng->fetch_assoc())    { echo ROUND($row['engpoints'],0)."</td><td><b>".$row['engcallsign']."</b>";} ?></td></tr>
                        <tr align='center'><td><?php while ($row = $dgren->fetch_assoc())   { echo ROUND($row['grenpoints'],0)."</td><td><b>".$row['grencallsign']."</b>";} ?></td></tr>
                        <tr align='center'><td><?php while ($row = $dlmg->fetch_assoc())    { echo ROUND($row['lmgpoints'],0)."</td><td><b>".$row['lmgcallsign']."</b>";} ?></td></tr>
                        <tr align='center'><td><?php while ($row = $dmort->fetch_assoc())   { echo ROUND($row['mortpoints'],0)."</td><td><b>".$row['mortcallsign']."</b>";} ?></td></tr>
                        <tr align='center'><td><?php while ($row = $drifle->fetch_assoc())  { echo ROUND($row['rpoints'],0)."</td><td><b>".$row['rcallsign']."</b>";} ?></td></tr>
                        <tr align='center'><td><?php while ($row = $dsmg->fetch_assoc())    { echo ROUND($row['smgpoints'],0)."</td><td><b>".$row['smgcallsign']."</b>";} ?></td></tr>
                        <tr align='center'><td><?php while ($row = $dsniper->fetch_assoc()) { echo ROUND($row['sniperpoints'],0)."</td><td><b>".$row['snipercallsign']."</b>";} ?></td></tr>
                        <tr align='center'><td><?php while ($row = $dtank->fetch_assoc())   { echo ROUND($row['tankpoints'],0)."</td><td><b>".$row['tankcallsign']."</b>";} ?></td></tr>
                        <tr align='center'><td><?php while ($row = $dtruck->fetch_assoc())  { echo ROUND($row['truckpoints'],0)."</td><td><b>".$row['truckcallsign']."</b>";} ?></td></tr>
                        <tr align='center'><td><br /></td></tr>
                        <tr align='center'><td><?php while ($row = $dfight->fetch_assoc())  { echo ROUND($row['fightpoints'],0)."</td><td><b>".$row['fightcallsign']."</b>";} ?></td></tr>
                        <tr align='center'><td><?php while ($row = $dbomb->fetch_assoc())   { echo ROUND($row['bombpoints'],0)."</td><td><b>".$row['bombcallsign']."</b>";} ?></td></tr>
                        <tr align='center'><td><br /></td></tr>
                        <tr align='center'><td><?php while ($row = $ddd->fetch_assoc())     { echo ROUND($row['ddpoints'],0)."</td><td><b>".$row['ddcallsign']."</b>";} ?></td></tr>
                        <tr align='center'><td><?php while ($row = $dpb->fetch_assoc())     { echo ROUND($row['pbpoints'],0)."</td><td><b>".$row['pbcallsign']."</b>";} ?></td></tr>
                        <tr align='center'><td><?php while ($row = $dtt->fetch_assoc())     { echo ROUND($row['ttpoints'],0)."</td><td><b>".$row['ttcallsign']."</b>";} ?></td></tr>
                        <tr><td colspan='2'><hr style="width:100%"></td></tr>   
                        <tr align='center'><td><b>Total</b></td><td><b>Player</b></td></tr>
                        <tr align='center'><td><?php while ($row = $dkills->fetch_assoc())  { echo $row['kills']."</td><td><b>".$row['killscallsign']."</b></td>" ;} ?> </td></tr>
                        <tr align='center'><td><?php while ($row = $dcaps->fetch_assoc())   { echo $row['caps']."</td><td><b>".$row['capscallsign']."</b></td>" ;} ?></td></tr>
                        <tr align='center'><td><?php  while ($row = $dkd->fetch_assoc())    { echo $row['kd']."</td><td><b>".$row['kdcallsign']."</b></td>" ;} ?></td></tr>
                        <tr align='center'><td><?php  while ($row = $dtom->fetch_assoc())   { echo $row['tom']."</td><td><b>".$row['tomcallsign']."</b></td>" ;} ?></td></tr>
                        <tr align='center'><td><br /></td></tr>
                        <tr align='center'><td><?php  while ($row = $dkrow->fetch_assoc())  { echo $row['krowpoints']."</td><td><b>".$row['krowcallsign']."</b></td>" ;} ?></td></tr>
                        <tr align='center'><td><?php  while ($row = $dcrow->fetch_assoc())  { echo $row['crowpoints']."</td><td><b>".$row['crowcallsign']."</b></td>" ;} ?></td></tr>
                        <tr><td colspan='2'><hr style="width:100%"></td></tr>
                        <tr align='center'><td><b>Player</b></td><td><b>Total</b></td</tr>
                        <tr align='center'><td><?php while ($row = $dkstreak->fetch_assoc()){ echo $row['sortmkill']."</td><td><b>".$row['sortmkillcallsign']."</b></td>" ;} ?></td></tr>
                        <tr align='center'><td><?php  while ($row = $dcapstreak->fetch_assoc()){ echo $row['capstreak']."</td><td><b>".$row['sortcapscallsign']."</b></td>" ;}  ?></td></tr>
                                                        
                    </table>
            </span>
        </div>
        <div id="catIDLeader" valign="top">
            <span class="papertimesmedium">
                <table align='center' valign='top' cellpadding='0' cellspacing='1' width='100%'>
                    <tr><th>Category:</th></tr>
                    <tr><td><hr style="width:90%; margin-top:0;"></td></tr>
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
                    <tr><td><hr style="width:100%"></td></tr>
                    <tr><th>By Action</th></tr>
                    <tr><td>Most Kills</td></tr>
                    <tr><td>Most Caps</td></tr>
                    <tr><td>Best K/D</td></tr>
                    <tr><td>Top TOM</td></tr>
                    <tr><td><b>Cons. Sorties</b></td></tr>
                    <tr><td>With a kill</td></tr>
                    <tr><td>With a Capture</td></tr>
                    <tr><td><hr style="width:100%"></td></tr>
                    <tr><td><b>Single Sortie</b></td></tr>
                    <tr><td>Most kills</td></tr>
                    <tr><td>Most Capture</td></tr>
                </table>
            </span>
        </div>
        <div id="campaignLeader" valign='top'>
            <span class="papertimesmedium">
                <table align='center' valign='top' cellpadding='0' cellspacing='1' width='100%' text-align='left'>
                    <tr align='center'><th colspan='2'>Campaign Leaders:</th></tr>
                    <tr><td colspan='2'><hr style="width:100%; margin-top:0;"></td></tr>
                    <tr align='center'><td><b>Player</b></td><td><b>Points</b></td></tr>
                    <tr align='center'><td><?php while ($row = $taa->fetch_assoc())     { echo "<b>".$row['aaacallsign']."</b></td><td>".ROUND($row['aaapoints'],0)."";} ?></td></tr>
                    <tr align='center'><td><?php while ($row = $tatg->fetch_assoc())    { echo "<b>".$row['atgcallsign']."</b></td><td>".ROUND($row['atgpoints'],0)."";} ?></td></tr>
                    <tr align='center'><td><?php while ($row = $tatr->fetch_assoc())    { echo "<b>".$row['atrcallsign']."</b></td><td>".ROUND($row['atrpoints'],0)."";} ?></td></tr>
                    <tr align='center'><td><?php while ($row = $teng->fetch_assoc())    { echo "<b>".$row['engcallsign']."</b></td><td>".ROUND($row['engpoints'],0)."";} ?></td></tr>
                    <tr align='center'><td><?php while ($row = $tgren->fetch_assoc())   { echo "<b>".$row['grencallsign']."</b></td><td>".ROUND($row['grenpoints'],0)."";} ?></td></tr>
                    <tr align='center'><td><?php while ($row = $tlmg->fetch_assoc())    { echo "<b>".$row['lmgcallsign']."</b></td><td>".ROUND($row['lmgpoints'],0)."";}?></td></tr>
                    <tr align='center'><td><?php while ($row = $tmort->fetch_assoc())   { echo "<b>".$row['mortcallsign']."</b></td><td>".ROUND($row['mortpoints'],0)."";} ?></td></tr>
                    <tr align='center'><td><?php while ($row = $trifle->fetch_assoc())  { echo "<b>".$row['rcallsign']."</b></td><td>".ROUND($row['rpoints'],0)."";} ?></td></tr>
                    <tr align='center'><td><?php while ($row = $tsmg->fetch_assoc())    { echo "<b>".$row['smgcallsign']."</b></td><td>".ROUND($row['smgpoints'],0)."";} ?></td></tr>
                    <tr align='center'><td><?php while ($row = $tsniper->fetch_assoc()) { echo "<b>".$row['snipercallsign']."</b></td><td>".ROUND($row['sniperpoints'],0)."";} ?></td></tr>
                    <tr align='center'><td><?php while ($row = $ttank->fetch_assoc())   { echo "<b>".$row['tankcallsign']."</b></td><td>".ROUND($row['tankpoints'],0)."";} ?></td></tr>
                    <tr align='center'><td><?php while ($row = $ttruck->fetch_assoc())  { echo "<b>".$row['truckcallsign']."</b></td><td>".ROUND($row['truckpoints'],0)."";} ?></td></tr>
                    <tr><td><br /></td></tr>
                    <tr align='center'><td><?php while ($row = $tfight->fetch_assoc())  { echo "<b>".$row['fightcallsign']."</b></td><td>".ROUND($row['fightpoints'],0)."";} ?></td></tr>
                    <tr align='center'><td><?php while ($row = $tbomb->fetch_assoc())   { echo "<b>".$row['bombcallsign']."</b></td><td>".ROUND($row['bombpoints'],0)."";} ?></td></tr>
                    <tr align='center'><td><br /></td></tr>
                    <tr align='center'><td><?php while ($row = $tdd->fetch_assoc())     { echo "<b>".$row['ddcallsign']."</b></td><td>".ROUND($row['ddpoints'],0)."";} ?></td></tr>
                    <tr align='center'><td><?php while ($row = $tpb->fetch_assoc())     { echo "<b>".$row['pbcallsign']."</b></td><td>".ROUND($row['pbpoints'],0)."";} ?></td></tr>
                    <tr align='center'><td><?php while ($row = $ttt->fetch_assoc())     { echo "<b>".$row['ttcallsign']."</b></td><td>".ROUND($row['ttpoints'],0)."";} ?></td></tr>
                    <tr><td colspan='2'><hr style="width:100%"></td></tr>
                    <tr align='center'><td><b>Player</b></td><td><b>Total</b></td></tr>
                    <tr align='center'><td><?php while ($row = $tkills->fetch_assoc())  { echo "<b>".$row['killscallsign']."</b></td><td>".$row['kills']."</td>" ;} ?> </td></tr>
                    <tr align='center'><td><?php while ($row = $tcaps->fetch_assoc())   { echo "<b>".$row['capscallsign']."</b></td><td>".$row['caps']."</td>" ;} ?></td></tr>
                    <tr align='center'><td><?php  while ($row = $tkd->fetch_assoc())    { echo "<b>".$row['kdcallsign']."</b></td><td>".$row['kd']."</td>" ;} ?></td></tr>
                    <tr align='center'><td><?php  while ($row = $ttom->fetch_assoc())   { echo "<b>".$row['tomcallsign']."</b></td><td>".$row['tom']."</td>" ;} ?></td></tr>
                    <tr align='center'><td><br /></td></tr>
                    <tr align='center'><td><?php  while ($row = $tkrow->fetch_assoc())  { echo "<b>".$row['krowcallsign']."</b></td><td>".$row['krowpoints']."</td>" ;} ?></td></tr>
                    <tr align='center'><td><?php  while ($row = $tcrow->fetch_assoc())  { echo "<b>".$row['crowcallsign']."</b></td><td>".$row['crowpoints']."</td>" ;} ?></td></tr>
                    <tr><td colspan='2'><hr style="width:100%"></td></tr>
                    <tr align='center'><td><b>Player</b></td><td><b>Total</b></td</tr>
                    <tr align='center'><td><?php while ($row = $tkstreak->fetch_assoc()){ echo "<b>".$row['sortmkillcallsign']."</b></td><td>".$row['sortmkill']."</td>" ;} ?></td></tr>
                    <tr align='center'><td><?php  while ($row = $tcapstreak->fetch_assoc()){ echo "<b>".$row['sortcapscallsign']."</b></td><td>".$row['capstreak']."</td>" ; }  ?></td></tr>
                </table>
            </span>
        </div>
        </div>
<!-- Second Row: Know Enemy | Stats Continue  -->
        <div id="knowEnemy">
            <hr style="width:90%; margin-top:0;">
            <span class="papertimeshuge"><?php echo "Know Your Enemy<br />" ?>
						<!-- Code for image rotation -->
						<?php
							$iDir='assets/img/allied/units/';
							$image = glob ($iDir.'*.{jpg,png,gif}', GLOB_BRACE);
							$selImage = $image[array_rand($image)];
						?>
                        <img id="poster" src="<?php echo $selImage ?>" style="width: 460px; height: 260px;"> 
						<!-- End rotation Code --> </span>
        </div>
<!-- Third Row: Units in Field | Story/image | stats1 story -->

        <div id="secondRightOuterStory">
            <span class="papertimesmedium"><?php echo $stat1 ?></span>
        </div>        

        <div id="unitsInField">
            <span class="papertimeshuge">Units In The Field</span>
                <div id="frenchField" valign='top'>
                   <span class="papertimesmedium"><div> <?php echo "<b>French</b>:".$bunit ?></div></span>
                </div>
                <div id="britField" valign='top'>
                   <span class="papertimesmedium"><div> <?php echo "<b>British</b>:". $funit ?></div></span>
                </div>
                <div id="USField" valign='top'>
                   <span class="papertimesmedium"><div> <?php echo "<b>United States</b>:".$uunit  ?> </div></span>
                </div>
        </div>
        <div id="bottomMiddle">
            <div id="bottomMidtop">
                <span class="papertimesmedium"><?php echo $stat3 ?> </span>
            </div>
            <div id="bottomMidbottom">
                <span class="papertimesmedium"><?php echo $stat4 ?> </span>
            </div>
        </div>


<!-- 4th Row: By Country Factory stats/RDP completion -->
        <div id="bottomRightOuterStory">
            <span class="papertimesmedium"><?php echo $promo ?></span>
        </div> 



































    </div> <?php /* end of background container */ ?>
</body>
</html>
