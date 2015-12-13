@extends('layouts.master-' . $frame)

@section('gazette-content')

<table width="550" border="1" cellspacing="0" cellpadding="0" background="/assets/img/paper_tile_new.jpg" align="center" bordercolor="#CCCCCC">
              <tbody><tr align="left" valign="top">
               <td height="943">
                  <table width="530" border="1" cellspacing="0" cellpadding="0" align="center" bordercolor="#000000">
                    <tbody><tr>
                      <td>
                        <table width="530" border="0" cellspacing="0" cellpadding="2" bordercolor="#000000" align="center">
                          <tbody><tr valign="top" align="center">
                            <td width="33%"> <a href="http://forums.battlegroundeurope.com/forumdisplay.php?f=10" target="_blank" class="papertimessmall">WHAT'S COMING NEXT</a> </td>
                            <td width="33%"> <a href="{{ route('allied-gazette') }}" class="papertimessmall">READ THE ALLIED SECTION</a> </td>
                            <td width="33%"> <a href="{{ route('index-gazette') }}" class="papertimessmall">READ THE FRONT PAGE</a> </td>
                          </tr>
                        </tbody></table>
                      </td>
                    </tr>
                  </tbody></table>
                  <table width="550" border="0" cellspacing="0" cellpadding="0">
                    <tbody><tr align="left" valign="top">
                      <td colspan="2"><img src="/assets/img/axis/banner_top.gif" width="550" height="43"></td>
                    </tr>
                    <tr>
					
                      <td align="left" valign="top" height="62" width="230">
                        <table width="100%" border="0" cellspacing="0" cellpadding="5">
                          <tbody><tr align="left" valign="top">
                            <td align="center" class="papertimesbig"> <b>AXIS COMMAND COMMUNICATION TO ALL TROOPS</b> </td>
                          </tr>
                        </tbody></table>
                      </td>
                      <td align="right" valign="top" width="320" height="62"><img src="/assets/img/axis/banner_bottom.gif" width="320" height="59"><br>
                      </td>
                    </tr>
                  </tbody></table>
                  <table width="550" border="0" cellspacing="0" cellpadding="0">
                    <tbody><tr>
                      <td width="230" align="left" valign="top" height="511">
                        <table width="230" border="0" cellspacing="0" cellpadding="0" name="leftside_content1">
                          <tbody><tr align="left" valign="top">
                            <td width="124">
                              <!--Axis High Command MOTD -->
                              <table border="0" cellspacing="0" cellpadding="5" width="230">
                                <tbody><tr align="left" valign="top">
                                   <td class="papertimesmedium">
										
<!-- START paper_ghc_story -->

											<center><span class="papertimesbig"><b>Allied Blinded by Greed</b></span></center><br>
											Axis Forces advance through the center of the map as Allied Forces focus on northern areas of the map.  Strong advances have been made by Axis Forces and victory is looking to be within our hands once again.  The Allied Forces made a tactical decision and that decision has played into the hands of our operational planning.  We will not react to the Allied mistake, but use it to our advantage.
										
<!-- END paper_ghc_story (CACHED) -->

									</td>
                                </tr>
                              </tbody></table>
                            </td>
                            <td width="1" bgcolor="#000000"><img width="1"></td>
                         </tr>
                        </tbody></table>
                        <table width="230" border="0" cellspacing="0" cellpadding="0" name="leftside_content2">
                          <tbody><tr align="left" valign="top" bgcolor="#000000">
                            <td colspan="2"><img height="1"></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td width="124" height="88">
                              <!--AXIS STATS STORY #1 -->
                              <!-- #include file="/home/bv55/scriptinc/paper/playnow_axis_stats1.html" -->
                              <table border="0" cellspacing="0" cellpadding="5" width="230">
