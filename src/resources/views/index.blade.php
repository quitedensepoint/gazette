@extends('layouts.master-' . $frame)

@section('gazette-content')
<div class="container" style="width:920px">
<table width="615" border="0" cellspacing="0" cellpadding="0" bordercolor="#666666" align="left" background="/assets/img/paper_tile_new.jpg">
            <tbody><tr align="left" valign="top">
              <td>
                <table width="621" border="0" cellspacing="0" cellpadding="0">
                  <tbody><tr>
                    <td><img height="2" width="100%"></td>

                  </tr>
                  <tr>
                    <td width="144" align="left" valign="top"><table width="144" border="0" cellspacing="0" cellpadding="0">
                      <tbody><tr>
                        <td><img width="2" height="1"></td>
                        <td><table width="100%" border="1" cellspacing="0" cellpadding="3" bordercolor="#000000">

                          <tbody><tr align="center" valign="top">
                            <td bgcolor="#333333" class="paperdefault"><span class="paperwhite">ALLIED CASUALTIES</span></td>
                          </tr>
                          <tr>
                            <td align="left" valign="top" class="paperarialsmall">
                              GROUND FORCES 3066<br>
AIR FORCES 1407<br>
SEA FORCES 112<br>

                            </td>
                          </tr>
                        </tbody></table></td>
                      </tr>
                    </tbody></table>
</td>
                    <td valign="bottom" align="left"><div align="center"><img src="/assets/img/header_middle.gif" width="333" height="74"></div></td>
                    <td width="144" align="right" valign="top"><table width="144" border="0" cellspacing="0" cellpadding="0">
                      <tbody><tr>
                        <td><table width="100%" border="1" cellspacing="0" cellpadding="3" bordercolor="#000000">

                          <tbody><tr align="center" valign="top">
                            <td bgcolor="#333333" class="paperdefault"><span class="paperwhite">AXIS CASUALTIES</span></td>
                            </tr>
                          <tr>
                            <td align="right" valign="top" class="paperarialsmall">
                              GROUND FORCES 2231<br>
AIR FORCES 1162<br>
SEA FORCES 113<br>

                            </td>
                          </tr>
                        </tbody></table></td>
                        <td><img width="2" height="1"></td>
                      </tr>
                    </tbody></table>
</td>
                  </tr>
                  <tr align="left" valign="top">
                    <td colspan="3"><div align="center"><img src="/assets/img/header_bar.gif" width="621" height="17"></div></td>
                  </tr>
                </tbody></table>
                <table width="575" border="1" cellspacing="0" cellpadding="0" align="center" bordercolor="#000000">
                  <tbody><tr>
                    <td>
                      <table width="575" border="0" cellspacing="0" cellpadding="2" bordercolor="#000000" align="center">
                        <tbody><tr valign="top" align="center">
                          <td width="136"><a href="http://forums.battlegroundeurope.com/forumdisplay.php?f=10" target="_blank" class="papertimessmall"><font color="#cc3333">CURRENT VERSION 
<!-- START index_version -->
1.20.2
<!-- END index_version (LIVE) -->
</font></a></td>
                          <td width="184"><a href="http://forums.battlegroundeurope.com/forumdisplay.php?f=3" target="_blank" class="papertimessmall">NEWS ON WHAT'S COMING NEXT</a></td>
                          <td width="160" class="papertimessmall">Two Sections- <a href="{{ route('allied-gazette') }}" class="papertimessmall">ALLIED</a> and <a href="{{ route('allied-gazette') }}" class="papertimessmall">AXIS</a></td>
                        </tr>
                      </tbody></table>
                    </td>
                  </tr>
                </tbody></table>
                <!-- Main Headline -->
                <table width="100%" border="0" cellspacing="0" cellpadding="3" align="center">
<tbody><tr valign="top"> 
<td> 
<div align="center">
<font face="Arial, Helvetica, sans-serif" size="7">
<font color="#666666">
<b>
<span class="paperheadline"> TITANIC CLASH FOR ANTWERP</span>
</b>
</font>
</font>
</div>
</td>
</tr>
</tbody></table>

                <table width="615" border="0" cellspacing="0" cellpadding="0" name="story_content" bordercolor="#000000">
                  <tbody><tr align="left" valign="top">
                    <td width="132" rowspan="3">
                      <table width="130" border="0" cellspacing="0" cellpadding="0" name="leftside_content1">
                        <tbody><tr align="left" valign="top" bgcolor="#000000">
                          <td colspan="2"><img height="1"></td>
                        </tr>
                        <tr align="left" valign="top">
                          <td width="124">
                            <table width="100%" border="0" cellspacing="0" cellpadding="5">
                              <tbody><tr align="left" valign="top">
                                <td>
                                  <!--Allied Player Stat Story 2 -->
                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
