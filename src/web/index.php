<?php
/* 
This page is the index (main) page for the Gazette
It has the layout and look of the original page.  The data feeds are not yet in place.  
Where the feed should be, the area his marked at both top and bottom of where the includes should go.  Table information within these comments may not align with the code outside. 
as the queries place data in, it appears to all be placed L aligned.  
For reference also the data within those areas has been modified to show the old data source.
Map is aplaceholder image and the link to the full map page has been commented out pending restoration of the BE Map.   

*/
 ?>
<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
        <title>Battleground Europe Gazette</title>
	        <link rel='stylesheet' href='assets/css/gazette.css'>
	        <link rel='stylesheet' href='assets/css/wwiiol.css'>
</head>

<body background='assets/img/stats-bg.jpg'  leftmargin='0' topmargin='0' marginwidth='0' marginheight='0'>

<!-- Start of re-imaged page 940px wide -->
<table width='940' background='assets/img/paper_tile_new.jpg' border='0' cellspacing='0' cellpadding='0' align='center'>
    <tr align='center' valign='top'><!-- Top Spacer Row - sets both table and default cell size -->
        <td width='188'><img height=1></td><td width='188'></td><td width='188'></td><td width='188'></td><td width='188'></td>
    </tr>
    <tr> <!-- START Casualty Summary and title imagry row -->
        <td> <!-- Allied Casualties-->
        <table width='100%' border='1' cellspacing='0' cellpadding='3' bordercolor='#000000'>
            <tr align='center' valign='top'>
                <td bgcolor='#333333' class='paperdefault'><span class='paperwhite'>ALLIED CASUALTIES</span></td>
            </tr>
            <tr>
            <!-- #include file='/home/bv55/scriptinc/paper/index_allied_casualties.html' -->
                <td align='left' valign='top' class='paperarialsmall'>GROUND FORCES 3066<br />AIR FORCES 1407<br />SEA FORCES 112<br />Index_allied_casualties</td>
            <!-- #include file='/home/bv55/scriptinc/paper/index_allied_casualties.html' -->
            </tr>
        </table>
        </td>
        <td colspan='3' align='center' valign='bottom'><img src='assets/img/header_middle.gif' width='530' height='72'></td> <!-- Battleground Europe title image -->
        <td> <!-- Axis Casualties -->
            <table width='100%' border='1' cellspacing='0' cellpadding='3' bordercolor='#000000'>
                <tr align='center' valign='top'>
                    <td bgcolor='#333333' class='paperdefault'><span class='paperwhite'>AXIS CASUALTIES</span></td>
                </tr>
                <tr>
                <!-- #include file='/home/bv55/scriptinc/paper/index_axis_casualties.html' -->
                    <td align='right' valign='top' class='paperarialsmall'>GROUND FORCES 2231<br />AIR FORCES 1162<br />SEA FORCES 113<br />Index_Axis_caualties
                <!-- #include file='/home/bv55/scriptinc/paper/index_axis_casualties.html' -->
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr> <!-- Under header line and bottom border -->
        <td colspan='5' valign='top'><img src='assets/img/header_bar.gif' width='920' height='17'>
        </td>
    </tr>
    <tr align='center'> <!-- Version info and Navigation Section -->
    <td colspan='5'>
        <table width='100%' border='1' cellspacing='0' cellpadding='0' bordercolor='#000000' align='center'><!-- Sets outside border -->
        <tr>
        <td colspan='5'>
            <table width='100%' border='0' cellspacing='0' cellpadding='0' bordercolor='#000000' align='center'><!-- removes cell borders border from row-->
            <tr>
            <td>
                <a href='/web/20051201034059/http://forums.battlegroundeurope.com/forumdisplay.php?f=10' target='_blank' class='papertimessmall'>
                <font color='#cc3333'>CURRENT VERSION 
                    <!-- START index_version --> 1.34.12 (index_version) <!-- END index_version (LIVE) -->
                </font>
                </a>
            </td>
            <td colspan='2' align='center'>
                <a href='http://www.battlegroundeurope.com/index.php' target='_blank' class='papertimessmall'>LATEST DEVELOPER NEWS</a>
            </td>
        
            <td colspan='2'>
                <table align='center' width='100%'>
                    <tr>
                        <td class='papertimessmall' align='right'>Two Sections: </td>  
                        <td align='center'><a href='allied.php' class='papertimessmall'>ALLIED</a></td>
                        <td align='center'><a href='axis.php' class='papertimessmall'>AXIS</a></td>
                    </tr>
                </table>
            </td>
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
        <table width='100%' border='1' cellspacing='0' cellpadding='3' align='center'>
            <tr valign='top' align='center'> 
                <td> 
                    <font face='Arial, Helvetica, sans-serif' size='7' color='#666666'>
                    <b><span class='paperheadline'>index_main_headline</span></b>
                    </font>
                </td>
            </tr>
        </table>
        <!-- #include file='/home/bv55/scriptinc/paper/index_main_headline.html' -->
        </td><!-- END MAIN HEADLINE -->
    </tr>
    <tr valign='top'><!-- MAP and 1st Story Row-->
        <td height='150'><!-- Allied Stats Story-->
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
            <table width='100%' border='0' cellspacing='0' cellpadding='0' name='map_table'>
                <tr>
                    <td><!-- Placeholder image -->
                        <div align='center'><a href='http://map.wwiionline.com/belgium_front.html')>
                            <img src='assets/img/placeholder/belgium_long_front.jpg' width='100%' align='middle' border=0 alt='This is the map'></a>
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
            <table width='100%' border='1' cellspacing='0' cellpadding='3' name='community_ad' align='center' height='230' bordercolor='#000000'>
                <tr align='center' valign='Top'>
                    <td>
                    Advertisement<br />goes <br />here.
