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
?>
<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>        
    <title>Battleground Europe Gazette</title>
            <link rel='stylesheet' href='assets/css/gazette.css'>
            
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
<table border='0' cellpadding='0' cellspacing='0'> <!-- Border currently on to show layout of frames -->
    <tr align='center'> <!-- Version info and Navigation Section -->
        <td colspan='5'class='story'>
            <table width='100%' border='0' cellspacing='0' cellpadding='0' bordercolor='#000000' align='center'><!-- Sets outside border -->
            <tr>
            <td>
                <table width='100%' border='0' cellspacing='0' cellpadding='0' align='center'><!-- removes cell borders border from row-->
                <tr align='left'>
                    <td>
                        <table>
                            <tr>
                                <td><a href='http://www.battlegroundeurope.net/getting-started' target='_blank' class='papertimessmall'>
                                <font color='#cc3333'>CURRENT VERSION:</font></a>
                                </td>    
                                <td><!-- START index_version -->1.34.15<!-- END index_version (LIVE) --></td>
                            </tr>
                        </table>
                    </td>
                    <td class='papertimessmall' align='right'>Campaign <?= $row['id'] ?></td> 
                    <td class='papertimessmall' align='left'>, Day: <?= $days ?> </td>
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
        <td colspan='5' class='story'>
        <table width='100%' border='0' cellspacing='0' cellpadding='3' align='center'>
            <tr valign='top' align='center'> 
                <td> 
                    <font face='Arial, Helvetica, sans-serif' size='7' color='#666666'>
                    <b><span class='paperheadline'><?= $indexMainHeadline ?></span></b>
                    </font>
                </td>
            </tr>
        </table>
        </td><!-- END MAIN HEADLINE -->
    </tr>
    <tr valign='top'><!-- MAP and 1st Story Row-->
        <td height='175' width='188' class='story'><!-- Allied Stats Story-->
            <table width='100%' align ='center' border='0' cellspacing='0' cellpadding='0'>
            <tr>
            <td>
			<?= $indexAlliedStats2 ?>
            </td>
            </tr>
            <tr>
                <td align='center' valign='bottom'><br /><a href='allied.php' class='paperdefault'>[more in Allied section]</a></td>
            </tr>
            </table>
        </td>
        <!-- Start map -->
        <td rowspan='2' colspan='3' class='story'>
    <!-- Front Line Map -->
            <table width='564' border='0' cellspacing='0' cellpadding='0' name='map_table'>
                <tr>
                    <td><!-- Placeholder image -->
                        <div align='center'><a href='http://snipets.net/WebMap/2.0/WebMap.php')>
                            <img src='http://snipets.net/WebMap/2.0/GazetteFrontPage.png' width='564' align='middle' border=0 alt='This is the map'></a>
                        </div>
                   </td>
                </tr>
                <tr bgcolor='#000000'>
                    <td align='center' class='paperdefault'> <span class='paperwhite'>GAME MAP UPDATED EVERY 15 MINUTES - <a href='http://snipets.net/WebMap/2.0/WebMap.php'>CLICK FOR FULL SIZE</a></span> </td>
                </tr>
            </table>
    <!-- END MAP -->
        </td>
        <td class='story'> 
        <!-- Attacks captures section -->
        
                                <table width='100%'border='0' cellspacing='0' cellpadding='5' >
                                    <tr align='center' valign='top'> 
                                        <td>
                                        <font face='Arial, Helvetica, sans-serif' size='4'>
                                        <span class='paperarialhuge'><b>
                                        Current Attacks:
                                        </b></span>
                                        </font>
                                        </td>
                                    <tr>
                                        <td class='paperdefault'>
                                            <table>
                                            <tr align='center'>
                                                <td width='100%'><?php
                                                while($row=$aos->fetch_assoc())
                                                { echo ("<tr><td align='center'>".$row['name']."</td><td></td></tr>"); } 
                                                ?></td>
                                            </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
        <!-- END Attacks Captures --> 

        </td>
    </tr>
    <tr>
        <td valign='top'><!-- Old Advertising spot Left in in case still wanted/needed could also be used for Propa-->
            <table width='100%' border='0' cellspacing='0' cellpadding='0' name='community_ad' align='center' height='230' >
                <tr align='center' valign='middle'>
                    <td class='story'>
                    <!-- Code for image rotation -->
                                                                    <script>
                                                                    <?php
                                                                    $idir='assets/img/ads/';
                                                                    $image = glob ($idir.'*.{jpg,png,gif}', GLOB_BRACE);
                                                                    $selimage = $image[array_rand($image)];
                                                                    ?>
                                                                    </script>
                <!-- End rotation Code -->
                    <img width='100%' src="<?php echo $selimage ?>" alt="Factory Image randomly selected" /> 
                    </td>
                </tr>
            </table>
        </td>
        <td align='center' valign='middle' width='188' class='story'> 
 <!-- Recent Captures -->
            <table width='100%'border='0' cellspacing='0' cellpadding='5'>
                <tr align='center'> 
                    <td valign='top'>
                        <font face='Arial, Helvetica, sans-serif' size='3'>
                        <span class='paperarialhuge'>
                        <b>Most Recent Captures:</b>
                        </span>
                        </font>
                    </td>
                </tr>
                <tr>
                    <td class='paperdefault' width='100%'>
                        <table>
                        <tr align='center'>
                            <td>City</td><td>Country</td>
                        <tr>
                        <td>
                    <?php while($row=$caps->fetch_assoc())
                        { echo ("<tr><td>".$row['name']."</td><td>".$row['fullName']."</td></tr>"); } ?>
                        <?php //town name for after join .$row['facility_oid']. ?>
                        </td>
                        </tr>
                        </table>
                     </td>
                </tr>
            </table>
    <!-- END Recent Captures -->
        </td>
    </tr>
    <tr><!-- Row Below Map -->
        <td rowspan='1' valign='top' class='story'>
			<?= $indexAlliedStats1 ?>
                           
			<table width='100%' border='0' cellspacing='0' cellpadding='5'>
				<tr>
					<td align='center'><a href='allied.php' class='paperdefault'>[more in Allied section]</a></td>
				</tr>
			</table>
        </td>
        <td colspan='3' valign='top' align='center'> 
        <!-- Latest News -->
            <table class='paperhidden' width='100%' height='100' valign='top' border='0' cellspacing='0' cellpadding='0' align='center'>
                <tr align='left' valign='top'> 
                    <td align='center' class='story'> 
                        <?php
                            $newsPage = file_get_contents("http://www.battlegroundeurope.com/index.php");
                                    preg_match('/<table class="contentpaneopen">(.+?)<span class="article_separator">/s',$newsPage,$firstArticle);
                            $firstArticle = str_replace('<span class="override">', '', $firstArticle[0]);
							$firstArticle = str_replace('<a href="/index.php', '<a href="http://www.battlegroundeurope.com/index.php', $firstArticle);
                            echo "<span clas='paperhidden'>".$firstArticle."</span>";
						?>
                    </td>
                </tr>
            </table>
    <!-- End Latest news -->              
        </td>
        <td height='30' valign='top' align='center' class='story'>
        <!-- Forces in the field table -->
            Need something for this spot 
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
        <td colspan='5' class='story'>
            <table width='100%' border='0' cellspacing='2' cellpadding='0'>
                <tr>
                    <td align='center' valign='middle' bgcolor='#000000' class='paperhidden'>Only the cool people know about this...... </td>
                </tr>
                <tr>
                    <td align='center' valign='middle' bgcolor='#000000' class='paperhidden'>RIP Buckeyes! | WILLYTEE RAWKS | SNAKES ON A PLANE | BLANGETT IS SEXEH | CLINTNOMOD</td>
                </tr>
             </table>
         </td>
    </tr>

</table>
</div>
</body>
</html>
 


