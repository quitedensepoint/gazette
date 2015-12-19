<?php
/* 
This page is the index (main) page for the Gazette
It has the layout and look of the original page.  The data feeds are not yet in place.  
Where the feed should be, the area his marked at both top and bottom of where the includes should go.  Table information within these comments may not align with the code outside. 
as the queries place data in, it appears to all be placed L aligned.  
For reference also the data within those areas has been modified to show the old data source.
Map is aplaceholder image and the link to the full map page has been commented out pending restoration of the BE Map.   

*/

require(__DIR__ . '/../DBconn.php');
require(__DIR__ . '/../include/dbhelper.php');
require(__DIR__ . '/../processors/casualty-processor.php');

$casualtyProcessor = new CasualtyProcessor($dbconn);
$casualtyData = $casualtyProcessor->process();

var_dump($casualtyData);

 ?>
<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>        
    <title>Battleground Europe Gazette</title>
            <link rel='stylesheet' href='assets/css/gazette.css'>
            <link rel='stylesheet' href='assets/css/wwiiol.css'>
</head>

<body background='assets/img/paper_tile_new.jpg'  leftmargin='0' topmargin='0' marginwidth='0' marginheight='0'>
    <div id='bodyDiv' >
        <img src='assets/img/header_middle.gif' width='510' height='72' id='headerMiddle'>
        <img src='assets/img/header_bar.gif'  width='940' height='17' id='headerMiddle'>

        <table id='alliedDeaths'>
            <tr align='center' valign='top'>
                <th bgcolor='#333333' class='paperdefault'><span class='paperwhite'>ALLIED CASUALTIES</span></th>
            </tr>
            <tr>
                <td align='left' valign='top' class='paperarialsmall'>GROUND FORCES <?= $casualtyData['allied']['ground'];?><br />AIR FORCES <?= $casualtyData['allied']['air'];?><br />SEA FORCES <?= $casualtyData['allied']['sea'];?><br /></td>
            </tr>
        </table>
        <table id='axisDeaths'>
                <tr align='center' valign='top'>
                    <th bgcolor='#333333' class='paperdefault'><span class='paperwhite'>AXIS CASUALTIES</span></th>
                </tr>
                <tr>
                    <td align='right' valign='top' class='paperarialsmall'>GROUND FORCES <?= $casualtyData['axis']['ground'];?><br />AIR FORCES <?= $casualtyData['axis']['air'];?><br />SEA FORCES <?= $casualtyData['axis']['sea'];?><br /></td>
                </tr>
         </table>
      </div>
 
 


