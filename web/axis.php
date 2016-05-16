<?php
// For Allied side specific information 

//include 'testkills.php';
include'assets/axisstats.php';
require(__DIR__ . '/../DBconn.php');


$gstat1 = file_get_contents(__DIR__ .'/../cache/playnow_axis_stats1.php');
$gstat2 = file_get_contents(__DIR__ .'/../cache/playnow_axis_stats2.php');
$gstat3 = file_get_contents(__DIR__ .'/../cache/playnow_axis_stats3.php');
$gstat4 = file_get_contents(__DIR__ .'/../cache/playnow_axis_stats4.php');
$gunit = file_get_contents(__DIR__ .'/../cache/playnow_axis_german_spawn.php');
$gpromo = file_get_contents(__DIR__ .'/../cache/playnow_axis_promotion.php');


?>
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>        
    <title>World@War AXIS Gazette</title>
	<link rel='stylesheet' href='assets/css/axisgazette.css'>
	<link rel="shortcut icon" href="assets/img/favicon.ico" />
</head>

<body>
    <div id='container'>
        <div id='top'>
            <table id='infoBar'>
                <tr>
                    <td style="width: 33%; text-align: left;"><span class='papertimesmedium'>WHATS COMING NEXT</span></td>
                    <td style="width: 33%; text-align: center;"><span class='papertimesmedium'><a href="index.php">READ THE FRONT PAGE</a></span></td>
                    <td style="width: 33%; text-align: right;"><span class='papertimesmedium'><a href='allied.php'>READ THE ALLIED SECTION</a></span></td>
                </tr>
            </table>
        </div>
        <div>
            <span id ="mainHeadline" class="paperheadline"><?php echo "<b>AXIS FORCES EDITION</b>"; ?></SPAN>
            
        </div>
        <div>
            <br />
            <img src="assets/img/header.gif" id="imagegazette">
        </div>
        <div id="topLeft" >
            <span class="papertimeshuge" ><?php echo"FROM THE<br /> AXIS COMMAND";?></span>
        </div>
        <div class='clear'></div>