<tbody><tr> 
<td>
<font size="3" face="Arial, Helvetica, sans-serif">
<b>
<span class="paperarialbig">Eagle38 Helps Capture an enemy facility</span>
</b>
</font>
<br>
<font face="Arial, Helvetica, sans-serif" size="1">
<span class="paperdefault">Eagle38 was recently recognized by German military officials by outstanding performance. Eagle38 was <i>Missing in Action</i> after completion of the mission.<br><br>

During a harrowing 12 minute mission, Eagle38 showed courage and determination in securing the facility. Men like Eagle38 are an example to all of us and we salute his bravery.  France will no doubt feel the loss of an enemy facility.</span>
</font>
</td>
</tr>
</tbody></table>
                            </td>
                            <td width="1" bgcolor="#000000" height="88"><img width="1"></td>
                          </tr>
                        </tbody></table>
                        <br>
                        <table width="230" border="0" cellspacing="0" cellpadding="0" name="leftside_content2">
                          <tbody><tr align="left" valign="top" bgcolor="#000000">
                            <td colspan="2"><img height="1"></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td width="124" height="183"><!--Axis Player Written Story -->
                                <table width="230" border="0" cellspacing="0" cellpadding="5">
                                  <tbody><tr align="left" valign="top">
                                 	<td class="paperarialsmall">
										<span class="papertimesbig"><b>WEHRMACHT RESTRUCTURED</b></span><br>
										<br>
										In preparation for upcoming gameplay changes, including unit-based spawning,
										the German High Command has restructured its forces, with the new units more
										closely resembling their historical counterparts.<br>
										<br>
										Heeresgruppe A, which previously consisted of 1. Panzerarmee and 2. Panzerarmee,
										will now consist of 4. Armee and 12. Armee.  Heeresgruppe B, which previously
										consisted only of 3. Panzerarmee, will now consist of 6. Armee and 18. Armee.<br>
										<br>
										Details of the new structure can be found on the Axis HQ website.
									</td>
                                  </tr>
                                </tbody></table>
                            </td>
                            <td width="1" bgcolor="#000000" height="183"><img width="1"></td>
                          </tr>
                        </tbody></table>
                        <br>
                        <table width="230" border="0" cellspacing="0" cellpadding="0" name="leftside_content1">
                          <tbody><tr align="left" valign="top">
                            <td width="124">
                              <table border="0" cellspacing="0" cellpadding="5" name="stats_content2" width="230">
                                <tbody><tr align="left" valign="top">
                                  <td>
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                      <tbody><tr>
                                        <td>
                                          <table width="195" border="0" cellspacing="0" cellpadding="0" align="left">
                                            <tbody><tr>
                                              <td bgcolor="#000000"><img height="1"></td>
                                            </tr>
                                            <tr>
                                              <td class="paperarialsmall"> FOCUS ON THE FRONTLINES </td>
                                            </tr>
                                            <tr>
                                              <td bgcolor="#000000"><img height="1"></td>
                                            </tr>
                                          </tbody></table>
                                        </td>
                                      </tr>
                                      <tr>
                                        <td><!--AXIS STATS STORY #2-->
                                            <!-- #include file="/home/bv55/scriptinc/paper/playnow_axis_stats2.html" -->
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
<tbody><tr align="left" valign="top"> 
<td>
<font face="Arial, Helvetica, sans-serif" size="2">
<b>
<span class="paperarialmedium">Recent Airfield Analysis Results</span>
</b>
</font>
<br>
<font face="Arial, Helvetica, sans-serif" size="1">
<span class="paperdefault">An independent analysis of military airfields controlled by the major combatants indicate that United Kingdom now controls 53% of the airfields (26 total), followed by France with 30% (15) and Germany with 16% (8).</span>
</font>
</td>
</tr>
</tbody></table>
                                        </td>
                                      </tr>
                                    </tbody></table>
                                  </td>
                                </tr>
                              </tbody></table>
                            </td>
                            <td width="1" bgcolor="#000000"><img width="1"></td>
                          </tr>
                        </tbody></table>
                        <br>
                      </td>
                      <td align="left" valign="top" height="511">
                        <table width="100%" cellspacing="0" cellpadding="0" align="center" border="0">
                          <tbody><tr>
                            <td height="42" align="center" class="paperarialhuge"> <b>RDP REPORTS AVAILABLE</b><br>
                                <table width="80%" border="0" cellspacing="0" cellpadding="0" align="center">
                                  <tbody><tr>
                                    <td bgcolor="#000000"><img height="1"></td>
                                  </tr>
                                  <tr>
                                    <td align="center" class="paperarialsmall"> VEHICLE &amp; WEAPON AVAILABILITY AND PLANNING </td>
                                  </tr>
                                  <tr>
                                    <td bgcolor="#000000"><img height="1"></td>
                                  </tr>
                                </tbody></table>
                            </td>
                          </tr>
                          <tr>
                            <td height="439">
                              <table width="320" border="0" cellspacing="0" cellpadding="0">
                                <tbody><tr>
                                  <td align="left" valign="top"><!--Axis German RDP #1 -->
                                      <!-- #include file="/home/bv55/scriptinc/paper/playnow_axis_german_rdp1.html" -->
                                      <table width="100%" border="0" cellspacing="0" cellpadding="5">