<!-- Start of re-imaged page 940px wide -->
<div id='mainBody' >
<table border='1'> <!-- Border currently on to show layout of frames -->


    <tr align='center'> <!-- Version info and Navigation Section -->
        <td colspan='5'>
            <table width='100%' border='1' cellspacing='0' cellpadding='0' bordercolor='#000000' align='center'><!-- Sets outside border -->
            <tr>
            <td>
                <table width='100%' border='0' cellspacing='0' cellpadding='0' bordercolor='#000000' align='center'><!-- removes cell borders border from row-->
                <tr>
                    <td width='188'>
                        <a href='/web/20051201034059/http://forums.battlegroundeurope.com/forumdisplay.php?f=10' target='_blank' class='papertimessmall'>
                        <font color='#cc3333'>CURRENT VERSION 
                            <!-- START index_version --> 1.34.12<!-- END index_version (LIVE) -->
                        </font>
                        </a>
                    </td>
                    <td>Day: </td><td> # </td><td> Of Campaign #:</td><td> (number)</td>
                    <td class='papertimessmall' align='right'>Coming Soon:</td>  
                    <td align='center'><a class='papertimessmall'>ALLIED Section</td>
                    <td align='center'><a class='papertimessmall'>AXIS Section</td>
                </tr>
                </table>
            </td>
            </tr>
            </table>
        </td>
    </tr><!-- END Version info and Navigation Section -->
    <tr> <!-- MAIN HEADLINE --> 
        <td colspan='5'>
        <!-- #include file='/home/bv55/scriptinc/paper/index_main_headline.html' -->
        <table width='100%' border='0' cellspacing='0' cellpadding='3' align='center'>
            <tr valign='top' align='center'> 
                <td> 
                    <font face='Arial, Helvetica, sans-serif' size='7' color='#666666'>
                    <b><span class='paperheadline'>Guess Who's Back? (index_main_headline)</span></b>
                    </font>
                </td>
            </tr>
        </table>
        <!-- #include file='/home/bv55/scriptinc/paper/index_main_headline.html' -->
        </td><!-- END MAIN HEADLINE -->
    </tr>
    <tr valign='top'><!-- MAP and 1st Story Row-->
        <td height='150' width='188'><!-- Allied Stats Story-->
            <table width='100%' align ='center' border='0' cellspacing='0' cellpadding='0'>
            <tr>
            <td>
            <!-- #include file='/home/bv55/scriptinc/paper/index_allied_stats2.html' -->
                                            <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                                                <tr align='center' valign='top'> 
                                                    <td> 
                                                    <font size='3' face='Times New Roman, Times, serif'>
                                                    <b><span class='papertimesbig'>index_allied_stats2</span></b>
                                                    </font>
                                                    <br>
                                                    <font size='1' face='Arial, Helvetica, sans-serif'>
                                                    <span class='paperdefault'>An independent analysis of military airfields controlled by the major combatants indicate that United Kingdom now controls 38% of the airfields (19 total), followed by Germany with 34% (17) and France with 26% (13). This article page has 40 words</span>
                                                    </font>
                                                    </td>
                                                </tr>
                                            </table>
            <!-- #include file='/home/bv55/scriptinc/paper/index_allied_stats2.html' -->
            </td>
            </tr>
            <tr>
                <td align='center' valign='bottom'><br /><a href='allied.php' class='paperdefault'>[more in Allied section]</a></td>
            </tr>
            </table>
        </td>
        <!-- Start map -->
        <td rowspan='2' colspan='3'>
    <!-- Front Line Map -->
            <table width='564' border='0' cellspacing='0' cellpadding='0' name='map_table'>
                <tr>
                    <td><!-- Placeholder image -->
                        <div align='center'><a href='http://map.wwiionline.com/belgium_front.html')>
                            <img src='assets/img/belgium_long_front.jpg' width='564' align='middle' border=0 alt='This is the map'></a>
                        </div>
                   </td>
                </tr>
                <tr bgcolor='#000000'>
                    <td align='center' class='paperdefault'> <span class='paperwhite'>GAME MAP UPDATED EVERY 5 MINUTES - <a href=http://map.wwiionline.com/belgium_front.html'>CLICK FOR FULL SIZE</a></span> </td>
                </tr>
            </table>
    <!-- END MAP -->
        </td>
        <td> 
        <!--Axis RDP Story #1 -->
        <!-- #include file='/home/bv55/scriptinc/paper/index_axis_german_rdp1.html' -->
                                <table width='100%'border='0' cellspacing='0' cellpadding='5'>
                                    <tr align='left' valign='top'> 
                                        <td>
                                        <font face='Arial, Helvetica, sans-serif' size='4'>
                                        <b>
                                        <span class='paperarialhuge'>
                                        Index_Axis_rdp1
                                        </span>
                                        </b>
                                        </font>
                                        <br>
                                        <font face='Arial, Helvetica, sans-serif' size='1'>
                                        <span class='paperdefault'>
                                        As German High Command is poised to unveil RDP plans, German industry is preparing to react to new vehicle and weapon orders.
                                        </span>
                                        </font>
                                        </td>
                                    </tr>
                                </table>
        <!-- #include file='/home/bv55/scriptinc/paper/index_axis_german_rdp1.html' --> 
            <table width='100%' border='0' cellspacing='0' cellpadding='5'>
                <tr>
                    <td align='center' valign='bottom'><a href='axis.php' class='paperdefault'>[more in Axis section]</a></td>
                </tr>
           </table>
        </td>
    </tr>
    <tr>
        <td valign='top'><!-- Old Advertising spot Left in in case still wanted/needed could also be used for Propa-->
            <table width='100%' border='0' cellspacing='0' cellpadding='3' name='community_ad' align='center' height='230' bordercolor='#000000'>
                <tr align='center' valign='middle'>
                    <td>
                    <!-- Code for image rotation -->
                                                                    <script>
                                                                    <?php
                                                                    $idir='assets/img/ads/';
                                                                    $image = glob ($idir.'*.{jpg,png,gif}', GLOB_BRACE);
                                                                    $selimage = $image[array_rand($image)];
                                                                    ?>
                                                                    </script>
                <!-- End rotation Code -->
                                                                    <img src="<?php echo $selimage ?>" alt="Factory Image randomly selected" /> 
                   

                    </td>
                </tr>
            </table>
        </td>
        <td align='center' valign='middle'> <!-- Bottom Cell R side of map -->
            This space is wide open for whatever
        </td>
    </tr>
    <tr><!-- Row Below Map -->
        <td rowspan='1' valign='top'>
<!--Allied Stats Story #1 -->
<!-- #include file='/home/bv55/scriptinc/paper/index_allied_stats1.html' -->
                                <table border='0' cellspacing='0' cellpadding='5'>
                                    <tr align='left' valign='top'> 
                                        <td> 
                                        <font size='4' face='Arial, Helvetica, sans-serif'>
                                        <b>      <span class='paperarialhuge'>Index_allied _stats1</span>
                                        </b>
                                        </font>
                                        <br>
                                        <font size='1' face='Arial, Helvetica, sans-serif'>
                                        <span class='paperdefault'>The latest moderate attacks by Axis bomber formations over Abbeville has caused  Production output to drop to 46% at that city.
                                        </span>
                                        </font>
                                        </td>
                                    </tr>
                                </table>