<!-- TOP ROW: HC recruiting | Small Story | Stats Leaderboard Block -->
        <div id="topLeftOuterStory">
            <span class="papertimesmedium"><?php echo "<b>HIGH COMMAND SEEKING OFFICERS</b><br />
                    <hr style='width:90%; margin-top:0;'>The OKW is calling out to all axis players of yesterday and today. 
                        The team is working together and we need <b>YOUR</b> help.  Victory can only be earned if all players team together and work towards a common goal.
                        Your side needs players willing to step forth from the ranks to be leaders.<br /><b>YOU</b> can be that leader<br /><BR />
                        <a href='http://battlegroundeurope.net/ocs'  target='_blank'>CLICK HERE TO LEARN MORE</a>"; ?> </span>
        </div>
        <div id="topLeftInnerStory">
            <span class="papertimesmedium"><?php echo $gstat2;  ?></span>
        </div>
        <div id="leader">
            <span class="papertimeshuge"><b>AXIS LEADERBOARD</b></span>
            
            <hr style="width:90%; margin-top:0;">
                <div id="dailyLeader">
                    <span class="papertimesmedium">
                    <table align='center' valign='top' cellpadding='0' cellspacing='1' width='100%' text-align='center'>
                        <tr align='center'><th colspan='2'>Last 24 Hours:</th></tr>
                        <tr align='center'><td colspan='2'><hr style="width:100%; margin-top:0;"></td></tr>
                        <tr align='center'><td><b>Total Pts.</b></td><td><b>Player</b></td></tr>
                        <tr align='center'><td><?php while ($row = $gdaa->fetch_assoc())     { echo ROUND($row['aaapoints'],0)."</td><td><i>".ucfirst($row['aaacallsign'])."</i>";} ?></td></tr>
                        <tr align='center'><td><?php while ($row = $gdatg->fetch_assoc())    { echo ROUND($row['atgpoints'],0)."</td><td><i>".ucfirst($row['atgcallsign'])."</i>";} ?></td></tr>
                        <tr align='center'><td><?php while ($row = $gdatr->fetch_assoc())    { echo ROUND($row['atrpoints'],0)."</td><td><i>".ucfirst($row['atrcallsign'])."</i>";} ?></td></tr>
                        <tr align='center'><td><?php while ($row = $gdeng->fetch_assoc())    { echo ROUND($row['engpoints'],0)."</td><td><i>".ucfirst($row['engcallsign'])."</i>";} ?></td></tr>
                        <tr align='center'><td><?php while ($row = $gdgren->fetch_assoc())   { echo ROUND($row['grenpoints'],0)."</td><td><i>".ucfirst($row['grencallsign'])."</i>";} ?></td></tr>
                        <tr align='center'><td><?php while ($row = $gdlmg->fetch_assoc())    { echo ROUND($row['lmgpoints'],0)."</td><td><i>".ucfirst($row['lmgcallsign'])."</i>";} ?></td></tr>
                        <tr align='center'><td><?php while ($row = $gdmort->fetch_assoc())   { echo ROUND($row['mortpoints'],0)."</td><td><i>".ucfirst($row['mortcallsign'])."</i>";} ?></td></tr>
                        <tr align='center'><td><?php while ($row = $gdrifle->fetch_assoc())  { echo ROUND($row['rpoints'],0)."</td><td><i>".ucfirst($row['rcallsign'])."</i>";} ?></td></tr>
                        <tr align='center'><td><?php while ($row = $gdsmg->fetch_assoc())    { echo ROUND($row['smgpoints'],0)."</td><td><i>".ucfirst($row['smgcallsign'])."</i>";} ?></td></tr>
                        <tr align='center'><td><?php while ($row = $gdsniper->fetch_assoc()) { echo ROUND($row['sniperpoints'],0)."</td><td><i>".ucfirst($row['snipercallsign'])."</i>";} ?></td></tr>
                        <tr align='center'><td><?php while ($row = $gdtank->fetch_assoc())   { echo ROUND($row['tankpoints'],0)."</td><td><i>".ucfirst($row['tankcallsign'])."</i>";} ?></td></tr>
                        <tr align='center'><td><?php while ($row = $gdtruck->fetch_assoc())  { echo ROUND($row['truckpoints'],0)."</td><td><i>".ucfirst($row['truckcallsign'])."</i>";} ?></td></tr>
                        <tr align='center'><td><br /></td></tr>
                        <tr align='center'><td><?php while ($row = $gdfight->fetch_assoc())  { echo ROUND($row['fightpoints'],0)."</td><td><i>".ucfirst($row['fightcallsign'])."</i>";} ?></td></tr>
                        <tr align='center'><td><?php while ($row = $gdbomb->fetch_assoc())   { echo ROUND($row['bombpoints'],0)."</td><td><i>".ucfirst($row['bombcallsign'])."</i>";} ?></td></tr>
                        <tr align='center'><td><br /></td></tr>
                        <tr align='center'><td><?php while ($row = $gddd->fetch_assoc())     { echo ROUND($row['ddpoints'],0)."</td><td><i>".ucfirst($row['ddcallsign'])."</i>";} ?></td></tr>
                        <tr align='center'><td><?php while ($row = $gdpb->fetch_assoc())     { echo ROUND($row['pbpoints'],0)."</td><td><i>".ucfirst($row['pbcallsign'])."</i>";} ?></td></tr>
                        <tr align='center'><td><?php while ($row = $gdtt->fetch_assoc())     { echo ROUND($row['ttpoints'],0)."</td><td><i>".ucfirst($row['ttcallsign'])."</i>";} ?></td></tr>
                        <tr><td colspan='2'><hr style="width:100%"></td></tr>   
                        <tr align='center'><td><b>Total</b></td><td><b>Player</b></td></tr>
                        <tr align='center'><td><?php while ($row = $gdkills->fetch_assoc())  { echo $row['kills']."</td><td><i>".ucfirst($row['killscallsign'])."</i></td>" ;} ?> </td></tr>
                        <tr align='center'><td><?php while ($row = $gdcaps->fetch_assoc())   { echo $row['caps']."</td><td><i>".ucfirst($row['capscallsign'])."</i></td>" ;} ?></td></tr>
                        <tr align='center'><td><?php  while ($row = $gdkd->fetch_assoc())    { echo $row['kd']."</td><td><i>".ucfirst($row['kdcallsign'])."</i></td>" ;} ?></td></tr>
                        <tr align='center'><td><?php  while ($row = $gdtom->fetch_assoc())   { echo $row['tom']."</td><td><i>".ucfirst($row['tomcallsign'])."</i></td>" ;} ?></td></tr>
                        <tr align='center'><td><br /></td></tr>
                        <tr align='center'><td><?php  while ($row = $gdkrow->fetch_assoc())  { echo ROUND($row['krowpoints'],0)."</td><td><i>".ucfirst($row['krowcallsign'])."</i></td>" ;} ?></td></tr>
                        <tr align='center'><td><?php  while ($row = $gdcrow->fetch_assoc())  { echo ROUND($row['crowpoints'],0)."</td><td><i>".ucfirst($row['crowcallsign'])."</i></td>" ;} ?></td></tr>
                        <tr><td colspan='2'><hr style="width:100%"></td></tr>
                        <tr align='center'><td><b>Player</b></td><td><b>Total</b></td</tr>
                        <tr align='center'><td><?php while ($row = $gdkstreak->fetch_assoc()){ echo $row['sortmkill']."</td><td><i>".ucfirst($row['sortmkillcallsign'])."</i></td>" ;} ?></td></tr>
                        <tr align='center'><td><?php  while ($row = $gdcapstreak->fetch_assoc()){ echo $row['capstreak']."</td><td><i>".ucfirst($row['sortcapscallsign'])."</i></td>" ;}  ?></td></tr>
                                                        
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
                    <tr><td><span class="paptertimesmedium">*Updated Hourly*</span></td></tr>
                </table>
            </span>
        </div>
        <div id="campaignLeader" valign='top'>
            <span class="papertimesmedium">
                <table align='center' valign='top' cellpadding='0' cellspacing='1' width='100%' text-align='left'>
                    <tr align='center'><th colspan='2'>Campaign Leaders:</th></tr>
                    <tr><td colspan='2'><hr style="width:100%; margin-top:0;"></td></tr>
                    <tr align='center'><td><b>Player</b></td><td><b>Points</b></td></tr>
                    <tr align='center'><td><?php while ($row = $gtaa->fetch_assoc())     { echo "<i>".ucfirst($row['aaacallsign'])."</i></td><td>".ROUND($row['aaapoints'],0)."";} ?></td></tr>
                    <tr align='center'><td><?php while ($row = $gtatg->fetch_assoc())    { echo "<i>".ucfirst($row['atgcallsign'])."</i></td><td>".ROUND($row['atgpoints'],0)."";} ?></td></tr>
                    <tr align='center'><td><?php while ($row = $gtatr->fetch_assoc())    { echo "<i>".ucfirst($row['atrcallsign'])."</i></td><td>".ROUND($row['atrpoints'],0)."";} ?></td></tr>
                    <tr align='center'><td><?php while ($row = $gteng->fetch_assoc())    { echo "<i>".ucfirst($row['engcallsign'])."</i></td><td>".ROUND($row['engpoints'],0)."";} ?></td></tr>
                    <tr align='center'><td><?php while ($row = $gtgren->fetch_assoc())   { echo "<i>".ucfirst($row['grencallsign'])."</i></td><td>".ROUND($row['grenpoints'],0)."";} ?></td></tr>
                    <tr align='center'><td><?php while ($row = $gtlmg->fetch_assoc())    { echo "<i>".ucfirst($row['lmgcallsign'])."</i></td><td>".ROUND($row['lmgpoints'],0)."";}?></td></tr>
                    <tr align='center'><td><?php while ($row = $gtmort->fetch_assoc())   { echo "<i>".ucfirst($row['mortcallsign'])."</i></td><td>".ROUND($row['mortpoints'],0)."";} ?></td></tr>
                    <tr align='center'><td><?php while ($row = $gtrifle->fetch_assoc())  { echo "<i>".ucfirst($row['rcallsign'])."</i></td><td>".ROUND($row['rpoints'],0)."";} ?></td></tr>
                    <tr align='center'><td><?php while ($row = $gtsmg->fetch_assoc())    { echo "<i>".ucfirst($row['smgcallsign'])."</i></td><td>".ROUND($row['smgpoints'],0)."";} ?></td></tr>
                    <tr align='center'><td><?php while ($row = $gtsniper->fetch_assoc()) { echo "<i>".ucfirst($row['snipercallsign'])."</i></td><td>".ROUND($row['sniperpoints'],0)."";} ?></td></tr>
                    <tr align='center'><td><?php while ($row = $gttank->fetch_assoc())   { echo "<i>".ucfirst($row['tankcallsign'])."</i></td><td>".ROUND($row['tankpoints'],0)."";} ?></td></tr>
                    <tr align='center'><td><?php while ($row = $gttruck->fetch_assoc())  { echo "<i>".ucfirst($row['truckcallsign'])."</i></td><td>".ROUND($row['truckpoints'],0)."";} ?></td></tr>
                    <tr><td><br /></td></tr>
                    <tr align='center'><td><?php while ($row = $gtfight->fetch_assoc())  { echo "<i>".ucfirst($row['fightcallsign'])."</i></td><td>".ROUND($row['fightpoints'],0)."";} ?></td></tr>
                    <tr align='center'><td><?php while ($row = $gtbomb->fetch_assoc())   { echo "<i>".ucfirst($row['bombcallsign'])."</i></td><td>".ROUND($row['bombpoints'],0)."";} ?></td></tr>
                    <tr align='center'><td><br /></td></tr>
                    <tr align='center'><td><?php while ($row = $gtdd->fetch_assoc())     { echo "<i>".ucfirst($row['ddcallsign'])."</i></td><td>".ROUND($row['ddpoints'],0)."";} ?></td></tr>
                    <tr align='center'><td><?php while ($row = $gtpb->fetch_assoc())     { echo "<i>".ucfirst($row['pbcallsign'])."</i></td><td>".ROUND($row['pbpoints'],0)."";} ?></td></tr>
                    <tr align='center'><td><?php while ($row = $gttt->fetch_assoc())     { echo "<i>".ucfirst($row['ttcallsign'])."</i></td><td>".ROUND($row['ttpoints'],0)."";} ?></td></tr>
                    <tr><td colspan='2'><hr style="width:100%"></td></tr>
                    <tr align='center'><td><b>Player</b></td><td><b>Total</b></td></tr>
                    <tr align='center'><td><?php while ($row = $gtkills->fetch_assoc())  { echo "<i>".ucfirst($row['killscallsign'])."</i></td><td>".$row['kills']."</td>" ;} ?> </td></tr>
                    <tr align='center'><td><?php while ($row = $gtcaps->fetch_assoc())   { echo "<i>".ucfirst($row['capscallsign'])."</i></td><td>".$row['caps']."</td>" ;} ?></td></tr>
                    <tr align='center'><td><?php  while ($row = $gtkd->fetch_assoc())    { echo "<i>".ucfirst($row['kdcallsign'])."</i></td><td>".$row['kd']."</td>" ;} ?></td></tr>
                    <tr align='center'><td><?php  while ($row = $gttom->fetch_assoc())   { echo "<i>".ucfirst($row['tomcallsign'])."</i></td><td>".$row['tom']."</td>" ;} ?></td></tr>
                    <tr align='center'><td><br /></td></tr>
                    <tr align='center'><td><?php  while ($row = $gtkrow->fetch_assoc())  { echo "<i>".ucfirst($row['krowcallsign'])."</i></td><td>".$row['krowpoints']."</td>" ;} ?></td></tr>
                    <tr align='center'><td><?php  while ($row = $gtcrow->fetch_assoc())  { echo "<i>".ucfirst($row['crowcallsign'])."</i></td><td>".$row['crowpoints']."</td>" ;} ?></td></tr>
                    <tr><td colspan='2'><hr style="width:100%"></td></tr>
                    <tr align='center'><td><b>Player</b></td><td><b>Total</b></td</tr>
                    <tr align='center'><td><?php while ($row = $gtkstreak->fetch_assoc()){ echo "<i>".ucfirst($row['sortmkillcallsign'])."</i></td><td>".$row['sortmkill']."</td>" ;} ?></td></tr>
                    <tr align='center'><td><?php  while ($row = $gtcapstreak->fetch_assoc()){ echo "<i>".ucfirst($row['sortcapscallsign'])."</i></td><td>".$row['capstreak']."</td>" ; }  ?></td></tr>
                    
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
							$pDir='assets/img/axis/units/';
							$image = glob ($pDir.'*.{jpg,png,gif}', GLOB_BRACE);
							$selImage = $image[array_rand($image)];
						?>
                        <img id="poster" src="<?php echo $selImage; ?>" style="width: 460px; height: 260px;"> 
						<!-- End rotation Code --> </span>
        </div>
<!-- Third Row: Units in Field | Story/image | stats1 story -->

        <div id="secondRightOuterStory">
            <span class="papertimesmedium"><?php echo $gstat1; ?></span>
            <div>
                <hr style="width:90%; margin-top:0;">
            </div>
        </div>        

        <div id="unitsInField">
            <span class="papertimeshuge">In The Field</span>
                <div id="germanField" valign='top'>
                   <span class="papertimesmedium"><div><?php echo "<b>Germany</b>:".$gunit;  ?></div></span>
                </div>
        </div>
        <div id="bottomMiddle">
            <div id="bottomMidtop">
                <span class="papertimesmedium"> <?php echo $gstat3; ?> </span>
                
            </div>
            <div>
                <hr style="width:90%; margin-top:0;">
            </div>
            <div id="bottomMidbottom">
                <span class="papertimesmedium"><?php echo $gstat4; ?> </span>
            </div>
        </div>


<!-- 4th Row: By Country Factory stats/RDP completion -->
        <div id="bottomRightOuterStory">
            <span class="papertimesmedium"><?php echo $gpromo; ?></span>
        </div> 



































    </div> <?php /* end of background container */ ?>
</body>
</html>
