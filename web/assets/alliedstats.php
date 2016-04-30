<?php
include '../DBconn.php';
/* For total campaign summary */
$tkills =  mysqli_query($dbconn, "SELECT killscallsign, kills FROM scoring_top_players WHERE side ='1' AND period = 'camp' ORDER BY kills DESC LIMIT 1") or die ($dbconn->error.kills_LINE_);
$tcaps =  mysqli_query($dbconn, "SELECT capscallsign, caps FROM scoring_top_players WHERE side ='1' AND period = 'camp' ORDER BY caps DESC LIMIT 1") or die ($dbconn->error.cap_LINE_);
$tkd =  mysqli_query($dbconn, "SELECT kdcallsign, kd FROM scoring_top_players WHERE side ='1' AND period = 'camp' ORDER BY kd DESC LIMIT 1") or die ($dbconn->error.kd_LINE_);
$ttom =  mysqli_query($dbconn, "SELECT tomcallsign, tom FROM scoring_top_players WHERE side ='1' AND period = 'camp' ORDER BY tom DESC LIMIT 1") or die ($dbconn->error.tom_LINE_);
$tkstreak =  mysqli_query($dbconn, "SELECT sortmkillcallsign, sortmkill FROM scoring_top_players WHERE side ='1' AND period = 'camp' ORDER BY sortmkill DESC LIMIT 1") or die ($dbconn->error.kstreak_LINE_);
$tcapstreak =  mysqli_query($dbconn, "SELECT sortcapscallsign, capstreak FROM scoring_top_players WHERE side ='1' AND period = 'camp' ORDER BY capstreak DESC LIMIT 1") or die ($dbconn->error.tcapstreak_LINE_);
$trifle =  mysqli_query($dbconn, "SELECT rcallsign, rpoints FROM scoring_top_players WHERE side ='1' AND period = 'camp' ORDER BY rpoints DESC LIMIT 1") or die ($dbconn->error.trifle_LINE_);
$taa =  mysqli_query($dbconn, "SELECT aaacallsign, aaapoints FROM scoring_top_players WHERE side ='1' AND period = 'camp' ORDER BY aaapoints DESC LIMIT 1") or die ($dbconn->error.taaa_LINE_);
$tatg =  mysqli_query($dbconn, "SELECT atgcallsign, atgpoints FROM scoring_top_players WHERE side ='1' AND period = 'camp' ORDER BY atgpoints DESC LIMIT 1") or die ($dbconn->error.tatg_LINE_);
$tatr =  mysqli_query($dbconn, "SELECT atrcallsign, atrpoints FROM scoring_top_players WHERE side ='1' AND period = 'camp' ORDER BY atrpoints DESC LIMIT 1") or die ($dbconn->error.tatr_LINE_);
$tbomb =  mysqli_query($dbconn, "SELECT bombcallsign, bombpoints FROM scoring_top_players WHERE side ='1' AND period = 'camp' ORDER BY bombpoints DESC LIMIT 1") or die ($dbconn->error.tcapstreak_LINE_);
$tfight =  mysqli_query($dbconn, "SELECT fightcallsign, fightpoints FROM scoring_top_players WHERE side ='1' AND period = 'camp' ORDER BY fightpoints DESC LIMIT 1") or die ($dbconn->error.tcapstreak_LINE_);
$tdd =  mysqli_query($dbconn, "SELECT ddcallsign, ddpoints FROM scoring_top_players WHERE side ='1' AND period = 'camp' ORDER BY ddpoints DESC LIMIT 1") or die ($dbconn->error.tdd_LINE_);
$tpb =  mysqli_query($dbconn, "SELECT pbcallsign, pbpoints FROM scoring_top_players WHERE side ='1' AND period = 'camp' ORDER BY pbpoints DESC LIMIT 1") or die ($dbconn->error.tpb_LINE_);
$tsmg =  mysqli_query($dbconn, "SELECT smgcallsign, smgpoints FROM scoring_top_players WHERE side ='1' AND period = 'camp' ORDER BY smgpoints DESC LIMIT 1") or die ($dbconn->error.smg_LINE_);
$tlmg =  mysqli_query($dbconn, "SELECT lmgcallsign, lmgpoints FROM scoring_top_players WHERE side ='1' AND period = 'camp' ORDER BY lmgpoints DESC LIMIT 1") or die ($dbconn->error.lmg_LINE_);
$ttank =  mysqli_query($dbconn, "SELECT tankcallsign, tankpoints FROM scoring_top_players WHERE side ='1' AND period = 'camp' ORDER BY tankpoints DESC LIMIT 1") or die ($dbconn->error.tanker_LINE_);
$tsniper =  mysqli_query($dbconn, "SELECT snipercallsign, sniperpoints FROM scoring_top_players WHERE side ='1' AND period = 'camp' ORDER BY sniperpoints DESC LIMIT 1") or die ($dbconn->error.sniper_LINE_);
$tgren =  mysqli_query($dbconn, "SELECT grencallsign, grenpoints FROM scoring_top_players WHERE side ='1' AND period = 'camp' ORDER BY grenpoints DESC LIMIT 1") or die ($dbconn->error.gren_LINE_);
$teng =  mysqli_query($dbconn, "SELECT engcallsign, engpoints FROM scoring_top_players WHERE side ='1' AND period = 'camp' ORDER BY engpoints DESC LIMIT 1") or die ($dbconn->error.eng_LINE_);
$tmort =  mysqli_query($dbconn, "SELECT mortcallsign, mortpoints FROM scoring_top_players WHERE side ='1' AND period = 'camp' ORDER BY mortpoints DESC LIMIT 1") or die ($dbconn->error.mort_LINE_);
$ttruck =  mysqli_query($dbconn, "SELECT truckcallsign,truckpoints FROM scoring_top_players WHERE side ='1' AND period = 'camp' ORDER BY truckpoints DESC LIMIT 1") or die ($dbconn->error.truck_LINE_);
$ttt =  mysqli_query($dbconn, "SELECT ttcallsign, ttpoints FROM scoring_top_players WHERE side ='1' AND period = 'camp' ORDER BY ttpoints DESC LIMIT 1") or die ($dbconn->error.tt_LINE_);
$tkrow =  mysqli_query($dbconn, "SELECT krowcallsign, krowpoints FROM scoring_top_players WHERE side ='1' AND period = 'camp' ORDER BY krowpoints DESC LIMIT 1") or die ($dbconn->error.krow_LINE_);
$tcrow =  mysqli_query($dbconn, "SELECT crowcallsign, crowpoints FROM scoring_top_players WHERE side ='1' AND period = 'camp' ORDER BY crowpoints DESC LIMIT 1") or die ($dbconn->error.tt_LINE_);