<!-- #include file='/home/bv55/scriptinc/paper/index_allied_stats1.html' -->                            
                                <table width='100%' border='0' cellspacing='0' cellpadding='5'>
                                    <tr>
                                        <td align='center'><a href='allied.php' class='paperdefault'>[more in Allied section]</a></td>
                                    </tr>
                                </table>
        </td>
        <td colspan='2' valign='top'> <!-- Player Sortie Storie -->
        <!--Axis Player Story -->             
        <!-- #include file='/home/bv55/scriptinc/paper/index_axis_stats2.html' --> 
                                                <table width='100%' height='100' valign='top' border='0' cellspacing='0' cellpadding='0'>
                                                            <tr align='left' valign='top'> 
                                                                <td> 
                                                                    <font size='3' face='Times New Roman, Times, serif'>
                                                                    <b><span class='papertimesbig'>Random Axis player stats generated Story 1</span>                                                               </b>
                                                                    </font>
                                                                    <br>
                                                                    <font size='1' face='Arial, Helvetica, sans-serif'>
                                                                    <span class='paperdefault'>May have to re-organize the page a bit as it wasn't built with 3 allied armies in mind.  And the Italians are also coming (eventually).  This entire little blurb is about 47 words total, as you can see plenty of room for additional stuff to be added. </span>
                                                                    </font>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><br /><a href='axis.php' class='paperdefault'>[more in Axis section]</a></td>
                                                            </tr>
                                                </table>
    <!-- #include file='/home/bv55/scriptinc/paper/index_axis_stats2.html' --> 
    <!-- end 1st story -->              
   
        

        </td>
        <td align='center'>
                        <a href='http://www.battlegroundeurope.com/index.php' target='_blank' class='papertimessmall'>LATEST DEVELOPER NEWS</a>
                    </td>
        <td height='30' valign='top'>
        <!-- Forces in the field table -->
            <table width='200'  border='0' cellpadding='0' cellspacing='0' id='forces_online'>
                <tr>
                    <td bgcolor='#333333'>
                        <table width='100%' height='30'  border='0' align='center' cellpadding='0' cellspacing='0'>
                            <tr align='left' valign='top'>
                                <td valign='middle'><div align='center'><font size='3'><b><font color='#FFFFFF' face='Times New Roman, Times, serif'>ALLIES vs AXIS</font> </b></font></div></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
    <!-- Cell Icon Table -->
                                                <table width='100%'  border='0' cellspacing='0' cellpadding='0'>

                                                    <tr>
                                                      <td align='left' valign='top'><img src='assets/img/forces/infantry_allied.gif' width='16' height='16'><img src='assets/img/forces/infantry_allied.gif' width='16' height='16'><img src='assets/img/forces/infantry_allied.gif' width='16' height='16'><img src='assets/img/forces/infantry_allied.gif' width='16' height='16'><img src='assets/img/forces/infantry_allied.gif' width='16' height='16'><img src='assets/img/forces/infantry_blank.gif' width='16' height='16'></td>
                                                      <td width='8' align='center' valign='top'><img width='8' height='1'></td>
                                                      <td align='right' valign='top'><img src='assets/img/forces/infantry_axis.gif' width='16' height='16'><img src='assets/img/forces/infantry_axis.gif' width='16' height='16'><img src='assets/img/forces/infantry_axis.gif' width='16' height='16'><img src='assets/img/forces/infantry_axis.gif' width='16' height='16'><img src='assets/img/forces/infantry_axis.gif' width='16' height='16'><img src='assets/img/forces/infantry_axis.gif' width='16' height='16'></td>
                                                    </tr>

                                                    <tr>
                                                      <td align='left' valign='top'><img src='assets/img/forces/armour_allied.gif' width='16' height='16'><img src='assets/img/forces/armour_allied.gif' width='16' height='16'><img src='assets/img/forces/armour_allied.gif' width='16' height='16'><img src='assets/img/forces/armour_allied.gif' width='16' height='16'><img src='assets/img/forces/armour_allied.gif' width='16' height='16'><img src='assets/img/forces/armour_blank.gif' width='16' height='16'></td>
                                                      <td align='center' valign='top' width='8'><img width='8' height='1'></td>
                                                      <td align='right' valign='top'><img src='assets/img/forces/armour_axis.gif' width='16' height='16'><img src='assets/img/forces/armour_axis.gif' width='16' height='16'><img src='assets/img/forces/armour_axis.gif' width='16' height='16'><img src='assets/img/forces/armour_axis.gif' width='16' height='16'><img src='assets/img/forces/armour_axis.gif' width='16' height='16'><img src='assets/img/forces/armour_axis.gif' width='16' height='16'></td>
                                                    </tr>

                                                    <tr>
                                                      <td align='left' valign='top'><img src='assets/img/forces/air_allied.gif' width='16' height='16'><img src='assets/img/forces/air_allied.gif' width='16' height='16'><img src='assets/img/forces/air_allied.gif' width='16' height='16'><img src='assets/img/forces/air_allied.gif' width='16' height='16'><img src='assets/img/forces/air_allied.gif' width='16' height='16'><img src='assets/img/forces/air_blank.gif' width='16' height='16'></td>
                                                      <td align='center' valign='top' width='8'><img width='8' height='1'></td>
                                                      <td align='right' valign='top'><img src='assets/img/forces/air_axis.gif' width='16' height='16'><img src='assets/img/forces/air_axis.gif' width='16' height='16'><img src='assets/img/forces/air_axis.gif' width='16' height='16'><img src='assets/img/forces/air_axis.gif' width='16' height='16'><img src='assets/img/forces/air_axis.gif' width='16' height='16'><img src='assets/img/forces/air_axis.gif' width='16' height='16'></td>
                                                    </tr>

                                                    <tr>
                                                      <td align='left' valign='top'><img src='assets/img/forces/navy_allied.gif' width='16' height='16'><img src='assets/img/forces/navy_allied.gif' width='16' height='16'><img src='assets/img/forces/navy_allied.gif' width='16' height='16'><img src='assets/img/forces/navy_allied.gif' width='16' height='16'><img src='assets/img/forces/navy_allied.gif' width='16' height='16'><img src='assets/img/forces/navy_allied.gif' width='16' height='16'></td>
                                                      <td align='center' valign='top' width='8'><img width='8' height='1'></td>
                                                      <td align='right' valign='top'><img src='assets/img/forces/navy_blank.gif' width='16' height='16'><img src='assets/img/forces/navy_blank.gif' width='16' height='16'><img src='assets/img/forces/navy_blank.gif' width='16' height='16'><img src='assets/img/forces/navy_blank.gif' width='16' height='16'><img src='assets/img/forces/navy_blank.gif' width='16' height='16'><img src='assets/img/forces/navy_axis.gif' width='16' height='16'></td>
                                                    </tr>

                                                </table>
                    <!-- end of icons-->
                    </td>
                </tr>
                <tr>
                    <td bgcolor='#333333'>
                        <table width='100%'  border='0' cellspacing='0' cellpadding='2'>
                            <tr>
                                <td><div align='center'> <b><font color='#FFFFFF'><font face='Arial, Helvetica, sans-serif'><i><font size='1'>combatants in the field (past 15 mins) </font></i></font></font></b></div></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td> <!-- End of Forces in the field -->
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
<! -- END of SHort Page Breakpoint -->
 */?>   
    <tr>
        <td colspan='5'>

            <table width='100%' border='0' cellspacing='2' cellpadding='0'>
                <tr>
                    <td bgcolor='#000000' height='2'><img height='1'></td>
                </tr>
                <tr>
                    <td align='center' valign='middle' bgcolor='#000000' class='paperhidden'><div align='center'>
                    <img height='7'> RIP Buckeyes! | WILLYTEE RAWKS | SNAKES ON A PLANE | BLANGETT IS SEXEH | CLINTNOMOD</div></td>
                </tr>
             </table>
         </td>
    </tr>