<tbody><tr align="left" valign="top"> 
<td> 
<font size="3" face="Times New Roman, Times, serif">
<b>
<span class="papertimesbig">United Kingdom Controls The Skies</span>
</b>
</font>
<br>
<font size="1" face="Arial, Helvetica, sans-serif">
<span class="paperdefault">United Kingdom now owns 38% of the airfields in the European Theatre of Operations. With this decided advantage, forward air units are now able to wreak havoc on enemy factories and front line positions.</span>
</font>
</td>
</tr>
</tbody></table>

                                  <br>
                                <a href="#" class="paperdefault">[more in Allied section]</a> </td>
                              </tr>
                            </tbody></table>
                          </td>
                          <td width="1" bgcolor="#000000"><img width="1"></td>
                        </tr>
                      </tbody></table>
                      <br>
                      <table width="125" border="0" cellspacing="0" cellpadding="3" name="community_ad" align="center" height="150" bordercolor="#000000">
                        <tbody><tr align="center" valign="middle">
                          <td>
                           <a href="/scripts/wwiionline/allies.jsp"><img src="/assets/img/placeholder/index_ad3.gif" width="125" height="150" border="0"></a>
                          </td>
                        </tr>
                      </tbody></table>
                      <br>
                      <table width="130" border="0" cellspacing="0" cellpadding="0" name="leftside_content2">
                        <tbody><tr align="left" valign="top" bgcolor="#000000">
                          <td colspan="2"><img height="1"></td>
                        </tr>
                        <tr align="left" valign="top">
                          <td width="124">
                            <!--Allied Stats Story #1 -->
                            <table border="0" cellspacing="0" cellpadding="5">
<tbody><tr align="left" valign="top"> 
<td> 
<font size="4" face="Arial, Helvetica, sans-serif">
<b>
<span class="paperarialhuge">Germany Tightens Grip on Frontlines</span>
</b>
</font>
<br>
<font size="1" face="Arial, Helvetica, sans-serif">
<span class="paperdefault">German forces are  mounting deadly assaults on the frontlines throughout all combat zones of Western Europe. Axis assaults are breaking through the lines in several locations across the combat front.
<br><br>
The war is rising in pitch to become a meatgrinder of death and destruction and the support of all in the pursuit of victory is becoming increasingly urgent everywhere.</span>
</font>
</td>
</tr>
</tbody></table>

                            <table width="100%" border="0" cellspacing="0" cellpadding="5">
                              <tbody><tr>
                                <td><a href="#" class="paperdefault">[more in Allied section]</a></td>
                              </tr>
                            </tbody></table>
                          </td>
                          <td width="1" bgcolor="#000000"><img width="1"></td>
                        </tr>
                      </tbody></table>
                      <br>
                    </td>
                    <td align="center">
                      <table width="350" border="0" cellspacing="0" cellpadding="0" name="map_table">
                        <tbody><tr>
                          <td>
                            <div align="center"><a href="#"><img src="/assets/img/placeholder/belgium_long_front.jpg" width="350" height="243" align="middle" border="0"></a></div>
                          </td>
                        </tr>
                        <tr bgcolor="#000000">
                          <td align="center" class="paperdefault"> <span class="paperwhite">GAME MAP UPDATED EVERY 5 MINUTES - <a href="#" onclick="return doWindow(&quot;mapWin&quot;, &quot;/web/20051127032718/http://map.wwiionline.com/belgium_front.html&quot;, 731, 570, false);">CLICK FOR FULL SIZE</a></span> </td>
                        </tr>
                      </tbody></table>
                    </td>
                    <td width="133" height="255" align="right">
                      <table width="123" border="0" cellspacing="0" cellpadding="0" name="rightsideofmap_structure" align="center">
                        <tbody><tr align="left" valign="top" bgcolor="#000000">
                          <td colspan="2"><img height="1"></td>
                        </tr>
                        <tr align="left" valign="top">
                          <td width="1" bgcolor="#000000"> <img width="1"> </td>
                          <td>
                            <!--Axis RDP Story #1 -->
                            <table border="0" cellspacing="0" cellpadding="5">