<tbody><tr align="left" valign="top"> 
<td>
<font face="Times New Roman, Times, serif" size="3">
<b>
<span class="papertimesbig">Bf-109F4 Production Cut Back</span>
</b>
</font>
<br>
<font face="Arial, Helvetica, sans-serif" size="1">
<span class="paperdefault">Due to  losses at the front to Bf-109F4 production of existing models has been cut back this cycle. A 33% decrease has been issued to all Bf-109F4 production facilities while tooling up for newer weapons takes place. "We are preparing to send improved weapons to the front to guarrentee victory" said one German official at Meckenheim when pressed for comment on the continuing  new weapons research being applied to the war effort.</span>
</font> 
</td>
</tr>
</tbody></table>
                                          
                                      <br>
                                  </td>
                                  <td width="131" align="left" valign="top" rowspan="2">                                    <br>
                                    <table width="154" border="0" cellspacing="0" cellpadding="0">
                                      <tbody><tr>
                                        <td colspan="2" align="center" class="papertimesbig"><a href="#"><img src="/assets/img/axis/playnow_axis.jpg" width="154" height="210" border="0"></a></td>
                                      </tr>
                                      <tr>
                                        <td colspan="2" align="center" bgcolor="#000000" class="papertimesbig">


<span class="paperwhite"><b>In the Field</b></span> </td>
                                      </tr>
                                      <tr>
                                        <td width="65" align="left" valign="top">
                                          <!--Axis German Spawn List -->
                                          <!-- #include file="/home/bv55/scriptinc/paper/playnow_axis_german_spawn.html" -->
                                          <table width="100%" border="0" cellspacing="0" cellpadding="3">
<tbody><tr> 
<td>
<font face="Arial, Helvetica, sans-serif" size="1">
<span class="paperdefault">Infantry<br>Pz IVg<br>Pz38t<br>PzIIIF<br>PzIIIH<br>PzIVD<br>SdKfz 232<br>Stug III<br>Opel<br>SdKfz 7<br>4cm Flak28<br>Flak 30<br>Flak 36<br>Pak 36<br>Pak38<br>Bf-110C4/B<br>He-111<br>Stuka<br>109E<br>109F<br>Bf110c<br>Fw 190A4<br>Fairmile<br>DE Freight<br>Z34<br></span>
</font>
</td>
</tr>
</tbody></table>         
                                        </td>
                                        <td bgcolor="#000000" width="1"><img width="1"></td>
                                      </tr>
                                    </tbody></table>
                                  </td>
                                </tr>
                                <tr>
                                  <td align="left" valign="top"><!--Axis German RDP #2 -->
                                      <!-- #include file="/home/bv55/scriptinc/paper/playnow_axis_german_rdp2.html" -->
                                      <table border="0" cellspacing="0" cellpadding="5">
