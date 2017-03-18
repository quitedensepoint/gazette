<?php
require '..' . DIRECTORY_SEPARATOR .'DBconn.php';
/* For total campaign summary */
$gtkills =  mysqli_query($dbconn, "SELECT killscallsign, kills FROM scoring_top_players WHERE side ='2' AND period = 'camp' ORDER BY kills DESC LIMIT 1") or die ($dbconn->error.kills_LINE_);
$gtcaps =  mysqli_query($dbconn, "SELECT capscallsign, caps FROM scoring_top_players WHERE side ='2' AND period = 'camp' ORDER BY caps DESC LIMIT 1") or die ($dbconn->error.cap_LINE_);
$gtkd =  mysqli_query($dbconn, "SELECT kdcallsign, kd FROM scoring_top_players WHERE side ='2' AND period = 'camp' ORDER BY kd DESC LIMIT 1") or die ($dbconn->error.kd_LINE_);
$gttom =  mysqli_query($dbconn, "SELECT tomcallsign, tom FROM scoring_top_players WHERE side ='2' AND period = 'camp' ORDER BY tom DESC LIMIT 1") or die ($dbconn->error.tom_LINE_);
$gtkstreak =  mysqli_query($dbconn, "SELECT sortmkillcallsign, sortmkill FROM scoring_top_players WHERE side ='2' AND period = 'camp' ORDER BY sortmkill DESC LIMIT 1") or die ($dbconn->error.kstreak_LINE_);
$gtcapstreak =  mysqli_query($dbconn, "SELECT sortcapscallsign, capstreak FROM scoring_top_players WHERE side ='2' AND period = 'camp' ORDER BY capstreak DESC LIMIT 1") or die ($dbconn->error.tcapstreak_LINE_);
$gtrifle =  mysqli_query($dbconn, "SELECT rcallsign, rpoints FROM scoring_top_players WHERE side ='2' AND period = 'camp' ORDER BY rpoints DESC LIMIT 1") or die ($dbconn->error.trifle_LINE_);
$gtaa =  mysqli_query($dbconn, "SELECT aaacallsign, aaapoints FROM scoring_top_players WHERE side ='2' AND period = 'camp' ORDER BY aaapoints DESC LIMIT 1") or die ($dbconn->error.taaa_LINE_);
$gtatg =  mysqli_query($dbconn, "SELECT atgcallsign, atgpoints FROM scoring_top_players WHERE side ='2' AND period = 'camp' ORDER BY atgpoints DESC LIMIT 1") or die ($dbconn->error.tatg_LINE_);
$gtatr =  mysqli_query($dbconn, "SELECT atrcallsign, atrpoints FROM scoring_top_players WHERE side ='2' AND period = 'camp' ORDER BY atrpoints DESC LIMIT 1") or die ($dbconn->error.tatr_LINE_);
$gtbomb =  mysqli_query($dbconn, "SELECT bombcallsign, bombpoints FROM scoring_top_players WHERE side ='2' AND period = 'camp' ORDER BY bombpoints DESC LIMIT 1") or die ($dbconn->error.tcapstreak_LINE_);
$gtfight =  mysqli_query($dbconn, "SELECT fightcallsign, fightpoints FROM scoring_top_players WHERE side ='2' AND period = 'camp' ORDER BY fightpoints DESC LIMIT 1") or die ($dbconn->error.tcapstreak_LINE_);
$gtdd =  mysqli_query($dbconn, "SELECT ddcallsign, ddpoints FROM scoring_top_players WHERE side ='2' AND period = 'camp' ORDER BY ddpoints DESC LIMIT 1") or die ($dbconn->error.tdd_LINE_);
$gtpb =  mysqli_query($dbconn, "SELECT pbcallsign, pbpoints FROM scoring_top_players WHERE side ='2' AND period = 'camp' ORDER BY pbpoints DESC LIMIT 1") or die ($dbconn->error.tpb_LINE_);
$gtsmg =  mysqli_query($dbconn, "SELECT smgcallsign, smgpoints FROM scoring_top_players WHERE side ='2' AND period = 'camp' ORDER BY smgpoints DESC LIMIT 1") or die ($dbconn->error.smg_LINE_);
$gtlmg =  mysqli_query($dbconn, "SELECT lmgcallsign, lmgpoints FROM scoring_top_players WHERE side ='2' AND period = 'camp' ORDER BY lmgpoints DESC LIMIT 1") or die ($dbconn->error.lmg_LINE_);
$gttank =  mysqli_query($dbconn, "SELECT tankcallsign, tankpoints FROM scoring_top_players WHERE side ='2' AND period = 'camp' ORDER BY tankpoints DESC LIMIT 1") or die ($dbconn->error.tanker_LINE_);
$gtsniper =  mysqli_query($dbconn, "SELECT snipercallsign, sniperpoints FROM scoring_top_players WHERE side ='2' AND period = 'camp' ORDER BY sniperpoints DESC LIMIT 1") or die ($dbconn->error.sniper_LINE_);
$gtgren =  mysqli_query($dbconn, "SELECT grencallsign, grenpoints FROM scoring_top_players WHERE side ='2' AND period = 'camp' ORDER BY grenpoints DESC LIMIT 1") or die ($dbconn->error.gren_LINE_);
$gteng =  mysqli_query($dbconn, "SELECT engcallsign, engpoints FROM scoring_top_players WHERE side ='2' AND period = 'camp' ORDER BY engpoints DESC LIMIT 1") or die ($dbconn->error.eng_LINE_);
$gtmort =  mysqli_query($dbconn, "SELECT mortcallsign, mortpoints FROM scoring_top_players WHERE side ='2' AND period = 'camp' ORDER BY mortpoints DESC LIMIT 1") or die ($dbconn->error.mort_LINE_);
$gttruck =  mysqli_query($dbconn, "SELECT truckcallsign,truckpoints FROM scoring_top_players WHERE side ='2' AND period = 'camp' ORDER BY truckpoints DESC LIMIT 1") or die ($dbconn->error.truck_LINE_);
$gttt =  mysqli_query($dbconn, "SELECT ttcallsign, ttpoints FROM scoring_top_players WHERE side ='2' AND period = 'camp' ORDER BY ttpoints DESC LIMIT 1") or die ($dbconn->error.tt_LINE_);
$gtkrow =  mysqli_query($dbconn, "SELECT krowcallsign, krowpoints FROM scoring_top_players WHERE side ='2' AND period = 'camp' ORDER BY krowpoints DESC LIMIT 1") or die ($dbconn->error.krow_LINE_);
$gtcrow =  mysqli_query($dbconn, "SELECT crowcallsign, crowpoints FROM scoring_top_players WHERE side ='2' AND period = 'camp' ORDER BY crowpoints DESC LIMIT 1") or die ($dbconn->error.tt_LINE_);