<tbody><tr align="left" valign="top"> 
<td>
<font face="Arial, Helvetica, sans-serif" size="4">
<b>
<span class="paperarialhuge">
GERMAN INDUSTRY AT THE READY
</span>
</b>
</font>
<br>
<font face="Arial, Helvetica, sans-serif" size="1">
<span class="paperdefault">
As German High Command is poised to unveil RDP plans, German industry is preparing to react to new vehicle and weapon orders.
</span>
</font>
</td>
</tr>
</tbody></table>
                            <!-- #include file="/home/bv55/scriptinc/paper/index_axis_german_rdp1.html" -->
                            <table width="100%" border="0" cellspacing="0" cellpadding="5">
                              <tbody><tr align="left" valign="top">
                                <td><a href="#" class="paperdefault">[more in Axis section]</a></td>
                              </tr>
                            </tbody></table>
                          </td>
                        </tr>
                      </tbody></table>
                    </td>
                  </tr>
                  <tr align="left" valign="top">
                    <td colspan="2" height="246"> <br>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" name="below_map_content1">
                          <tbody><tr align="left" valign="top" bgcolor="#000000">
                            <td><img height="1"></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td>
                              <!--Axis Player Story -->
                              <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                <tbody><tr>
                                  <td>
                                    <!-- commented out due to lack of content -->
                                    <!-- some content -->
                                    <!-- some contetn body -->
                                    <table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF">
                                      <tbody><tr>
                                        <td align="left" valign="top">
                                          <!--Axis Player Stats Story #2 -->
                                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