</table>

<!-- PAGE BOTTOM CONTENT -->

<table align='center' >
    <tr align='center' valign='top' bgcolor='#000000'> 
        <td>
            <div align='center'>
            <span class='small'>© 2001-2015 Playnet, Inc. All Rights Reserved.<br> Playnet Inc., World War II Online<SUP>TM</SUP>, WWII Online<SUP>TM</SUP> Cornered Rat Software©, Unity 3D Engine<SUP>TM</SUP> are trademarks of Playnet Incorporated.<br> Other marks used herein are those of their respective owners.</span><br>
      <br>
      <table width='90%' border='0' cellspacing='0' cellpadding='5'>
        <tr>
          <td width='50%' align='right'>
              <a href='http://www.wwiionline.com/scripts/wwiionline/tos.jsp' target='_blank' class='small'>Playnet Terms Of Service!</a>
            &nbsp;&nbsp;&nbsp;
          </td>
          <td width='50%'>
              &nbsp;&nbsp;&nbsp;
            <a href='http://www.battlegroundeurope.com/index.php/component/content/article/48' target='_blank' class='small'>Forum Rules and Conduct</a>
          </td>
        </tr>
      </table>
     </div>
    </td>
</td>
</tr>
</table>
</table>
</div>
</center>
</body>
</html>
 