/* For 'daily' - last 24 hour Summary */
$gdkills =  mysqli_query($dbconn, "SELECT killscallsign, kills FROM scoring_top_players WHERE side ='2' AND period = 'day' ORDER BY kills DESC LIMIT 1") or die ($dbconn->error.kills_LINE_);
$gdcaps =  mysqli_query($dbconn, "SELECT capscallsign, caps FROM scoring_top_players WHERE side ='2' AND period = 'day' ORDER BY caps DESC LIMIT 1") or die ($dbconn->error.cap_LINE_);
$gdkd =  mysqli_query($dbconn, "SELECT kdcallsign, kd FROM scoring_top_players WHERE side ='2' AND period = 'day' ORDER BY kd DESC LIMIT 1") or die ($dbconn->error.kd_LINE_);
$gdtom =  mysqli_query($dbconn, "SELECT tomcallsign, tom FROM scoring_top_players WHERE side ='2' AND period = 'day' ORDER BY tom DESC LIMIT 1") or die ($dbconn->error.tom_LINE_);
$gdkstreak =  mysqli_query($dbconn, "SELECT sortmkillcallsign, sortmkill FROM scoring_top_players WHERE side ='2' AND period = 'day' ORDER BY sortmkill DESC LIMIT 1") or die ($dbconn->error.kstreak_LINE_);
$gdcapstreak =  mysqli_query($dbconn, "SELECT sortcapscallsign, capstreak FROM scoring_top_players WHERE side ='2' AND period = 'day' ORDER BY capstreak DESC LIMIT 1") or die ($dbconn->error.tcapstreak_LINE_);
$gdrifle =  mysqli_query($dbconn, "SELECT rcallsign, rpoints FROM scoring_top_players WHERE side ='2' AND period = 'day' ORDER BY rpoints DESC LIMIT 1") or die ($dbconn->error.trifle_LINE_);
$gdaa =  mysqli_query($dbconn, "SELECT aaacallsign, aaapoints FROM scoring_top_players WHERE side ='2' AND period = 'day' ORDER BY aaapoints DESC LIMIT 1") or die ($dbconn->error.taaa_LINE_);
$gdatg =  mysqli_query($dbconn, "SELECT atgcallsign, atgpoints FROM scoring_top_players WHERE side ='2' AND period = 'day' ORDER BY atgpoints DESC LIMIT 1") or die ($dbconn->error.tatg_LINE_);
$gdatr =  mysqli_query($dbconn, "SELECT atrcallsign, atrpoints FROM scoring_top_players WHERE side ='2' AND period = 'day' ORDER BY atrpoints DESC LIMIT 1") or die ($dbconn->error.tatr_LINE_);
$gdbomb =  mysqli_query($dbconn, "SELECT bombcallsign, bombpoints FROM scoring_top_players WHERE side ='2' AND period = 'day' ORDER BY bombpoints DESC LIMIT 1") or die ($dbconn->error.tcapstreak_LINE_);
$gdfight =  mysqli_query($dbconn, "SELECT fightcallsign, fightpoints FROM scoring_top_players WHERE side ='2' AND period = 'day' ORDER BY fightpoints DESC LIMIT 1") or die ($dbconn->error.tcapstreak_LINE_);
$gddd =  mysqli_query($dbconn, "SELECT ddcallsign, ddpoints FROM scoring_top_players WHERE side ='2' AND period = 'day' ORDER BY ddpoints DESC LIMIT 1") or die ($dbconn->error.tdd_LINE_);
$gdpb =  mysqli_query($dbconn, "SELECT pbcallsign, pbpoints FROM scoring_top_players WHERE side ='2' AND period = 'day' ORDER BY pbpoints DESC LIMIT 1") or die ($dbconn->error.tpb_LINE_);
$gdsmg =  mysqli_query($dbconn, "SELECT smgcallsign, smgpoints FROM scoring_top_players WHERE side ='2' AND period = 'day' ORDER BY smgpoints DESC LIMIT 1") or die ($dbconn->error.smg_LINE_);
$gdlmg =  mysqli_query($dbconn, "SELECT lmgcallsign, lmgpoints FROM scoring_top_players WHERE side ='2' AND period = 'day' ORDER BY lmgpoints DESC LIMIT 1") or die ($dbconn->error.lmg_LINE_);
$gdtank =  mysqli_query($dbconn, "SELECT tankcallsign, tankpoints FROM scoring_top_players WHERE side ='2' AND period = 'day' ORDER BY tankpoints DESC LIMIT 1") or die ($dbconn->error.tanker_LINE_);
$gdsniper =  mysqli_query($dbconn, "SELECT snipercallsign, sniperpoints FROM scoring_top_players WHERE side ='2' AND period = 'day' ORDER BY sniperpoints DESC LIMIT 1") or die ($dbconn->error.sniper_LINE_);
$gdgren =  mysqli_query($dbconn, "SELECT grencallsign, grenpoints FROM scoring_top_players WHERE side ='2' AND period = 'day' ORDER BY grenpoints DESC LIMIT 1") or die ($dbconn->error.gren_LINE_);
$gdeng =  mysqli_query($dbconn, "SELECT engcallsign, engpoints FROM scoring_top_players WHERE side ='2' AND period = 'day' ORDER BY engpoints DESC LIMIT 1") or die ($dbconn->error.eng_LINE_);
$gdmort =  mysqli_query($dbconn, "SELECT mortcallsign, mortpoints FROM scoring_top_players WHERE side ='2' AND period = 'day' ORDER BY mortpoints DESC LIMIT 1") or die ($dbconn->error.mort_LINE_);
$gdtruck =  mysqli_query($dbconn, "SELECT truckcallsign,truckpoints FROM scoring_top_players WHERE side ='2' AND period = 'day' ORDER BY truckpoints DESC LIMIT 1") or die ($dbconn->error.truck_LINE_);
$gdtt =  mysqli_query($dbconn, "SELECT ttcallsign, ttpoints FROM scoring_top_players WHERE side ='2' AND period = 'day' ORDER BY ttpoints DESC LIMIT 1") or die ($dbconn->error.tt_LINE_);
$gdkrow =  mysqli_query($dbconn, "SELECT krowcallsign, krowpoints FROM scoring_top_players WHERE side ='2' AND period = 'day' ORDER BY krowpoints DESC LIMIT 1") or die ($dbconn->error.krow_LINE_);
$gdcrow =  mysqli_query($dbconn, "SELECT crowcallsign, crowpoints FROM scoring_top_players WHERE side ='2' AND period = 'day' ORDER BY crowpoints DESC LIMIT 1") or die ($dbconn->error.tt_LINE_);
?>