<!--<script language='JavaScript' src='/web/20051201034059js_/http://www.wwiionline.com/lib/playnet/gazette_ads_index.js'></script> Commented out -->
<!-- the above Js script was left in as a reference to the old in-page add should that functionality be requested to be restored-->
                    </td>
                </tr>
            </table>
        </td>
        <td align='center' valign='middle'> <!-- Bottom Cell R side of map -->
            This space is wide open for whatever
        </td>
    </tr>
    <tr><!-- Row Below Map -->
        <td rowspan='2' valign='top'>
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
        <td align='center' valign='middle' height='150'><!-- Open cell -->
        This space is wide open for whatever
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
    <tr>
        <td align='center' valign='middle'>This Space Available for Rent</td>
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
        <td colspan='5'>

            <table width='100%' border='0' cellspacing='2' cellpadding='0'>
                <tr>
                    <td bgcolor='#000000' height='2'><img height='1'></td>
                </tr>
                <tr>
                    <td align='center' valign='middle' bgcolor='#000000' class='paperhidden'><div align='center'>
                    <img height='7'> WILLYTEE RAWKS | SNAKES ON A PLANE | BLANGETT IS SEXEH | CLINTNOMOD</div></td>
                </tr>
             </table>
         </td>
    </tr>

</table>

<!-- PAGE BOTTOM CONTENT -->

<table border='0' cellspacing='0' cellpadding='0' align='center'>
    <tr align='center' valign='top'> 
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

</center>
</body>
</html>
 


<!-- BELOW THISPOINT IS THE ORIGINAL PAGE CONTENT SHOULD IT BE NEEDED FOR COMPARISON -->
  