/* For 'daily' - last 24 hour Summary */
$dkills =  mysqli_query($dbconn, "SELECT killscallsign, kills FROM scoring_top_players WHERE side ='1' AND period = 'day' ORDER BY kills DESC LIMIT 1") or die ($dbconn->error.kills_LINE_);
$dcaps =  mysqli_query($dbconn, "SELECT capscallsign, caps FROM scoring_top_players WHERE side ='1' AND period = 'day' ORDER BY caps DESC LIMIT 1") or die ($dbconn->error.cap_LINE_);
$dkd =  mysqli_query($dbconn, "SELECT kdcallsign, kd FROM scoring_top_players WHERE side ='1' AND period = 'day' ORDER BY kd DESC LIMIT 1") or die ($dbconn->error.kd_LINE_);
$dtom =  mysqli_query($dbconn, "SELECT tomcallsign, tom FROM scoring_top_players WHERE side ='1' AND period = 'day' ORDER BY tom DESC LIMIT 1") or die ($dbconn->error.tom_LINE_);
$dkstreak =  mysqli_query($dbconn, "SELECT sortmkillcallsign, sortmkill FROM scoring_top_players WHERE side ='1' AND period = 'day' ORDER BY sortmkill DESC LIMIT 1") or die ($dbconn->error.kstreak_LINE_);
$dcapstreak =  mysqli_query($dbconn, "SELECT sortcapscallsign, capstreak FROM scoring_top_players WHERE side ='1' AND period = 'day' ORDER BY capstreak DESC LIMIT 1") or die ($dbconn->error.tcapstreak_LINE_);
$drifle =  mysqli_query($dbconn, "SELECT rcallsign, rpoints FROM scoring_top_players WHERE side ='1' AND period = 'day' ORDER BY rpoints DESC LIMIT 1") or die ($dbconn->error.trifle_LINE_);
$daa =  mysqli_query($dbconn, "SELECT aaacallsign, aaapoints FROM scoring_top_players WHERE side ='1' AND period = 'day' ORDER BY aaapoints DESC LIMIT 1") or die ($dbconn->error.taaa_LINE_);
$datg =  mysqli_query($dbconn, "SELECT atgcallsign, atgpoints FROM scoring_top_players WHERE side ='1' AND period = 'day' ORDER BY atgpoints DESC LIMIT 1") or die ($dbconn->error.tatg_LINE_);
$datr =  mysqli_query($dbconn, "SELECT atrcallsign, atrpoints FROM scoring_top_players WHERE side ='1' AND period = 'day' ORDER BY atrpoints DESC LIMIT 1") or die ($dbconn->error.tatr_LINE_);
$dbomb =  mysqli_query($dbconn, "SELECT bombcallsign, bombpoints FROM scoring_top_players WHERE side ='1' AND period = 'day' ORDER BY bombpoints DESC LIMIT 1") or die ($dbconn->error.tcapstreak_LINE_);
$dfight =  mysqli_query($dbconn, "SELECT fightcallsign, fightpoints FROM scoring_top_players WHERE side ='1' AND period = 'day' ORDER BY fightpoints DESC LIMIT 1") or die ($dbconn->error.tcapstreak_LINE_);
$ddd =  mysqli_query($dbconn, "SELECT ddcallsign, ddpoints FROM scoring_top_players WHERE side ='1' AND period = 'day' ORDER BY ddpoints DESC LIMIT 1") or die ($dbconn->error.tdd_LINE_);
$dpb =  mysqli_query($dbconn, "SELECT pbcallsign, pbpoints FROM scoring_top_players WHERE side ='1' AND period = 'day' ORDER BY pbpoints DESC LIMIT 1") or die ($dbconn->error.tpb_LINE_);
$dsmg =  mysqli_query($dbconn, "SELECT smgcallsign, smgpoints FROM scoring_top_players WHERE side ='1' AND period = 'day' ORDER BY smgpoints DESC LIMIT 1") or die ($dbconn->error.smg_LINE_);
$dlmg =  mysqli_query($dbconn, "SELECT lmgcallsign, lmgpoints FROM scoring_top_players WHERE side ='1' AND period = 'day' ORDER BY lmgpoints DESC LIMIT 1") or die ($dbconn->error.lmg_LINE_);
$dtank =  mysqli_query($dbconn, "SELECT tankcallsign, tankpoints FROM scoring_top_players WHERE side ='1' AND period = 'day' ORDER BY tankpoints DESC LIMIT 1") or die ($dbconn->error.tanker_LINE_);
$dsniper =  mysqli_query($dbconn, "SELECT snipercallsign, sniperpoints FROM scoring_top_players WHERE side ='1' AND period = 'day' ORDER BY sniperpoints DESC LIMIT 1") or die ($dbconn->error.sniper_LINE_);
$dgren =  mysqli_query($dbconn, "SELECT grencallsign, grenpoints FROM scoring_top_players WHERE side ='1' AND period = 'day' ORDER BY grenpoints DESC LIMIT 1") or die ($dbconn->error.gren_LINE_);
$deng =  mysqli_query($dbconn, "SELECT engcallsign, engpoints FROM scoring_top_players WHERE side ='1' AND period = 'day' ORDER BY engpoints DESC LIMIT 1") or die ($dbconn->error.eng_LINE_);
$dmort =  mysqli_query($dbconn, "SELECT mortcallsign, mortpoints FROM scoring_top_players WHERE side ='1' AND period = 'day' ORDER BY mortpoints DESC LIMIT 1") or die ($dbconn->error.mort_LINE_);
$dtruck =  mysqli_query($dbconn, "SELECT truckcallsign,truckpoints FROM scoring_top_players WHERE side ='1' AND period = 'day' ORDER BY truckpoints DESC LIMIT 1") or die ($dbconn->error.truck_LINE_);
$dtt =  mysqli_query($dbconn, "SELECT ttcallsign, ttpoints FROM scoring_top_players WHERE side ='1' AND period = 'day' ORDER BY ttpoints DESC LIMIT 1") or die ($dbconn->error.tt_LINE_);
$dkrow =  mysqli_query($dbconn, "SELECT krowcallsign, krowpoints FROM scoring_top_players WHERE side ='1' AND period = 'day' ORDER BY krowpoints DESC LIMIT 1") or die ($dbconn->error.krow_LINE_);
$dcrow =  mysqli_query($dbconn, "SELECT crowcallsign, crowpoints FROM scoring_top_players WHERE side ='1' AND period = 'day' ORDER BY crowpoints DESC LIMIT 1") or die ($dbconn->error.tt_LINE_);
?>