<tbody><tr align="left" valign="top"> 
<td>
<font face="Arial, Helvetica, sans-serif" size="4">
<b>
<span class="paperarialhuge">Pz38t Production Slows Down!</span>
</b>
</font>
<br>
      <font size="1"> <font face="Arial, Helvetica, sans-serif">
<span class="paperdefault">Over the past few weeks, German factories have slowed production of Pz38t forces due to increased demands on industrial sectors of the country for other  deadlier weapon types. The rather huge decrease isn't expected to be felt by combat units at the frontlines until the end of this production cycle.</span>
</font></font> 
    </td>
</tr>
</tbody></table>
                                      <br>
                                  </td>
                                </tr>
                              </tbody></table>
                              <br>
                              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tbody><tr>
                                  <td bgcolor="#000000"><img height="1"></td>
                                </tr>
                                <tr align="left" valign="top">
                                  <td>
                                    <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                      <tbody><tr>
                                        <td class="papertimeshuge"> <b>RDP IN BRIEF</b> </td>
                                      </tr>
                                    </tbody></table>
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                      <tbody><tr align="left" valign="top">
                                        <td>
                                          <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                            <tbody><tr>
                                              <td><!--Axis RDP Archive -->
                                                  <!-- #include file="/home/bv55/scriptinc/paper/playnow_axis_rdp_archive.html" -->
                                                  <table width="100%" border="0" cellspacing="0">
<tbody><tr> 
  <td valign="top">
  <font face="Arial, Helvetica, sans-serif" size="1">
 <span class="paperarialsmall">Bf110c Production Cut Back</span>
  </font>
  </td>
</tr><tr> 
  <td valign="top">
  <font face="Arial, Helvetica, sans-serif" size="1">
 <span class="paperarialsmall">109F Production Limited</span>
  </font>
  </td>
</tr><tr> 
  <td valign="top">
  <font face="Arial, Helvetica, sans-serif" size="1">
 <span class="paperarialsmall">Pz 38(t) Tank Production Cut Back</span>
  </font>
  </td>
</tr><tr> 
  <td valign="top">
  <font face="Arial, Helvetica, sans-serif" size="1">
 <span class="paperarialsmall">Flak 30 20mm AA Gun productions quotas not met!</span>
  </font>
  </td>
</tr><tr> 
  <td valign="top">
  <font face="Arial, Helvetica, sans-serif" size="1">
 <span class="paperarialsmall">109E Production Limited</span>
  </font>
  </td>
</tr><tr> 
  <td valign="top">
  <font face="Arial, Helvetica, sans-serif" size="1">
 <span class="paperarialsmall">Pak38 Production Limited</span>
  </font>
  </td>
</tr><tr> 
  <td valign="top">
  <font face="Arial, Helvetica, sans-serif" size="1">
 <span class="paperarialsmall">PzKpfw III F Tank productions quotas not met!</span>
  </font>
  </td>
</tr>
</tbody></table>
                                              </td>
                                            </tr>
                                          </tbody></table>
                                        </td>
                                      </tr>
                                      <tr>
                                        <td align="center" valign="middle">
                                          <!--#include file="/home/bv55/scriptinc/paper/playnow_axis_archive_filler.html" -->
                                          
<script>
<!--

var banner_index	= Math.round(Math.random() * (4));

var banner_imgs		= new Array(4);

banner_imgs[0]		= '/assets/img/factories/rfactorys_1.jpg';
banner_imgs[1]		= '/assets/img/factories/rfactorys_2.jpg';
banner_imgs[2]		= '/assets/img/factories/rfactorys_3.jpg';
banner_imgs[3]		= '/assets/img/factories/rfactorys_4.jpg';
banner_imgs[4]		= '/assets/img/factories/rfactorys_5.jpg';

document.write('<img src="' + banner_imgs[banner_index] + '" width="300" height="100" border="0">');