<?php /*
<!-- Page -->
<table width='600' border='0' cellspacing='0' cellpadding='0' align='center'>
  <tr align='center' valign='top'>

    <td width='615'> <!-- SETS THE WIDTH OF THE TABLE -- THIS IS THE SIZE CONTROL CELL AS IT's ONLY 1 CELL WIDE-->

<!-- BEGIN GAZETTE MAIN FEATURES -->
        <table width='100%' border='0' align='left' cellpadding='0' cellspacing='0'>
            <tr>
                <td align='left' valign='top'>
<!-- START index_paper -->
                <table width='615' border='0' cellspacing='0' cellpadding='0' bordercolor='#666666' align='center' background='assets/img/paper_tile_new.jpg'>
            <tr align='left' valign='top'>
                <td>
                    <table width='621' border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                            <td><img height='2' width='100%'></td>
                        </tr>
<!-- START OF ALLIED/AXIS CASUALTIES TOP LINE w gazette image -->
                        <tr>
                            <td width='144' align='left' valign='top'>
                                <table width='144' border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                            <td>
                                <table width='100%' border='1' cellspacing='0' cellpadding='3' bordercolor='#000000'>
                                    <tr align='center' valign='top'>
                                        <td bgcolor='#333333' class='paperdefault'><span class='paperwhite'>ALLIED CASUALTIES</span></td>
                                    </tr>
                                    <tr>
<!-- #include file='/home/bv55/scriptinc/paper/index_allied_casualties.html' -->
                                        <td align='left' valign='top' class='paperarialsmall'>GROUND FORCES 3066<br />AIR FORCES 1407<br />SEA FORCES 112<br />Index_allied_casualties</td>
<!-- #include file='/home/bv55/scriptinc/paper/index_allied_casualties.html' -->
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
                <td valign='bottom' align='left'><img src='assets/img/header_middle.gif' width='333' height='74'></td>
                <td width='144' align='right' valign='top'>
                    <table width='144' border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                            <td>
                                <table width='100%' border='1' cellspacing='0' cellpadding='3' bordercolor='#000000'>
                                    <tr align='center' valign='top'>
                                        <td bgcolor='#333333' class='paperdefault'><span class='paperwhite'>AXIS CASUALTIES</span></td>
                                    </tr>
                                    <tr>
<!-- #include file='/home/bv55/scriptinc/paper/index_axis_casualties.html' -->
                                        <td align='right' valign='top' class='paperarialsmall'>GROUND FORCES 2231<br />AIR FORCES 1162<br />SEA FORCES 113<br />Index_Axis_caualties
<!-- #include file='/home/bv55/scriptinc/paper/index_axis_casualties.html' -->
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr align='left' valign='top'>
                    <td colspan='3'><img src='assets/img/header_bar.gif' width='621' height='17'></td>
            </tr>
        </table>
<!-- END OF TOPLINE (allied and axis Casualties) -->
        <table width='575' border='1' cellspacing='0' cellpadding='0' align='center' bordercolor='#000000'>
            <tr>
                <td>
                    <table width='575' border='0' cellspacing='0' cellpadding='2' bordercolor='#000000' align='center'>
                        <tr valign='top' align='center'>
                            <td width='136'><a href='/web/20051201034059/http://forums.battlegroundeurope.com/forumdisplay.php?f=10' target='_blank' class='papertimessmall'>
                            <font color='#cc3333'>CURRENT VERSION 
<!-- START index_version -->
1.34.12 (index_version)
<!-- END index_version (LIVE) -->
                            </font></a>
                            </td>
                            <td width='184'><a href='http://www.battlegroundeurope.com/index.php' target='_blank' class='papertimessmall'>LATEST NEWS</a></td>
                            <td width='160' class='papertimessmall'>Two Sections:   <a href='allied.php' class='papertimessmall'>ALLIED</a> and 
                                                                                    <a href='axis.php' class='papertimessmall'>AXIS</a></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        
<!-- Main Headline -->
<!-- #include file='/home/bv55/scriptinc/paper/index_main_headline.html' -->

        <table width='100%' border='0' cellspacing='0' cellpadding='3' align='center'>
            <tr valign='top' align='center'> 
                <td> 
                    <font face='Arial, Helvetica, sans-serif' size='7' color='#666666'>
                    <b><span class='paperheadline'> index_main_headline</span></b>
                    </font>
                </td>
            </tr>
        </table>
<!-- #include file='/home/bv55/scriptinc/paper/index_main_headline.html' -->

<!-- Start of the Stories-->               
        <table width='615' border='0' cellspacing='0' cellpadding='0' name='story_content' bordercolor='#000000'>
            <tr align='left' valign='top'>
                <td width= '132' rowspan='3'>
                    <table width='130' border='0' cellspacing='0' cellpadding='0' name='leftside_content1'>
                        <tr align='left' valign='top' bgcolor='#000000'>
                            <td colspan='2'><img height=1></td>
                        </tr>
                        <tr align='left' valign='top'>
                             <td width='124'>
                                <table width='100%' border='0' cellspacing='0' cellpadding='5'>
                                    <tr align='left' valign='top'>
                                        <td>
<!--Allied Player Stat Story 2 -->
<!-- #include file='/home/bv55/scriptinc/paper/index_allied_stats2.html' -->
                                            <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                                                <tr align='center' valign='top'> 
                                                    <td> 
                                                    <font size='3' face='Times New Roman, Times, serif'>
                                                    <b><span class='papertimesbig'>index_allied_stats2</span></b>
                                                    </font>
                                                    <br>
                                                    <font size='1' face='Arial, Helvetica, sans-serif'>
                                                    <span class='paperdefault'>An independent analysis of military airfields controlled by the major combatants indicate that United Kingdom now controls 38% of the airfields (19 total), followed by Germany with 34% (17) and France with 26% (13).</span>
                                                    </font>
                                                    </td>
                                                </tr>
                                            </table>
<!-- #include file='/home/bv55/scriptinc/paper/index_allied_stats2.html' -->
                                        <br>
                                        <a href='allied.php' class='paperdefault'>[more in Allied section]</a>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td width='1' bgcolor='#000000'><img width='1'></td>
                        </tr>
                    </table> 
<!-- This table is for an in-page advertisement -->                    
                    <table width='125' border='1' cellspacing='0' cellpadding='3' name='community_ad' align='center' height='150' bordercolor='#000000'>
                        <tr align='center' valign='middle'>
                            <td>
                                Advertisement<br />goes <br />here.
<!--<script language='JavaScript' src='/web/20051201034059js_/http://www.wwiionline.com/lib/playnet/gazette_ads_index.js'></script> Commented out -->
<!-- the above Js script was left in as a reference to the old in-page add should that functionality be requested to be restored-->
                            </td>
                        </tr>
                    </table>
                    <br />
                    <table width='130' border='0' cellspacing='0' cellpadding='0' name='leftside_content2'>
                        <tr align='left' valign='top' bgcolor='#000000'>
                            <td colspan='2'><img height='1'></td>
                        </tr>
                        <tr align='left' valign='top'>
                            <td width='124'>
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
                                        <td><a href='allied.php' class='paperdefault'>[more in Allied section]</a></td>
                                    </tr>
                                </table>
                            </td>
                            <td width='1' bgcolor='#000000'><img width='1'></td>
                        </tr>
                    </table>
                <br />
                </td>
<!-- Center Map -->
                <td align='center'>
                    <table width='350' border='0' cellspacing='0' cellpadding='0' name='map_table'>
                        <tr>
                            <td>
<!-- Placeholder image -->      <div align='center'><a href='http://map.wwiionline.com/belgium_front.html')>
                                <img src='assets/img/placeholder/belgium_long_front.jpg' width='350' height='243' align='middle' border=0 alt='This is the map'></a></div>
                            </td>
                        </tr>
                        <tr bgcolor='#000000'>
                            <td align='center' class='paperdefault'> <span class='paperwhite'>GAME MAP UPDATED EVERY 5 MINUTES - <a href=http://map.wwiionline.com/belgium_front.html'>CLICK FOR FULL SIZE</a></span> </td>
                        </tr>
                    </table>
                </td>
<!-- end Map -->    
                <td width='133' height='255' align='right'>
                    <table width='123' border='0' cellspacing='0' cellpadding='0' name='rightsideofmap_structure' align='center'>
                        <tr align='left' valign='top' bgcolor='#000000'>
                            <td colspan='2'><img height=1></td>
                        </tr>
                        <tr align='left' valign='top'>
                            <td width='1' bgcolor='#000000'> <img width=1> </td>
                            <td>
<!--Axis RDP Story #1 -->
<!-- #include file='/home/bv55/scriptinc/paper/index_axis_german_rdp1.html' -->
                                <table border='0' cellspacing='0' cellpadding='5'>
                                    <tr align='left' valign='top'> 
                                        <td>
                                        <font face='Arial, Helvetica, sans-serif' size='4'>
                                        <b>
                                        <span class='paperarialhuge'>
                                        Index Axis_rdp 1
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
                                      <tr align='left' valign='top'>
                                            <td><a href='axis.php' class='paperdefault'>[more in Axis section]</a></td>
                                      </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
<!-- Below Map Area -->            
            <tr align='left' valign='top'>
                <td colspan='2' height='246'> <br>
                    <table width='100%' border='0' cellspacing='0' cellpadding='0' name='below_map_content1'>
                        <tr align='left' valign='top' bgcolor='#000000'>
                            <td><img height=1></td>
                        </tr>
                        <tr align='left' valign='top'>
                            <td>
                                <table width='100%' border='0' cellspacing='0' cellpadding='5'>
                                    <tr>
                            <td>
<!--Axis Player Story -->             
<!-- #include file='/home/bv55/scriptinc/paper/index_axis_stats1.html' -->
                                                <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                                                            <tr align='left' valign='top'> 
                                                                <td> 
                                                                    <font size='3' face='Times New Roman, Times, serif'>
                                                                    <b><span class='papertimesbig'>Open SPot for Story</span>                                                               </b>
                                                                    </font>
                                                                    <br>
                                                                    <font size='1' face='Arial, Helvetica, sans-serif'>
                                                                    <span class='paperdefault'>May have to re-organize the page a bit as it wasn't built with 3 allied armies in mind.  And the Italians are also coming (eventually)</span>
                                                                    </font>
                                                                </td>
                                                            </tr>
                                                        </table>
<!-- #include file='/home/bv55/scriptinc/paper/index_axis_stats1.html' -->
                                            <table width='100%'  border='0' cellpadding='0' cellspacing='0' bordercolor='#FFFFFF'>
                                                <tr>
                                                    <td align='left' valign='top'>
<!--Axis Player Stats Story #2 -->
<!-- #include file='/home/bv55/scriptinc/paper/index_axis_stats2.html' -->
                                                        <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                                                            <tr align='left' valign='top'> 
                                                                <td> 
                                                                    <font size='3' face='Times New Roman, Times, serif'>
                                                                    <b><span class='papertimesbig'>Axis Stats 2</span>                                                               </b>
                                                                    </font>
                                                                    <br>
                                                                    <font size='1' face='Arial, Helvetica, sans-serif'>
                                                                    <span class='paperdefault'>United Kingdom now owns 38% of the airfields in the European Theatre of Operations. With this decided advantage, forward air units are now able to wreak havoc on enemy factories and front line positions.</span>
                                                                    </font>
                                                                </td>
                                                            </tr>
                                                        </table>
<!-- #include file='/home/bv55/scriptinc/paper/index_axis_stats2.html' -->                                          
                                          <br>
                                        <a href='axis.php' class='paperdefault'>[more in Axis section]</a></td>
                                        <td width='6' align='left' valign='top'><img width='6' height='1'></td>
                                        <td width='200' align='left' valign='top'>
 <!-- FORCES INCLUDE -->
                                        <table width='200'  border='0' cellpadding='0' cellspacing='0' id='forces_online'>
                                          <tr>
                                            <td bgcolor='#333333'>
                                                <table width='95%' height='25'  border='0' align='center' cellpadding='0' cellspacing='0'>
                                                    <tr align='left' valign='top'>
                                                      <td valign='middle'><div align='center'><font size='3'><b><font color='#FFFFFF' face='Times New Roman, Times, serif'>ALLIES vs AXIS</font> </b></font></div></td>
                                                    </tr>
                                                </table>
                                            </td>
                                          </tr>
<!-- START OF FORCES CHART -->
<!-- #include file='/home/bv55/scriptinc/paper/index_forces.html' -->
                                          <tr>
                                            <td>
                                                <table width='100%'  border='0' cellspacing='0' cellpadding='0'>
                                                    <tr>
                                                      <td align='left' valign='top' height='5' colspan='3'><img height='5' width='1'></td>
                                                    </tr>
                                                    <tr>
                                                      <td align='left' valign='top'><img src='assets/img/forces/infantry_allied.gif' width='16' height='16'><img src='assets/img/forces/infantry_allied.gif' width='16' height='16'><img src='assets/img/forces/infantry_allied.gif' width='16' height='16'><img src='assets/img/forces/infantry_allied.gif' width='16' height='16'><img src='assets/img/forces/infantry_allied.gif' width='16' height='16'><img src='assets/img/forces/infantry_blank.gif' width='16' height='16'></td>
                                                      <td width='8' align='center' valign='top'><img width='8' height='1'></td>
                                                      <td align='right' valign='top'><img src='assets/img/forces/infantry_axis.gif' width='16' height='16'><img src='assets/img/forces/infantry_axis.gif' width='16' height='16'><img src='assets/img/forces/infantry_axis.gif' width='16' height='16'><img src='assets/img/forces/infantry_axis.gif' width='16' height='16'><img src='assets/img/forces/infantry_axis.gif' width='16' height='16'><img src='assets/img/forces/infantry_axis.gif' width='16' height='16'></td>
                                                    </tr>
                                                    <tr>
                                                      <td height='5' colspan='3' align='left' valign='top'><img height='5' width='1'></td>
                                                    </tr>
                                                    <tr>
                                                      <td align='left' valign='top'><img src='assets/img/forces/armour_allied.gif' width='16' height='16'><img src='assets/img/forces/armour_allied.gif' width='16' height='16'><img src='assets/img/forces/armour_allied.gif' width='16' height='16'><img src='assets/img/forces/armour_allied.gif' width='16' height='16'><img src='assets/img/forces/armour_allied.gif' width='16' height='16'><img src='assets/img/forces/armour_blank.gif' width='16' height='16'></td>
                                                      <td align='center' valign='top' width='8'><img width='8' height='1'></td>
                                                      <td align='right' valign='top'><img src='assets/img/forces/armour_axis.gif' width='16' height='16'><img src='assets/img/forces/armour_axis.gif' width='16' height='16'><img src='assets/img/forces/armour_axis.gif' width='16' height='16'><img src='assets/img/forces/armour_axis.gif' width='16' height='16'><img src='assets/img/forces/armour_axis.gif' width='16' height='16'><img src='assets/img/forces/armour_axis.gif' width='16' height='16'></td>
                                                    </tr>
                                                    <tr>
                                                      <td height='5' align='left' valign='top' colspan='3' ><img height='5' width='1'></td>
                                                    </tr>
                                                    <tr>
                                                      <td align='left' valign='top'><img src='assets/img/forces/air_allied.gif' width='16' height='16'><img src='assets/img/forces/air_allied.gif' width='16' height='16'><img src='assets/img/forces/air_allied.gif' width='16' height='16'><img src='assets/img/forces/air_allied.gif' width='16' height='16'><img src='assets/img/forces/air_allied.gif' width='16' height='16'><img src='assets/img/forces/air_blank.gif' width='16' height='16'></td>
                                                      <td align='center' valign='top' width='8'><img width='8' height='1'></td>
                                                      <td align='right' valign='top'><img src='assets/img/forces/air_axis.gif' width='16' height='16'><img src='assets/img/forces/air_axis.gif' width='16' height='16'><img src='assets/img/forces/air_axis.gif' width='16' height='16'><img src='assets/img/forces/air_axis.gif' width='16' height='16'><img src='assets/img/forces/air_axis.gif' width='16' height='16'><img src='assets/img/forces/air_axis.gif' width='16' height='16'></td>
                                                    </tr>
                                                    <tr>
                                                      <td height='5' align='left' valign='top' colspan='3'><img height='5' width='1'></td>
                                                    </tr>
                                                    <tr>
                                                      <td align='left' valign='top'><img src='assets/img/forces/navy_allied.gif' width='16' height='16'><img src='assets/img/forces/navy_allied.gif' width='16' height='16'><img src='assets/img/forces/navy_allied.gif' width='16' height='16'><img src='assets/img/forces/navy_allied.gif' width='16' height='16'><img src='assets/img/forces/navy_allied.gif' width='16' height='16'><img src='assets/img/forces/navy_allied.gif' width='16' height='16'></td>
                                                      <td align='center' valign='top' width='8'><img width='8' height='1'></td>
                                                      <td align='right' valign='top'><img src='assets/img/forces/navy_blank.gif' width='16' height='16'><img src='assets/img/forces/navy_blank.gif' width='16' height='16'><img src='assets/img/forces/navy_blank.gif' width='16' height='16'><img src='assets/img/forces/navy_blank.gif' width='16' height='16'><img src='assets/img/forces/navy_blank.gif' width='16' height='16'><img src='assets/img/forces/navy_axis.gif' width='16' height='16'></td>
                                                    </tr>
                                                    <tr>
                                                      <td height='5' align='left' valign='top' colspan='3'><img height='5' width='1'></td>
                                                    </tr>
                                                </table>
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
<!-- #include file='/home/bv55/scriptinc/paper/index_forces.html' -->
                                        </td>

                                      </tr>
                                    </table>
                                  </td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                        </table>
                        <table width='100%' border='0' cellspacing='0' cellpadding='0' name='below_map_content2' bordercolor='#FF3366'>
                            <tr align='left' valign='top' >
                                <td colspan='4' bgcolor='#000000'><img height=1></td>
                            </tr>
                            <tr align='left' valign='top' >
                                <td colspan='4' >
                                    <table width='350' border='0' cellspacing='0' cellpadding='5' name='allied_rdp_headline_content' align='left' bordercolor='#000000'>
                                        <tr>
                                          <td align='center' class='paperarialhuge'> <b>Allied RDP Headline</b> </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr align='left' valign='top'>
                                <td width='175'>
                                    <table width='350' border='0' cellspacing='0' cellpadding='0'>
                                        <tr>
                                        <td align='left' valign='top'>
<!--Allied British RDP Story #1 -->
<!-- #include file='/home/bv55/scriptinc/paper/index_allied_british_rdp1.html' -->
                                    <table width='100%' border='0' cellspacing='0' cellpadding='5'>
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
                                          <td align='left' valign='top'>
 <!--Allied French RDP Story #1 -->
 <!-- #include file='/home/bv55/scriptinc/paper/index_allied_french_rdp1.html' -->
                                    <table width='100%' border='0' cellspacing='0' cellpadding='5'>
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
                                            <td colspan='2' align='center'> <a href='allied.php' class='paperarialmedium'><b>[See 'RDP Reports' in Allied Section]</b></a> </td>
                                        </tr>
                                        <tr valign='bottom' align='left'>
                                            <td colspan=2>
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
                                        </tr>
                                    </table>
                                </td>
                                <td bgcolor='#000000' width='1'><img height=1></td>
                                <td width='120'>
<!--Axis Stats Story #1 -->
<!-- #include file='/home/bv55/scriptinc/paper/index_axis_stats1.html' -->
                              <table width='121' border='0' cellspacing='0' cellpadding='5'>
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
                              <table width='100%' border='0' cellspacing='0' cellpadding='5'>
                                <tr align='left' valign='top'>
                                  <td><a href='axis.php' class='paperdefault'>[more in Axis Section]</a></td>
                                </tr>
                              </table>
                              <br>
                            </td>
                          </tr>
                        </table>
                    </td>
                  </tr>
                </table>*/?>                                  