<tbody><tr align="left" valign="top"> 
<td> 
<font size="3" face="Times New Roman, Times, serif">
<b>
<span class="papertimesbig">Germany Marches Forward!</span>
</b>
</font>
<br>
<font size="1" face="Arial, Helvetica, sans-serif">
<span class="paperdefault">Successes all along the front lines were felt by enemy forces as German air, armor and 
infantry units punched holes through front line positions. German population centers eager for victory cheered as the news spread across the country.</span>
</font>
</td>
</tr>
</tbody></table>
                                          <!-- #include file="/home/bv55/scriptinc/paper/index_axis_stats2.html" -->
                                          <br>
                                        <a href="#" class="paperdefault">[more in Axis section]</a></td>
                                        <td width="6" align="left" valign="top"><img width="6" height="1"></td>
                                        <td width="200" align="left" valign="top">
                                          <!-- FORCES INCLUDE -->
                                          <table width="200" border="0" cellpadding="0" cellspacing="0" id="forces_online">
  <tbody><tr>
    <td bgcolor="#333333"><table width="95%" height="25" border="0" align="center" cellpadding="0" cellspacing="0">
        <tbody><tr align="left" valign="top">
          <td valign="middle"><div align="center"><font size="3"><b><font color="#FFFFFF" face="Times New Roman, Times, serif">ALLIES vs AXIS</font> </b></font></div></td>
        </tr>
    </tbody></table></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tbody><tr>
          <td align="left" valign="top" height="5" colspan="3"><img height="5" width="1"></td>
        </tr>
        <tr>
          <td align="left" valign="top"><img src="/assets/img/forces/infantry_allied.gif" width="16" height="16"><img src="/assets/img/forces/infantry_allied.gif" width="16" height="16"><img src="/assets/img/forces/infantry_allied.gif" width="16" height="16"><img src="/assets/img/forces/infantry_allied.gif" width="16" height="16"><img src="/assets/img/forces/infantry_allied.gif" width="16" height="16"><img src="/assets/img/forces/infantry_blank.gif" width="16" height="16"></td>
          <td width="8" align="center" valign="top"><img width="8" height="1"></td>
          <td align="right" valign="top"><img src="/assets/img/forces/infantry_axis.gif" width="16" height="16"><img src="/assets/img/forces/infantry_axis.gif" width="16" height="16"><img src="/assets/img/forces/infantry_axis.gif" width="16" height="16"><img src="/assets/img/forces/infantry_axis.gif" width="16" height="16"><img src="/assets/img/forces/infantry_axis.gif" width="16" height="16"><img src="/assets/img/forces/infantry_axis.gif" width="16" height="16"></td>
        </tr>
        <tr>
          <td height="5" colspan="3" align="left" valign="top"><img height="5" width="1"></td>
        </tr>
        <tr>
          <td align="left" valign="top"><img src="/assets/img/forces/armour_allied.gif" width="16" height="16"><img src="/assets/img/forces/armour_allied.gif" width="16" height="16"><img src="/assets/img/forces/armour_allied.gif" width="16" height="16"><img src="/assets/img/forces/armour_allied.gif" width="16" height="16"><img src="/assets/img/forces/armour_allied.gif" width="16" height="16"><img src="/assets/img/forces/armour_allied.gif" width="16" height="16"></td>
          <td align="center" valign="top" width="8"><img width="8" height="1"></td>
          <td align="right" valign="top"><img src="/assets/img/forces/armour_blank.gif" width="16" height="16"><img src="/assets/img/forces/armour_blank.gif" width="16" height="16"><img src="/assets/img/forces/armour_axis.gif" width="16" height="16"><img src="/assets/img/forces/armour_axis.gif" width="16" height="16"><img src="/assets/img/forces/armour_axis.gif" width="16" height="16"><img src="/assets/img/forces/armour_axis.gif" width="16" height="16"></td>
        </tr>
        <tr>
          <td height="5" align="left" valign="top" colspan="3"><img height="5" width="1"></td>
        </tr>
        <tr>
          <td align="left" valign="top"><img src="/assets/img/forces/air_allied.gif" width="16" height="16"><img src="/assets/img/forces/air_allied.gif" width="16" height="16"><img src="/assets/img/forces/air_allied.gif" width="16" height="16"><img src="/assets/img/forces/air_allied.gif" width="16" height="16"><img src="/assets/img/forces/air_allied.gif" width="16" height="16"><img src="/assets/img/forces/air_allied.gif" width="16" height="16"></td>
          <td align="center" valign="top" width="8"><img width="8" height="1"></td>
          <td align="right" valign="top"><img src="/assets/img/forces/air_blank.gif" width="16" height="16"><img src="/assets/img/forces/air_blank.gif" width="16" height="16"><img src="/assets/img/forces/air_blank.gif" width="16" height="16"><img src="/assets/img/forces/air_blank.gif" width="16" height="16"><img src="/assets/img/forces/air_axis.gif" width="16" height="16"><img src="/assets/img/forces/air_axis.gif" width="16" height="16"></td>
        </tr>
        <tr>
          <td height="5" align="left" valign="top" colspan="3"><img height="5" width="1"></td>
        </tr>
        <tr>
          <td align="left" valign="top"><img src="/assets/img/forces/navy_blank.gif" width="16" height="16"><img src="/assets/img/forces/navy_blank.gif" width="16" height="16"><img src="/assets/img/forces/navy_blank.gif" width="16" height="16"><img src="/assets/img/forces/navy_blank.gif" width="16" height="16"><img src="/assets/img/forces/navy_blank.gif" width="16" height="16"><img src="/assets/img/forces/navy_blank.gif" width="16" height="16"></td>
          <td align="center" valign="top" width="8"><img width="8" height="1"></td>
          <td align="right" valign="top"><img src="/assets/img/forces/navy_axis.gif" width="16" height="16"><img src="/assets/img/forces/navy_axis.gif" width="16" height="16"><img src="/assets/img/forces/navy_axis.gif" width="16" height="16"><img src="/assets/img/forces/navy_axis.gif" width="16" height="16"><img src="/assets/img/forces/navy_axis.gif" width="16" height="16"><img src="/assets/img/forces/navy_axis.gif" width="16" height="16"></td>
        </tr>
        <tr>
          <td height="5" align="left" valign="top" colspan="3"><img height="5" width="1"></td>
        </tr>
    </tbody></table></td>
  </tr>
  <tr>
    <td bgcolor="#333333"><table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tbody><tr>
          <td><div align="center"> <b><font color="#FFFFFF"><font face="Arial, Helvetica, sans-serif"><i><font size="1">combatants in the field (past 15 mins) </font></i></font></font></b></div></td>
        </tr>
    </tbody></table></td>
  </tr>
</tbody></table>
                                        </td>
                                      </tr>
                                    </tbody></table>
                                  </td>
                                </tr>
                              </tbody></table>
                            </td>
                          </tr>
                        </tbody></table>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" name="below_map_content2" bordercolor="#FF3366">
                          <tbody><tr align="left" valign="top">
                            <td colspan="4" bgcolor="#000000"><img height="1"></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td colspan="4">
                              <table width="350" border="0" cellspacing="0" cellpadding="5" name="allied_rdp_headline_content" align="left" bordercolor="#000000">
                                <tbody><tr>
                                  <td align="center" class="paperarialhuge"> <b>Allied Industry Answers Call</b> </td>
                                </tr>
                              </tbody></table>
                            </td>
                          </tr>
                          <tr align="left" valign="top">
                            <td width="175">
                              <table width="350" border="0" cellspacing="0" cellpadding="0">
                                <tbody><tr>
                                  <td align="left" valign="top">

                                    <table width="100%" border="0" cellspacing="0" cellpadding="5">