//-->
</script><img src="/assets/img/factories/rfactorys_1.jpg" width="300" height="100" border="0">

                                        </td>
                                      </tr>
                                    </tbody></table>
                                  </td>
                              </tr></tbody></table>
                            </td>
                          </tr>
                        </tbody></table>
                      </td>
                    </tr>
                  </tbody></table>
                  <br>
                  <table width="550" border="0" cellspacing="0" cellpadding="0">
                    <tbody><tr>
                      <td width="400" align="left" valign="top">
                        <table width="400" border="0" cellspacing="0" cellpadding="0" name="leftside_content1">
                          <tbody><tr align="left" valign="top" bgcolor="#000000">
                            <td colspan="2"><img height="1"></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td>
                              <!--AXIS FACTORY STORY-->
                              <!-- #include file="/home/bv55/scriptinc/paper/playnow_axis_factory.html" -->
                              <table width="100%" border="0" cellspacing="0" cellpadding="5">
<tbody><tr> 
<td>
<font face="Times New Roman, Times, serif" size="4">
<b>
<span class="papertimeshuge">British Air Power Pounds Axis Factories</span>
</b>
</font>
<br>
<font size="1" face="Arial, Helvetica, sans-serif"> 
<span class="paperdefault">French bomber units have been hitting Axis factories day and night which has resulted in the shutdown of all production at Monchen Gladbach. The latest reports show that 100% of the complex has been destroyed. Workers brave the bombing 24 hours a day in an attempt to repair the damage.</span>
</font>                                      
</td>
</tr>
</tbody></table>
                              <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                <tbody><tr align="left" valign="top">
                                  <td height="13" class="paperdefault"> For more details read '<a href="#" target="_blank" class="paperdefault"><u>Research, Development and Production</u></a>'. </td>
                                </tr>
                              </tbody></table>
                              <!-- factory health -->
                              <!-- #include file="/home/bv55/scriptinc/paper/playnow_axis_factory_health.html" -->
                              <table width="350" border="0" cellspacing="0" cellpadding="0">
<tbody><tr> 
  <td>
  	<font face="Times New Roman, Times, serif" size="2"><em><span class="papertimesmedium">Country</span></em></font>
  </td>
  <td>
  	<font face="Times New Roman, Times, serif" size="2"><em><span class="papertimesmedium">Factory</span></em></font>
  </td>
  <td>
  	<font face="Times New Roman, Times, serif" size="2"><em><span class="papertimesmedium">Damage</span></em></font>
  </td>
  <td>
  	<font face="Times New Roman, Times, serif" size="2"><em><span class="papertimesmedium">Output</span></em></font>
  </td>
  <td>
  	<font face="Times New Roman, Times, serif" size="2"><em><span class="papertimesmedium">Status</span></em></font>
  </td>
</tr>
<tr> 
  <td>
  	<font face="Times New Roman, Times, serif" size="2"><span class="papertimesmedium">Germany</span></font>
  </td>
  <td>
  	<font face="Times New Roman, Times, serif" size="2"><span class="papertimesmedium">D�sseldorf</span></font>
  </td>
  <td>
  	<font face="Times New Roman, Times, serif" size="2"><span class="papertimesmedium">1%</span></font>
  </td>
  <td>
  	<font face="Times New Roman, Times, serif" size="2"><span class="papertimesmedium">98%</span></font>
  </td>
  <td>
  	<font face="Times New Roman, Times, serif" size="2"><span class="papertimesmedium">Reduced Production</span></font>
  </td>
</tr>
<tr> 
  <td>
  	<font face="Times New Roman, Times, serif" size="2"><span class="papertimesmedium">United Kingdom</span></font>
  </td>
  <td>
  	<font face="Times New Roman, Times, serif" size="2"><span class="papertimesmedium">Monchen Gladbach</span></font>
  </td>
  <td>
  	<font face="Times New Roman, Times, serif" size="2"><span class="papertimesmedium">100%</span></font>
  </td>
  <td>
  	<font face="Times New Roman, Times, serif" size="2"><span class="papertimesmedium">0%</span></font>
  </td>
  <td>
  	<font face="Times New Roman, Times, serif" size="2"><span class="papertimesmedium">Captured</span></font>
  </td>
</tr>
<tr> 
  <td>
  	<font face="Times New Roman, Times, serif" size="2"><span class="papertimesmedium">United Kingdom</span></font>
  </td>
  <td>
  	<font face="Times New Roman, Times, serif" size="2"><span class="papertimesmedium">K�ln</span></font>
  </td>
  <td>
  	<font face="Times New Roman, Times, serif" size="2"><span class="papertimesmedium">100%</span></font>
  </td>
  <td>
  	<font face="Times New Roman, Times, serif" size="2"><span class="papertimesmedium">0%</span></font>
  </td>
  <td>
  	<font face="Times New Roman, Times, serif" size="2"><span class="papertimesmedium">Captured</span></font>
  </td>
</tr>

</tbody></table>
                              <!-- rdp status -->
                              <table width="100%" border="0" cellspacing="0" cellpadding="4">
                                <tbody><tr>
                                  <td>
                                    <!-- #include file="/home/bv55/scriptinc/paper/playnow_german_rdp_status.html" -->
                                    <table width="99%" border="0" cellspacing="0" cellpadding="2">
<tbody><tr>
   <td>
<font face="Arial, Helvetica, sans-serif" size="1">
<b><span class="paperdefault">German RDP Report</span></b></font>
</td>
</tr>
<tr> 
	<td> 
		<table border="1" cellspacing="0" cellpadding="1" bordercolor="#000000">
		<tbody><tr> 
			<td><font face="Arial, Helvetica, sans-serif" size="1"><span class="paperdefault">Factory Output: <b>43%</b> of capacity</span></font></td>
		</tr>
		<tr> 
			<td><font face="Arial, Helvetica, sans-serif" size="1"><span class="paperdefault">Current RDP Cycle: <b>39%</b> complete</span></font></td>
		</tr>
		<tr> 
			<td><font face="Arial, Helvetica, sans-serif" size="1"><span class="paperdefault">Estimated Completion: <b>5d9h</b></span></font></td>
		</tr>
		</tbody></table>
	</td>
</tr>
</tbody></table>

                                  </td>
                                </tr>
                              </tbody></table>
                            </td>
                            <td width="1" bgcolor="#000000"><img width="1"></td>
                          </tr>
                        </tbody></table>
                      </td>
                      <td width="150" align="left" valign="top">
                        <table width="100%" border="0" cellspacing="0" cellpadding="5">
                          <tbody><tr align="left" valign="top">
                            <td class="paperarialbig"> <b>Allied Production Reports Make Way To GHC</b> </td>
                          </tr>
                        </tbody></table>
                        <!--Allied RDP #1 -->
                        <!-- #include file="/home/bv55/scriptinc/paper/axis_playnow_allied_rdp1.html" -->
                        <table border="0" cellspacing="0" cellpadding="5">
<tbody><tr align="left" valign="top"> 
<td>
<font face="Arial, Helvetica, sans-serif" size="1">
<span class="paperdefault">According to the British High Command, production of the Spitfire IXc is being increased 33% this cycle. Canterbury's factory facilities have 
improved production techniques which is creating this increased production capacity. More Spitfire IXc units should be arriving at the front by the end of this production cycle as a result of the increase in line production outputs.</span>
</font>
</td>
</tr>
</tbody></table>
                      </td>
                    </tr>
                  </tbody></table>
                  <br>
                  <table width="100%" border="0" cellspacing="2" cellpadding="0">
                    <tbody><tr>
                      <td bgcolor="#000000"><img height="1"></td>
                    </tr>
                    <tr bgcolor="#000000">
                      <td class="paperhidden"><img height="7"> WILLYTEE RAWKS | SNAKES ON A PLANE | BLANGETT IS SEXEH | CLINTNOMO</td>
                    </tr>
                  </tbody></table>
                </td>
              </tr>
            </tbody></table>