<tbody><tr valign="top" align="left"> 
<td> 
<font face="Arial, Helvetica, sans-serif" size="1">
<span class="paperdefault">
Recent meetings between industry leaders and Allied Command took place near London.
<br><br>
Insiders say that Command has said the industrial war machine must make itself ready for the forthcoming factory orders for both increases and new vehicle production.
</span>
</font>
</td>
</tr>
</tbody></table>
                                  </td>
                                  <td align="left" valign="top">
                                    <!--Allied French RDP Story #1 -->
                                    <table width="100%" border="0" cellspacing="0" cellpadding="5">
<tbody><tr align="left" valign="top"> 
<td> 
<font face="Arial, Helvetica, sans-serif" size="1">
<span class="paperdefault">
"We must not fail to prepare for what lies ahead." This was the recent message from French military officials in a public addess directly squarly towards French industry.
<br><br>
Factories across France and french-held territories are stocking up on oil and raw materials in anticipation of new orders from Allied High Command.
</span>
</font>
</td>
</tr>
</tbody></table>

                                  </td>
                                </tr>
                                <tr>
                                  <td colspan="2" align="center"> <a href="#" class="paperarialmedium"><b>[See 'RDP Reports' in Allied Section]</b></a> </td>
                                </tr>
                                <tr valign="bottom" align="left">
                                  <td colspan="2">
                                    <!--Random Stats Story -->
                                    <table border="0" cellspacing="0" cellpadding="5">
<tbody><tr align="left" valign="top"> 
<td> 
<font size="4" face="Times New Roman, Times, serif">
<b>
<span class="papertimesbig">Mosquit Helps Capture an enemy facility</span></b>
</font>
<br>
<font size="1" face="Arial, Helvetica, sans-serif">
<span class="paperdefault">Mosquit was recently recognized by British military officials by outstanding performance. Mosquit was <i>Missing in Action</i> after completion of the mission.<br><br>

During a harrowing 10 minute mission, Mosquit showed courage and determination in securing the facility. Men like Mosquit are an example to all of us and we salute his bravery. There are reports of a parade near an enemy facility in Mosquit's name.</span><!--
--></font>
</td>
</tr>
</tbody></table> 
                                  </td>
                                </tr>
                              </tbody></table>
                            </td>
                            <td bgcolor="#000000" width="1"><img height="1"></td>
                            <td width="120">
                              <table width="121" border="0" cellspacing="0" cellpadding="5">
<tbody><tr align="left" valign="top"> 
<td> 
<font size="4" face="Times New Roman, Times, serif">
<b>
<span class="papertimeshuge">German Lines Tighten</span>
</b>
</font>
<br>
<font size="1" face="Arial, Helvetica, sans-serif">
<span class="paperdefault">German armor and infantry forces dig in along a line of 448 miles as Allied air and ground forces  threaten their positions. Civilians of the 52 frontline cities and towns held by German forces were said to be taking cover in basements and bomb shelters as the two armies brace for the fight ahead.</span>
</font>
</td>
</tr>
</tbody></table>
                              <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                <tbody><tr align="left" valign="top">
                                  <td><a href="#" class="paperdefault">[more in Axis Section]</a></td>
                                </tr>
                              </tbody></table>
                              <br>
                            </td>
                          </tr>
                        </tbody></table>
                    </td>
                  </tr>
                </tbody></table>
                <br>
                <table width="100%" border="0" cellspacing="2" cellpadding="0">
                  <tbody><tr>
                    <td bgcolor="#000000" height="2"><img height="1"></td>
                  </tr>
                  <tr>
                    <td align="center" valign="middle" bgcolor="#000000" class="paperhidden"><div align="center"><img height="7"> WILLYTEE RAWKS | SNAKES ON A PLANE | BLANGETT IS SEXEH | CLINTNOMOD</div></td>
                  </tr>
                </tbody></table>
              </td>
            </tr>
          </tbody></table>
</div>
@endsection