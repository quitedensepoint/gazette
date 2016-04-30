<?php
/*
For collecting stats and updating the Top_players tables for both CSR and Gazette top player lists DB's as follows:
scoring_campaign_sorties = s
scoring_campaign_personas = p 
scoring_persona_configs = c
wwii_player = w

*/


include '/../DBconn.php';

/* Truncate table */
mysqli_query($dbconn, "TRUNCATE scoring_top_players");

/* Queries */
/* ALLIED STATS REDUCDED TO 1 LINE ARE ALL THOSE THAT ARE COMPLETELY DONE */
/* Top AAA by points -- point formula = KILLS * KD    --- FOR ALLIED SIDE -- Need Sortie counts*/
$taa = mysqli_query($dbConnCommunity, "SELECT w.callsign,
                                        IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                  FROM scoring_campaign_sorties s
                                        JOIN wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c 
                                            WHERE s.vehicle_id IN(85,109,201,78,79,259)
                                            AND s.persona_id=c.persona_id
                                            AND (isnull(c.bans) OR c.bans = 0) 
                                  GROUP BY w.callsign ORDER BY points DESC limit 100") or die ($dbConnCommunity->error.taaaquery_LINE_);
/* TOP AAA */
while ($row = $taa->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, aaacallsign, aaapoints) VALUES ('1','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.taa_LINE_);  } 

/* Daily Top AAA by points -- point formula = KILLS * KD    --- FOR ALLIED SIDE -- Need Sortie counts*/
$dtaa = mysqli_query($dbConnCommunity, "SELECT w.callsign,
                                        SUM(s.kills) as kills,
                                        IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                   FROM `scoring_campaign_sorties` s 
                                        JOIN wwii_player w ON s.player_id=w.playerid 
                                            WHERE s.vehicle_id IN(85,109,201,78,79,259)
                                            AND s.`sortie_start` > DATE_SUB(NOW(), INTERVAL 24 HOUR) 
                                   GROUP BY w.callsign ORDER BY points DESC limit 100") or die ($dbConnCommunity->error.taaaquery_LINE_);
/*Daily Top AAA */
while ($row = $dtaa->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, aaacallsign, aaapoints) VALUES ('1','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.taa_LINE_);  } 

/* Top ATG by points -- point formula = KILLS * KD    --- FOR ALLIED SIDE -- Need Sortie counts*/
$tatg = mysqli_query($dbConnCommunity, "SELECT w.callsign, 
                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c  
                                        WHERE s.vehicle_id IN(156,124,207,202,69,17,18,154,7,123)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.tatg_LINE_);
/* TOP ATG */
while ($row = $tatg->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, atgcallsign, atgpoints) VALUES ('1','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.tatg_LINE_); } 

/* Daily Top ATG by points -- point formula = KILLS * KD    --- FOR ALLIED SIDE -- Need Sortie counts*/
$dtatg = mysqli_query($dbConnCommunity, "SELECT w.callsign, 
                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid 
                                        WHERE s.vehicle_id IN(156,124,207,202,69,17,18,154,7,123)
                                        AND s.`sortie_start` > DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.datg_LINE_);
/* DAILY TOP ATG */
while ($row = $dtatg->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, atgcallsign, atgpoints) VALUES ('1','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.datg_LINE_);} 

/* Top ATR by points -- point formula = (KILLS + CAPS )) * KD    --- FOR ALLIED SIDE*/
$tatr = mysqli_query($dbConnCommunity, "SELECT w.callsign, 
                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+SUM(s.captures)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c  
                                        WHERE s.vehicle_id IN(120,121,166,172,260)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.atr_LINE_);
/* TOP ATR */
while ($row = $tatr->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, atrcallsign, atrpoints) VALUES ('1','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.atrinsert_LINE_); }

/* Daily Top ATR by points -- point formula = (KILLS + CAPS )) * KD    --- FOR ALLIED SIDE*/
$dtatr = mysqli_query($dbConnCommunity, "SELECT w.callsign, 
                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+SUM(s.captures)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid  
                                        WHERE s.vehicle_id IN(120,121,166,172,260)
                                        AND s.`sortie_start` > DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.datr_LINE_);
/* DAILY TOP ATR */
while ($row = $dtatr->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, atrcallsign, atrpoints) VALUES ('1','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.atrinsert_LINE_); }

/* Top Engineer by points -- point formula = (KILLS + (CAPS * 5)) * KD    --- FOR ALLIED SIDE*/
$teng = mysqli_query($dbConnCommunity, "SELECT w.callsign, 
                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+(SUM(s.captures)*5)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c  
                                        WHERE s.vehicle_id IN(10,21,205,265)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.engquery_LINE_);
/* TOP Engineer */
while ($row = $teng->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, engcallsign, engpoints) VALUES ('1','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.enginsert_LINE_);  } 

/* Daily Top Engineer by points -- point formula = (KILLS + (CAPS * 5)) * KD    --- FOR ALLIED SIDE*/
$dteng = mysqli_query($dbConnCommunity, "SELECT w.callsign, 
                                              IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+(SUM(s.captures)*5)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid  
                                        WHERE s.vehicle_id IN(10,21,205,265)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.dengquery_LINE_);
/* DAILY TOP Engineer */
while ($row = $dteng->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, engcallsign, engpoints) VALUES ('1','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.enginsert_LINE_);  } 

/* Top Grenadier by points -- point formula = (KILLS + CAPS )) * KD    --- FOR ALLIED SIDE*/
$tgren = mysqli_query($dbConnCommunity, "SELECT w.callsign, 

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+SUM(s.captures)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c  
                                        WHERE s.vehicle_id IN(112,113)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.grenadierquery_LINE_);
/* TOP GRENADIER */
while ($row = $tgren->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,grencallsign, grenpoints) VALUES ('1','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.grenadierinsert_LINE_); } 

/* Daily Top Grenadier by points -- point formula = (KILLS + CAPS )) * KD    --- FOR ALLIED SIDE*/
$dtgren = mysqli_query($dbConnCommunity, "SELECT w.callsign, 

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+SUM(s.captures)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid  
                                        WHERE s.vehicle_id IN(112,113)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.grenadierquery_LINE_);
/* DAILY TOP GRENADIER */
while ($row = $dtgren->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,grencallsign, grenpoints) VALUES ('1','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.grenadierinsert_LINE_); } 

/* Top LMG by points -- point formula = (KILLS + (CAPS * 2)) * KD    --- FOR ALLIED SIDE*/
$tlmg = mysqli_query($dbConnCommunity, "SELECT w.callsign, 

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+(SUM(s.captures)*2)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c  
                                        WHERE s.vehicle_id IN(105,104,165,171)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.lmgquery_LINE_);
/* TOP LMG */
while ($row = $tlmg->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,lmgcallsign, lmgpoints) VALUES ('1','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.tlmginsert_LINE_); }

/* Daily  Top LMG by points -- point formula = (KILLS + (CAPS * 2)) * KD    --- FOR ALLIED SIDE*/
$dtlmg = mysqli_query($dbConnCommunity, "SELECT w.callsign, 

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+(SUM(s.captures)*2)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid  
                                        WHERE s.vehicle_id IN(105,104,165,171)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.lmgquery_LINE_);
/* DAILY TOP LMG */ 
while ($row = $dtlmg->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,lmgcallsign, lmgpoints) VALUES ('1','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.tlmginsert_LINE_); }

/* Top Mortar by points -- point formula = (KILLS + (CAPS * 2)) * KD    --- FOR ALLIED SIDE*/
$tmort = mysqli_query($dbConnCommunity, "SELECT w.callsign, 
                                        IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+(SUM(s.captures)*2)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c  
                                        WHERE s.vehicle_id IN(161,162,174,168,261)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.mortquery_LINE_);
/* TOP MORTAR */
while ($row = $tmort->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,mortcallsign, mortpoints) VALUES ('1','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.tmortinsert_LINE_); }

/* Daily Top Mortar by points -- point formula = (KILLS + (CAPS * 2)) * KD    --- FOR ALLIED SIDE*/
$dtmort = mysqli_query($dbConnCommunity, "SELECT w.callsign, 

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+(SUM(s.captures)*2)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid  
                                        WHERE s.vehicle_id IN(161,162,174,168,261)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.mortquery_LINE_);
/* DAILY TOP MORTAR */ 
while ($row = $dtmort->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,mortcallsign, mortpoints) VALUES ('1','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.tmortinsert_LINE_); }

/* Top Rifleman by points -- point formula = (KILLS + (CAPS * 5)) * KD    --- FOR ALLIED SIDE*/
$trifle = mysqli_query($dbConnCommunity, "SELECT w.callsign, 

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+(SUM(s.captures)*5)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c  
                                        WHERE s.vehicle_id IN(8,19,188,169,163)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.testriflequery_LINE_);
/* TOP RIFLE */
while ($row = $trifle->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,rcallsign, rpoints) VALUES ('1','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.trifleinsert_LINE_); }

/* Daily Top Rifleman by points -- point formula = (KILLS + (CAPS * 5)) * KD    --- FOR ALLIED SIDE*/
$dtrifle = mysqli_query($dbConnCommunity, "SELECT w.callsign, 

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+(SUM(s.captures)*5)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid  
                                        WHERE s.vehicle_id IN(8,19,188,169,163)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.testriflequery_LINE_);
/* DAILY TOP RIFLE */
while ($row = $dtrifle->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,rcallsign, rpoints) VALUES ('1','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.trifleinsert_LINE_); }

/* Top SMG by points -- point formula = (KILLS + (CAPS * 5)) * KD    --- FOR ALLIED SIDE*/
$tsmg = mysqli_query($dbConnCommunity, "SELECT w.callsign, 

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+(SUM(s.captures)*5)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c  
                                        WHERE s.vehicle_id IN(269,170,164,20,9,272,268)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.smgquery_LINE_);
/* TOP SMG */
while ($row = $tsmg->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,smgcallsign, smgpoints) VALUES ('1','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.smginsert_LINE_); } 
    /* Daliy Top SMG by points -- point formula = (KILLS + (CAPS * 5)) * KD    --- FOR ALLIED SIDE*/
$dtsmg = mysqli_query($dbConnCommunity, "SELECT w.callsign, 

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+(SUM(s.captures)*5)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid  
                                        WHERE s.vehicle_id IN(269,170,164,20,9,272,268)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.smgquery_LINE_);
/* DAILY TOP SMG */
while ($row = $dtsmg->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,smgcallsign, smgpoints) VALUES ('1','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.smginsert_LINE_); } 


/* Top Sniper by points -- point formula = (KILLS + CAPS )) * KD    --- FOR ALLIED SIDE*/
$tsniper = mysqli_query($dbConnCommunity, "SELECT w.callsign, 

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+SUM(s.captures)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c  
                                        WHERE s.vehicle_id IN(167,173,149,203,151)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.atr_LINE_);
/* TOP SNIPER */
while ($row = $tsniper->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,snipercallsign, sniperpoints) VALUES ('1','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.atrinsert_LINE_); }

/* Daily Top Sniper by points -- point formula = (KILLS + CAPS )) * KD    --- FOR ALLIED SIDE*/
$dtsniper = mysqli_query($dbConnCommunity, "SELECT w.callsign, 

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+SUM(s.captures)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid  
                                        WHERE s.vehicle_id IN(167,173,149,203,151)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.atr_LINE_);
/* DAILY TOP SNIPER */
while ($row = $dtsniper->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,snipercallsign, sniperpoints) VALUES ('1','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.atrinsert_LINE_); }

    /* Top Tanks by points -- point formula = KILLS * KD    --- FOR ALLIED SIDE*/
$ttank = mysqli_query($dbConnCommunity, "SELECT w.callsign, 

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c  
                                        WHERE s.vehicle_id IN(4,14,68,152,146,87,126,89,96,147,206,200,198,73,81,5,67,15,129,153,107,250,256,84)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.tankquery_LINE_);
/* TOP TANK */
while ($row = $ttank->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,tankcallsign, tankpoints) VALUES ('1','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.tankinsert_LINE_); } 

        /* Daily Top Tanks by points -- point formula = KILLS * KD    --- FOR ALLIED SIDE*/
$dttank = mysqli_query($dbConnCommunity, "SELECT w.callsign, 

                                        IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid  
                                        WHERE s.vehicle_id IN(4,14,68,152,146,87,126,89,96,147,206,200,198,73,81,5,67,15,129,153,107,250,256,84)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.tankquery_LINE_);
/* DAILY TOP TANK */
while ($row = $dttank->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,tankcallsign, tankpoints) VALUES ('1','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.tankinsert_LINE_); }

/* Top Truck by points -- point formula = (RTB % * Sorties)    --- FOR ALLIED SIDE*/
$ttruck = mysqli_query($dbConnCommunity, "SELECT w.callsign, 
                                        COUNT(CASE WHEN s.player_id = '*' THEN 1 ELSE 0 END) as sortie,
                                        ((SUM(CASE WHEN s.rtb = '0' THEN 1 ELSE 0 END)/COUNT(s.player_id)) * COUNT(CASE WHEN s.player_id = '*' THEN 1 ELSE 0 END))  as points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c  
                                        WHERE s.vehicle_id IN(16,6,190,77,83,258)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0) 
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.truckquery_LINE_);
/* TOP TRUCK */
while ($row = $ttruck->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, truckcallsign, truckpoints) VALUES ('1','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.ttruckinsert_LINE_); }

/* Daily Top Truck by points -- point formula = (RTB % * Sorties)    --- FOR ALLIED SIDE*/
$dttruck = mysqli_query($dbConnCommunity, "SELECT w.callsign, 
                                        COUNT(CASE WHEN s.player_id = '*' THEN 1 ELSE 0 END) as sortie,
                                        IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points  
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid  
                                        WHERE s.vehicle_id IN(16,6,190,77,83,258)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR) 
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.truckquery_LINE_);
/* DAILY TOP TRUCK */
while ($row = $dttruck->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, truckcallsign, truckpoints) VALUES ('1','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.ttruckinsert_LINE_); }

/* Top fighter by points -- point formula = KILLS * KD    --- FOR ALLIED SIDE*/
$tfight = mysqli_query($dbConnCommunity, "SELECT w.callsign, 

                                            SUM(s.kills)*ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2) AS points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c  
                                        WHERE s.vehicle_id IN(72,86,94,158,11,2,13,159,204,131,12,1,90,138)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.bomber_LINE_);
/* TOP FIGHTER */
while ($row = $tfight->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,fightcallsign, fightpoints) VALUES ('1','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.fightinsert_LINE_); } 

/* Daliy Top fighter by points -- point formula = KILLS * KD    --- FOR ALLIED SIDE*/
$dtfight = mysqli_query($dbConnCommunity, "SELECT w.callsign, 

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid 
                                        WHERE s.vehicle_id IN(72,86,94,158,11,2,13,159,204,131,12,1,90,138)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.bomber_LINE_);
/* DAILY TOP FIGHTER */
while ($row = $dtfight->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,fightcallsign, fightpoints) VALUES ('1','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.fightinsert_LINE_); } 

/* Top bomber by points -- point formula = KILLS * KD    --- FOR ALLIED SIDE*/
$tbomb = mysqli_query($dbConnCommunity, "SELECT w.callsign, 

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c  
                                        WHERE s.vehicle_id IN(65,70,95,97)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.bomber_LINE_);
/* TOP BOMBER */
while ($row = $tbomb->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,bombcallsign, bombpoints) VALUES ('1','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.bomberinsert_LINE_); } 

/* Daily Top bomber by points -- point formula = KILLS * KD    --- FOR ALLIED SIDE*/
$dtbomb = mysqli_query($dbConnCommunity, "SELECT w.callsign, 

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid  
                                        WHERE s.vehicle_id IN(65,70,95,97)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.bomber_LINE_);
/* DAILY TOP BOMBER */
while ($row = $dtbomb->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,bombcallsign, bombpoints) VALUES ('1','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.bomberinsert_LINE_); }

/* Top Destroyer by points -- point formula = KILLS * KD    --- FOR ALLIED SIDE*/
$tdd = mysqli_query($dbConnCommunity, "SELECT w.callsign, 

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c  
                                        WHERE s.vehicle_id IN(98,99)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.dd_LINE_);
/* TOP DESTROYER */
while ($row = $tdd->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,ddcallsign, ddpoints) VALUES ('1','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.ddinsert_LINE_); } 
/* Daliy Top Destroyer by points -- point formula = KILLS * KD    --- FOR ALLIED SIDE*/
$dtdd = mysqli_query($dbConnCommunity, "SELECT w.callsign, 

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points  
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid  
                                        WHERE s.vehicle_id IN(98,99)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.dd_LINE_);
/* DAILY TOP DESTROYER */
while ($row = $dtdd->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,ddcallsign, ddpoints) VALUES ('1','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.ddinsert_LINE_); } 

/* Top PatrolBoats by points -- point formula = KILLS * KD    --- FOR ALLIED SIDE*/
$tpb = mysqli_query($dbConnCommunity, "SELECT w.callsign, 

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c  
                                        WHERE s.vehicle_id IN(62,63)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.pb_LINE_);
/* TOP PATROL BOATS */
while ($row = $tpb->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,pbcallsign, pbpoints) VALUES ('1','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.pbinsert_LINE_); }

    /* Daily Top PatrolBoats by points -- point formula = KILLS * KD    --- FOR ALLIED SIDE*/
$dtpb = mysqli_query($dbConnCommunity, "SELECT w.callsign, 

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid  
                                        WHERE s.vehicle_id IN(62,63)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.pb_LINE_);
/* DAILY TOP PATROL BOATS */ 
while ($row = $dtpb->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,pbcallsign, pbpoints) VALUES ('1','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.pbinsert_LINE_); } 


/* Top Freighter by points -- point formula = (RTB % * Sorties)    --- FOR ALLIED SIDE*/
$ttt = mysqli_query($dbConnCommunity, "SELECT w.callsign, 
                                        
                                        ((SUM(CASE WHEN s.rtb = '0' THEN 1 ELSE 0 END)/COUNT(s.player_id)) * COUNT(CASE WHEN s.player_id = '*' THEN 1 ELSE 0 END))  as points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c  
                                        WHERE s.vehicle_id IN(100,101)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0) 
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.ttquery_LINE_);
/* TOP FREIGHTER (Transport (tt)) */
while ($row = $ttt->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,ttcallsign, ttpoints) VALUES ('1','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.tttinsert_LINE_); }
/* Daily Top Freighter by points -- point formula = (RTB % * Sorties)    --- FOR ALLIED SIDE*/
$dttt = mysqli_query($dbConnCommunity, "SELECT w.callsign, 
    
                                        ((SUM(CASE WHEN s.rtb = '0' THEN 1 ELSE 0 END)/COUNT(s.player_id)) * COUNT(CASE WHEN s.player_id = '*' THEN 1 ELSE 0 END))  as points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid 
                                        WHERE s.vehicle_id IN(100,101)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR) 
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.ttquery_LINE_);
/* DAILY TOP FREIGHTER  */
while ($row = $dttt->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,ttcallsign, ttpoints) VALUES ('1','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.tttinsert_LINE_); }


/* Allied Total Kills for Current Campaign */
$allykills = mysqli_query($dbConnCommunity, "SELECT callsign, 
                                            SUM(p.kills) AS kills

                                        FROM scoring_campaign_personas AS p 
                                        LEFT JOIN scoring_persona_configs c ON (p.persona_id = c.persona_id), wwii_persona, wwii_player 
                                        WHERE wwii_persona.personaid = p.persona_id 
                                            AND wwii_player.playerid = wwii_persona.playerid 
                                            AND wwii_persona.countryid !='4' 
                                            AND (ISNULL(c.bans) OR c.bans = 0) 
                                        GROUP BY callsign ORDER BY kills DESC LIMIT 100") or die ($dbConnCommunity->error.kills_LINE_);
/* TOP KILLS  */
while ($row = $allykills->fetch_assoc())
    {mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,killscallsign,kills) VALUES ('1','camp','".$row['callsign']."','".$row['kills']."')") or die ($dbconn->error.killsinsert_LINE_); }


/* Allied Kills for last 24 hour */
$dallykills = mysqli_query($dbConnCommunity, "SELECT callsign, 
                                            SUM(s.kills) AS kills
                                        FROM scoring_campaign_sorties AS s 
                                        LEFT JOIN scoring_persona_configs c ON (s.persona_id = c.persona_id), wwii_persona, wwii_player 
                                        WHERE DATE(s.`sortie_start`) >= DATE_SUB(NOW(), INTERVAL 24 HOUR) 
                                            AND wwii_persona.personaid = s.persona_id 
                                            AND wwii_player.playerid = wwii_persona.playerid 
                                            AND wwii_persona.countryid !='4' 
                                            AND (ISNULL(c.bans) OR c.bans = 0) GROUP BY callsign ORDER BY kills DESC LIMIT 100") or die ($dbConnCommunity->error.kills_LINE_);
/* DAILY TOP KILLS */
while ($row = $dallykills->fetch_assoc())
    {mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,killscallsign,kills) VALUES ('1','day','".$row['callsign']."','".$row['kills']."')") or die ($dbconn->error.killsinsert_LINE_);  }

/*  top captures info */
$allycaps = mysqli_query($dbConnCommunity, "SELECT callsign, 
                                            SUM(p.captures) AS caps
                                            
                                       FROM scoring_campaign_personas AS p 
                                       LEFT JOIN scoring_persona_configs c ON (p.persona_id = c.persona_id), wwii_persona, wwii_player 
                                       WHERE wwii_persona.personaid = p.persona_id 
                                            AND wwii_player.playerid = wwii_persona.playerid 
                                            AND wwii_persona.countryid !='4' 
                                            AND (ISNULL(c.bans) OR c.bans = 0) 
                                       GROUP BY callsign ORDER BY caps DESC LIMIT 100") or die ($dbConnCommunity->error.caps_LINE_);
/* MOST CAPTURES */
while ($row = $allycaps->fetch_assoc())
    {mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,capscallsign,caps) VALUES ('1','camp','".$row['callsign']."','".$row['caps']."')") or die ($dbconn->error.capsinsert_LINE_); }

/* Caps for last 24 hours */
$dallycaps = mysqli_query($dbConnCommunity, "SELECT callsign, SUM(s.captures) AS caps 
                                        FROM scoring_campaign_sorties AS s 
                                        LEFT JOIN scoring_persona_configs c ON (s.persona_id = c.persona_id),wwii_persona, wwii_player 
                                        WHERE DATE(s.`sortie_start`) >= DATE_SUB(NOW(), INTERVAL 24 HOUR) 
                                            AND wwii_persona.personaid = s.persona_id 
                                            AND wwii_player.playerid = wwii_persona.playerid 
                                            AND wwii_persona.countryid !='4' 
                                            AND (ISNULL(c.bans) OR c.bans = 0) 
                                        GROUP BY callsign ORDER BY caps DESC LIMIT 100") or die ($dbConnCommunity->error.dcaps_LINE_);
/* DAILY MOST CAPTURES */
while ($row = $dallycaps->fetch_assoc())
    {mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,capscallsign,caps) VALUES ('1','day','".$row['callsign']."','".$row['caps']."')") or die ($dbconn->error.capsinsert_LINE_);  }

/* Kills/Death (Kd) */
$allykd = mysqli_query($dbConnCommunity, "SELECT callsign, SUM(p.kills) as kills, SUM(p.deaths) as deaths, 
                                        SUM(p.kd) as kd
                                     FROM scoring_campaign_personas p 
                                     LEFT JOIN scoring_persona_configs c ON (p.persona_id = c.persona_id), wwii_persona, wwii_player 
                                     WHERE wwii_persona.personaid = p.persona_id 
                                            AND wwii_player.playerid = wwii_persona.playerid 
                                            AND wwii_persona.countryid !='4' 
                                            AND (isnull(c.bans) OR c.bans = 0) 
                                    GROUP BY callsign ORDER BY kd DESC LIMIT 100") or die ($dbConnCommunity->error.kdget_LINE_);
/* Top K/D */
while ($row = $allykd->fetch_assoc())
    {mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,kdcallsign,kd) VALUES ('1','camp','".$row['callsign']."','".$row['kd']."')") or die ($dbconn->error.kdinsert_LINE_); }

/* K/D For last 24 Hours */
$dallykd = mysqli_query($dbConnCommunity, "SELECT callsign, 
                                        ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2) AS kd 
                                      FROM scoring_campaign_sorties s 
                                      LEFT JOIN scoring_persona_configs c ON (s.persona_id = c.persona_id), wwii_persona, wwii_player 
                                      WHERE wwii_persona.personaid = s.persona_id 
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR) 
                                        AND wwii_player.playerid = wwii_persona.playerid 
                                        AND wwii_persona.countryid !='4' 
                                        AND (isnull(c.bans) OR c.bans = 0) 
                                      GROUP BY callsign ORDER BY kd DESC LIMIT 100") or die ($dbConnCommunity->error.kdget_LINE_);
while ($row = $dallykd->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,kdcallsign,kd) VALUES ('1','day','".$row['callsign']."','".$row['kd']."')") or die ($dbconn->error.kdinsert_LINE_); }

/* Allied MOST Time On Mission */
$allytom = mysqli_query($dbConnCommunity, "SELECT callsign, p.tom AS tom 
                                      FROM scoring_campaign_personas p 
                                      LEFT JOIN scoring_persona_configs c ON (p.persona_id = c.persona_id), wwii_persona, wwii_player 
                                      WHERE wwii_persona.personaid = p.persona_id 
                                        AND wwii_player.playerid = wwii_persona.playerid 
                                        AND wwii_persona.countryid !='4' 
                                        AND (isnull(c.bans) OR c.bans = 0) 
                                      GROUP BY callsign ORDER BY tom DESC LIMIT 100") or die ($dbConnCommunity->error.tomget_LINE_);
/* TIME ON MISSION */
while ($row = $allytom->fetch_assoc())
    {mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,tomcallsign,tom) VALUES ('1','camp','".$row['callsign']."','".$row['tom']."')") or die ($dbconn->error.tominsert_LINE_); }

/* Allied MOST Timeon Mission Last 24 Hours */                                  
$dallytom = mysqli_query($dbConnCommunity, "SELECT callsign, SUM(s.tom) AS tom 
                                       FROM scoring_campaign_sorties s 
                                       LEFT JOIN scoring_persona_configs c ON (s.persona_id = c.persona_id), wwii_persona, wwii_player 
                                       WHERE DATE(s.sortie_start) >= DATE_SUB(NOW(), INTERVAL 24 HOUR) 
                                            AND wwii_persona.personaid = s.persona_id 
                                            AND wwii_player.playerid = wwii_persona.playerid 
                                            AND wwii_persona.countryid !='4' 
                                            AND (isnull(c.bans) OR c.bans = 0) 
                                       GROUP BY callsign ORDER BY tom DESC LIMIT 100") or die ($dbConnCommunity->error.dtomget_LINE_);
while ($row = $dallytom->fetch_assoc())
    {mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,tomcallsign,tom) VALUES ('1','day','".$row['callsign']."','".$row['tom']."')") or die ($dbconn->error.dtominsert_LINE_); }


/* Top Kill Streak -- Number of consecutive Sorties with at least one kill.    --- FOR ALLIED SIDE*/
$tkrow = mysqli_query($dbConnCommunity, "SELECT callsign, streak_id, best as value2, UNIX_TIMESTAMP(achieved), v.name, 0, 0 
                                    FROM scoring_vehicles v, scoring_campaign_streak_bests s 
                                    LEFT JOIN scoring_persona_configs c ON (s.persona_id = c.persona_id), wwii_player, wwii_persona 
                                    WHERE v.vehicle_id=s.vehicle_id 
                                        AND wwii_persona.personaid = s.persona_id 
                                        AND wwii_player.playerid = wwii_persona.playerid 
                                        AND s.streak_id = 4 
                                        AND wwii_persona.countryid !='4'  
                                        AND (isnull(c.bans) OR c.bans = 0) ORDER BY value2 DESC LIMIT 100") or die ($dbConnCommunity->error.tkrowquery_LINE_);
/* Most consecutive Sorties with a kill */
while ($row = $tkrow->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,krowcallsign, krowpoints) VALUES ('1','camp','".$row['callsign']."','".$row['value2']."')") or die ($dbconn->error.tkrowinsert_LINE_);}

/* Daily Top Kill Streak -- Top players based on the Consecutive Sorties with a Kill streak.    --- FOR ALLIED SIDE*/
$dtkrow = mysqli_query($dbConnCommunity, "SELECT DISTINCT(callsign) as callsign, streak_id, current as value2 
                                    FROM scoring_campaign_streaks s, wwii_player, wwii_persona 
                                    WHERE s.streak_id = 4
                                        AND s.achieved >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                        AND wwii_persona.personaid = s.persona_id
                                        AND wwii_player.playerid = wwii_persona.playerid 
                                        ORDER BY value2 DESC LIMIT 100") or die ($dbConnCommunity->error.tkrowquery_LINE_);
/* DAILY CONSECUTIVE SORTIES WITH A KILL */
while ($row = $dtkrow->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,krowcallsign, krowpoints) VALUES ('1','day','".$row['callsign']."','".$row['value2']."')") or die ($dbconn->error.tkrowinsert_LINE_); }

/* Top Capture Streak -- Most sorties in a row with a capture */
$tcrow = mysqli_query($dbConnCommunity, "SELECT callsign, streak_id, best as value2, UNIX_TIMESTAMP(achieved), v.name, 0, 0 
                                    FROM scoring_vehicles v, scoring_campaign_streak_bests s 
                                    LEFT JOIN scoring_persona_configs c ON (s.persona_id = c.persona_id), wwii_player, wwii_persona 
                                    WHERE v.vehicle_id=s.vehicle_id 
                                        AND wwii_persona.personaid = s.persona_id 
                                        AND wwii_player.playerid = wwii_persona.playerid 
                                        AND s.streak_id = 5 
                                        AND wwii_persona.countryid !='4' 
                                        AND (isnull(c.bans) OR c.bans = 0) ORDER BY value2 DESC LIMIT 100") or die ($dbConnCommunity->error.tkrowquery_LINE_);
/* SORTIES IN A ROW WITH A CAPTURE */
while ($row = $tcrow->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,crowcallsign, crowpoints) VALUES ('1','camp','".$row['callsign']."','".$row['value2']."')") or die ($dbconn->error.tkrowinsert_LINE_); }

/* Daily Top Capture Streak -- Most sorties in a row with a capture */
$dtcrow = mysqli_query($dbConnCommunity, "SELECT DISTINCT(callsign) as callsign, streak_id, current as value2 
                                    FROM scoring_campaign_streaks s, wwii_player, wwii_persona 
                                    WHERE s.streak_id = 5
                                        AND s.achieved >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                        AND wwii_persona.personaid = s.persona_id
                                        AND wwii_player.playerid = wwii_persona.playerid 
                                        ORDER BY value2 DESC LIMIT 100") or die ($dbConnCommunity->error.tkrowquery_LINE_);
/* DAILY - MOST SORTIES IN A ROW WITH A CAPTURE */
while ($row = $dtcrow->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,crowcallsign, crowpoints) VALUES ('1','day','".$row['callsign']."','".$row['value2']."')") or die ($dbconn->error.tkrowinsert_LINE_); }

/* Top kills in a sortie (top kills streak) Top players based on the most Kills in a Sortie */
$allysks = mysqli_query($dbConnCommunity, "SELECT callsign,  
                                            s.kills as kills 
                                      FROM scoring_campaign_sorties s 
                                      LEFT JOIN wwii_player w ON (s.player_id=w.playerid), scoring_persona_configs c 
                                      WHERE s.persona_id=c.persona_id 
                                        AND s.country_id!='4' 
                                        AND (isnull(c.bans) OR c.bans=0) 
                                      ORDER BY kills DESC LIMIT 100 ") or die ($dbConnCommunity->error.kstreaksget_LINE_);
/* Most kills on a SINGLE SORTIE */
while ($row = $allysks->fetch_assoc())
    {mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, sortmkillcallsign, sortmkill) VALUES ('1','camp','".$row['callsign']."','".$row['kills']."')") or die ($dbconn->error.kstreaksinsert_LINE_);}

/* Daily Top kills in a sortie (top kills streak) Top players based on the most Kills in a Sortie */
$dallysks = mysqli_query($dbConnCommunity, "SELECT callsign, 
                                            s.kills as kills 
                                       FROM scoring_campaign_sorties s 
                                       LEFT JOIN wwii_player w ON (s.player_id=w.playerid), scoring_persona_configs c
                                       WHERE s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                            AND s.persona_id=c.persona_id
                                            AND s.country_id!='4'  
                                            AND (isnull(c.bans) OR c.bans=0)
                                       ORDER BY kills DESC LIMIT 100") or die ($dbConnCommunity->error.kstreaksget_LINE_);
/* Most kills on a SINGLE SORTIE - last 24 hours */
while ($row = $dallysks->fetch_assoc())
    {
       mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, sortmkillcallsign, sortmkill) VALUES ('1','day','".$row['callsign']."','".$row['kills']."')") or die ($dbconn->error.kstreaksinsert_LINE_); }

/* Allied Top Captures -- MOST CAPTURES IN 1 SORTIE */
$sortallycaps = mysqli_query($dbConnCommunity, "SELECT callsign, 
                                            s.captures as caps 
                                      FROM scoring_campaign_sorties s 
                                      LEFT JOIN wwii_player w ON (s.player_id=w.playerid), scoring_persona_configs c 
                                      WHERE s.persona_id=c.persona_id 
                                        AND (isnull(c.bans) OR c.bans=0)
                                        AND s.country_id!='4'
                                      ORDER BY caps DESC LIMIT 100") or die ($dbConnCommunity->error.capstreaksget_LINE_);
/* ALLIED MOST CAPTURES ON SINGLE SORTIE */
while ($row = $sortallycaps->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, sortcapscallsign, capstreak) VALUES ('1','camp','".$row['callsign']."','".$row['caps']."')") or die ($dbconn->error.capstreaksinsert_LINE_); }



/* Daily Allied Top Captures -- MOST CAPTURES IN 1 SORTIE */
$dsortallycaps = mysqli_query($dbConnCommunity, "SELECT callsign, 
                                            s.captures as caps 
                                      FROM scoring_campaign_sorties s 
                                      LEFT JOIN wwii_player w ON (s.player_id=w.playerid) 
                                      WHERE s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR) 
                                        AND s.country_id!='4' 
                                      ORDER BY caps DESC LIMIT 100") or die ($dbConnCommunity->error.capstreaksget_LINE_);

/* ALLIED MOST CAPTURES ON SINGLE SORTIE - FOR LAST 24 HOURS */
while ($row = $dsortallycaps->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, sortcapscallsign, capstreak) VALUES ('1','day','".$row['callsign']."','".$row['caps']."')") or die ($dbconn->error.capstreaksinsert_LINE_); }

/* Axis kill data 

$axkills = mysqli_query($dbConnCommunity, "SELECT callsign, SUM(p.kills) AS kills 
                                        FROM scoring_campaign_personas AS p 
                                        LEFT JOIN scoring_persona_configs c ON (p.persona_id = c.persona_id), wwii_persona, wwii_player 
                                            WHERE wwii_persona.personaid = p.persona_id 
                                            AND wwii_player.playerid = wwii_persona.playerid 
                                            AND wwii_persona.countryid ='4' 
                                            AND (ISNULL(c.bans) OR c.bans = 0) GROUP BY callsign ORDER BY kills DESC LIMIT 100") or die ($dbConnCommunity->error.kills_LINE_);
 
while ($row = $axkills->fetch_assoc())
    {
       mysqli_query($dbConnCommunity, "INSERT INTO scoring_top_Players (side, period,killscallsign,kills) VALUES ('2','camp','".$row['callsign']."','".$row['kills']."')") or die ($dbConnCommunity->error.killsinsert_LINE_); //echo $row['callsign']." Has ".$row['kills']." Kills.<br>" ;
    }

/*Axis capture info 
$axcaps = mysqli_query($dbConnCommunity, "SELECT callsign, sum(p.captures) as caps 
                                        FROM scoring_campaign_personas p 
                                        LEFT JOIN scoring_persona_configs c ON (p.persona_id = c.persona_id), wwii_persona, wwii_player 
                                        WHERE wwii_persona.personaid = p.persona_id 
                                        AND wwii_player.playerid = wwii_persona.playerid 
                                        AND wwii_persona.countryid ='4'
                                        AND (isnull(c.bans) OR c.bans = 0) GROUP BY callsign ORDER BY caps DESC LIMIT 100") or die ($dbConnCommunity->error.kills_LINE_);

while ($row = $axcaps->fetch_assoc())
    {
       mysqli_query($dbConnCommunity, "INSERT INTO scoring_top_players (side, period,capscallsign,caps) VALUES ('2','camp','".$row['callsign']."','".$row['caps']."')") or die ($dbConnCommunity->error.capsinsert_LINE_);
    }
 */   
 ################# START AXIS STATS SECTION ################################################################

/* Queries */
/* Axis STATS REDUCDED TO 1 LINE ARE ALL THOSE THAT ARE COMPLETELY DONE */
/* Top AAA by points -- point formula = KILLS * KD    --- FOR Axis SIDE -- Need Sortie counts*/
$gtaa = mysqli_query($dbConnCommunity, "SELECT w.callsign,
                                        IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                  FROM scoring_campaign_sorties s
                                        JOIN wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c 
                                            WHERE s.vehicle_id IN(111, 71)
                                            AND s.persona_id=c.persona_id
                                            AND (isnull(c.bans) OR c.bans = 0) 
                                  GROUP BY w.callsign ORDER BY points DESC limit 100") or die ($dbConnCommunity->error.taaaquery_LINE_);
/* TOP AAA */
while ($row = $gtaa->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, aaacallsign, aaapoints) VALUES ('2','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.taa_LINE_);  } 

/* Daily Top AAA by points -- point formula = KILLS * KD    --- FOR Axis SIDE -- Need Sortie counts*/
$gdtaa = mysqli_query($dbConnCommunity, "SELECT w.callsign,
                                        SUM(s.kills) as kills,
                                        IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                   FROM `scoring_campaign_sorties` s 
                                        JOIN wwii_player w ON s.player_id=w.playerid 
                                            WHERE s.vehicle_id IN(111,71)
                                            AND s.`sortie_start` > DATE_SUB(NOW(), INTERVAL 24 HOUR) 
                                   GROUP BY w.callsign ORDER BY points DESC limit 100") or die ($dbConnCommunity->error.taaaquery_LINE_);
/*Daily Top AAA */
while ($row = $gdtaa->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, aaacallsign, aaapoints) VALUES ('2','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.taa_LINE_);  } 


/* Top ATG by points -- point formula = KILLS * KD    --- FOR Axis SIDE -- Need Sortie counts*/
$gtatg = mysqli_query($dbConnCommunity, "SELECT w.callsign, 
                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c  
                                        WHERE s.vehicle_id IN(30, 130, 29, 166)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.tatg_LINE_);

while ($row = $gtatg->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, atgcallsign, atgpoints) VALUES ('2','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.tatg_LINE_); } 

/* Daily Top ATG by points -- point formula = KILLS * KD    --- FOR Axis SIDE -- Need Sortie counts*/
$gdtatg = mysqli_query($dbConnCommunity, "SELECT w.callsign, 
                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid 
                                        WHERE s.vehicle_id IN(30, 130, 29, 166)
                                        AND s.`sortie_start` > DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.datg_LINE_);

while ($row = $gdtatg->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, atgcallsign, atgpoints) VALUES ('2','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.datg_LINE_);} 


/* Top ATR by points -- point formula = (KILLS + CAPS )) * KD    --- FOR Axis SIDE*/
$gtatr = mysqli_query($dbConnCommunity, "SELECT w.callsign, 
                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+SUM(s.captures)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c  
                                        WHERE s.vehicle_id IN(120, 189, 2681)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.atr_LINE_);

while ($row = $gtatr->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, atrcallsign, atrpoints) VALUES ('2','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.atrinsert_LINE_); }

/* Daily Top ATR by points -- point formula = (KILLS + CAPS )) * KD    --- FOR Axis SIDE*/
$gdtatr = mysqli_query($dbConnCommunity, "SELECT w.callsign, 
                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+SUM(s.captures)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid  
                                        WHERE s.vehicle_id IN(120, 189, 2681)
                                        AND s.`sortie_start` > DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.datr_LINE_);

while ($row = $gdtatr->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, atrcallsign, atrpoints) VALUES ('2','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.atrinsert_LINE_); }

/* Top Engineer by points -- point formula = (KILLS + (CAPS * 5)) * KD    --- FOR Axis SIDE*/
$gteng = mysqli_query($dbConnCommunity, "SELECT w.callsign, 
                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+(SUM(s.captures)*5)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c  
                                        WHERE s.vehicle_id IN(33, 18114 )
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.engquery_LINE_);

while ($row = $gteng->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, engcallsign, engpoints) VALUES ('2','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.enginsert_LINE_);  } 

/* Daily Top Engineer by points -- point formula = (KILLS + (CAPS * 5)) * KD    --- FOR Axis SIDE*/
$gdteng = mysqli_query($dbConnCommunity, "SELECT w.callsign, 
                                              IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+(SUM(s.captures)*5)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid  
                                        WHERE s.vehicle_id IN(33, 18114)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.dengquery_LINE_);
/* DAILY TOP Engineer */
while ($row = $gdteng->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, engcallsign, engpoints) VALUES ('2','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.enginsert_LINE_);  } 

/* Top Grenadier by points -- point formula = (KILLS + CAPS )) * KD    --- FOR Axis SIDE*/
$gtgren = mysqli_query($dbConnCommunity, "SELECT w.callsign, 

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+SUM(s.captures)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c  
                                        WHERE s.vehicle_id IN(114)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.grenadierquery_LINE_);

while ($row = $gtgren->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,grencallsign, grenpoints) VALUES ('2','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.grenadierinsert_LINE_); } 

/* Daily Top Grenadier by points -- point formula = (KILLS + CAPS )) * KD    --- FOR Axis SIDE*/
$gdtgren = mysqli_query($dbConnCommunity, "SELECT w.callsign, 

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+SUM(s.captures)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid  
                                        WHERE s.vehicle_id IN(114)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.grenadierquery_LINE_);

while ($row = $gdtgren->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,grencallsign, grenpoints) VALUES ('2','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.grenadierinsert_LINE_); } 

/* Top LMG by points -- point formula = (KILLS + (CAPS * 2)) * KD    --- FOR Axis SIDE*/
$gtlmg = mysqli_query($dbConnCommunity, "SELECT w.callsign, 

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+(SUM(s.captures)*2)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c  
                                        WHERE s.vehicle_id IN(106, 188)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.lmgquery_LINE_);
/* TOP LMG */
while ($row = $gtlmg->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,lmgcallsign, lmgpoints) VALUES ('2','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.tlmginsert_LINE_); }

/* Daily  Top LMG by points -- point formula = (KILLS + (CAPS * 2)) * KD    --- FOR Axis SIDE*/
$gdtlmg = mysqli_query($dbConnCommunity, "SELECT w.callsign, 

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+(SUM(s.captures)*2)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid  
                                        WHERE s.vehicle_id IN(106,188)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.lmgquery_LINE_);
/* DAILY TOP LMG */ 
while ($row = $gdtlmg->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,lmgcallsign, lmgpoints) VALUES ('2','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.tlmginsert_LINE_); }

/* Top Mortar by points -- point formula = (KILLS + (CAPS * 2)) * KD    --- FOR Axis SIDE*/
$gtmort = mysqli_query($dbConnCommunity, "SELECT w.callsign, 
                                        IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+(SUM(s.captures)*2)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c  
                                        WHERE s.vehicle_id IN(169, 191)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.mortquery_LINE_);
/* TOP MORTAR */
while ($row = $gtmort->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,mortcallsign, mortpoints) VALUES ('2','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.tmortinsert_LINE_); }

/* Daily Top Mortar by points -- point formula = (KILLS + (CAPS * 2)) * KD    --- FOR Axis SIDE*/
$gdtmort = mysqli_query($dbConnCommunity, "SELECT w.callsign, 

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+(SUM(s.captures)*2)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid  
                                        WHERE s.vehicle_id IN(169, 191)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.mortquery_LINE_);
/* DAILY TOP MORTAR */ 
while ($row = $gdtmort->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,mortcallsign, mortpoints) VALUES ('2','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.tmortinsert_LINE_); }

/* Top Rifleman by points -- point formula = (KILLS + (CAPS * 5)) * KD    --- FOR Axis SIDE*/
$gtrifle = mysqli_query($dbConnCommunity, "SELECT w.callsign, 

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+(SUM(s.captures)*5)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c  
                                        WHERE s.vehicle_id IN(186, 139, 31, 10921, 2680, 18118, 18117)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.testriflequery_LINE_);
/* TOP RIFLE */
while ($row = $gtrifle->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,rcallsign, rpoints) VALUES ('2','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.trifleinsert_LINE_); }

/* Daily Top Rifleman by points -- point formula = (KILLS + (CAPS * 5)) * KD    --- FOR Axis SIDE*/
$gdtrifle = mysqli_query($dbConnCommunity, "SELECT w.callsign, 

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+(SUM(s.captures)*5)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid  
                                        WHERE s.vehicle_id IN(186, 139, 31, 10921, 2680, 18118, 18117)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.testriflequery_LINE_);
/* DAILY TOP RIFLE */
while ($row = $gdtrifle->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,rcallsign, rpoints) VALUES ('2','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.trifleinsert_LINE_); }

/* Top SMG by points -- point formula = (KILLS + (CAPS * 5)) * KD    --- FOR Axis SIDE*/
$gtsmg = mysqli_query($dbConnCommunity, "SELECT w.callsign, 

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+(SUM(s.captures)*5)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c  
                                        WHERE s.vehicle_id IN(18116, 18115, 32, 187)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.smgquery_LINE_);

while ($row = $gtsmg->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,smgcallsign, smgpoints) VALUES ('2','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.smginsert_LINE_); } 

/* Daliy Top SMG by points -- point formula = (KILLS + (CAPS * 5)) * KD    --- FOR Axis SIDE*/
$gdtsmg = mysqli_query($dbConnCommunity, "SELECT w.callsign, 

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+(SUM(s.captures)*5)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid  
                                        WHERE s.vehicle_id IN(18116, 18115, 32, 187)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.smgquery_LINE_);

while ($row = $gdtsmg->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,smgcallsign, smgpoints) VALUES ('2','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.smginsert_LINE_); } 


/* Top Sniper by points -- point formula = (KILLS + CAPS )) * KD    --- FOR Axis SIDE*/
$gtsniper = mysqli_query($dbConnCommunity, "SELECT w.callsign, 

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+SUM(s.captures)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c  
                                        WHERE s.vehicle_id IN(190, 162)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.atr_LINE_);

while ($row = $gtsniper->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,snipercallsign, sniperpoints) VALUES ('2','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.atrinsert_LINE_); }

/* Daily Top Sniper by points -- point formula = (KILLS + CAPS )) * KD    --- FOR Axis SIDE*/
$gdtsniper = mysqli_query($dbConnCommunity, "SELECT w.callsign, 

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+SUM(s.captures)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid  
                                        WHERE s.vehicle_id IN(190, 162)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.atr_LINE_);

while ($row = $gdtsniper->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,snipercallsign, sniperpoints) VALUES ('2','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.atrinsert_LINE_); }


/* Top Tanks by points -- point formula = KILLS * KD    --- FOR Axis SIDE*/
$gtank = mysqli_query($dbConnCommunity, "SELECT w.callsign, 

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c  
                                        WHERE s.vehicle_id IN(25,26,24,88,75,131,154,82,103,74,132)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.tankquery_LINE_);

while ($row = $gtank->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,tankcallsign, tankpoints) VALUES ('2','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.tankinsert_LINE_); } 

        /* Daily Top Tanks by points -- point formula = KILLS * KD    --- FOR Axis SIDE*/
$gdttank = mysqli_query($dbConnCommunity, "SELECT w.callsign, 

                                        IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid  
                                        WHERE s.vehicle_id IN(25,26,24,88,75,131,154,82,103,74,132)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.tankquery_LINE_);

while ($row = $gdttank->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,tankcallsign, tankpoints) VALUES ('2','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.tankinsert_LINE_); }

/* Top Truck by points -- point formula = (RTB % * Sorties)    --- FOR Axis SIDE*/
$gtruck = mysqli_query($dbConnCommunity, "SELECT w.callsign, 
                                        COUNT(CASE WHEN s.player_id = '*' THEN 1 ELSE 0 END) as sortie,
                                        ((SUM(CASE WHEN s.rtb = '0' THEN 1 ELSE 0 END)/COUNT(s.player_id)) * COUNT(CASE WHEN s.player_id = '*' THEN 1 ELSE 0 END))  as points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c  
                                        WHERE s.vehicle_id IN(27,28)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0) 
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.truckquery_LINE_);
/* TOP TRUCK */
while ($row = $gtruck->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, truckcallsign, truckpoints) VALUES ('2','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.ttruckinsert_LINE_); }

/* Daily Top Truck by points -- point formula = (RTB % * Sorties)    --- FOR Axis SIDE*/
$gdttruck = mysqli_query($dbConnCommunity, "SELECT w.callsign, 
                                        COUNT(CASE WHEN s.player_id = '*' THEN 1 ELSE 0 END) as sortie,
                                        IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points  
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid  
                                        WHERE s.vehicle_id IN(27,28)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR) 
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.truckquery_LINE_);
/* DAILY TOP TRUCK */
while ($row = $gdttruck->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, truckcallsign, truckpoints) VALUES ('2','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.ttruckinsert_LINE_); }

/* Top fighter by points -- point formula = KILLS * KD    --- FOR Axis SIDE*/
$gfight = mysqli_query($dbConnCommunity, "SELECT w.callsign, 

                                            SUM(s.kills)*ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2) AS points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c  
                                        WHERE s.vehicle_id IN(193,23,195,197,92,172,66,93,136)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.bomber_LINE_);

while ($row = $gfight->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,fightcallsign, fightpoints) VALUES ('2','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.fightinsert_LINE_); } 

/* Daliy Top fighter by points -- point formula = KILLS * KD    --- FOR Axis SIDE*/
$gdtfight = mysqli_query($dbConnCommunity, "SELECT w.callsign, 

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid 
                                        WHERE s.vehicle_id IN(193,23,195,197,92,172,66,93,136)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.bomber_LINE_);

while ($row = $gdtfight->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,fightcallsign, fightpoints) VALUES ('2','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.fightinsert_LINE_); } 

/* Top bomber by points -- point formula = KILLS * KD    --- FOR Axis SIDE*/
$gtbomb = mysqli_query($dbConnCommunity, "SELECT w.callsign, 

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c  
                                        WHERE s.vehicle_id IN(76,22,196)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.bomber_LINE_);
/* TOP BOMBER */
while ($row = $gtbomb->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,bombcallsign, bombpoints) VALUES ('2','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.bomberinsert_LINE_); } 

/* Daily Top bomber by points -- point formula = KILLS * KD    --- FOR Axis SIDE*/
$gdtbomb = mysqli_query($dbConnCommunity, "SELECT w.callsign, 

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid  
                                        WHERE s.vehicle_id IN(76,22,196)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.bomber_LINE_);
/* DAILY TOP BOMBER */
while ($row = $gdtbomb->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,bombcallsign, bombpoints) VALUES ('2','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.bomberinsert_LINE_); }

/* Top Destroyer by points -- point formula = KILLS * KD    --- FOR Axis SIDE*/
$gtdd = mysqli_query($dbConnCommunity, "SELECT w.callsign, 

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c  
                                        WHERE s.vehicle_id IN(80)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.dd_LINE_);
/* TOP DESTROYER */
while ($row = $gtdd->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,ddcallsign, ddpoints) VALUES ('2','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.ddinsert_LINE_); } 
/* Daliy Top Destroyer by points -- point formula = KILLS * KD    --- FOR Axis SIDE*/
$gdtdd = mysqli_query($dbConnCommunity, "SELECT w.callsign, 

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points  
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid  
                                        WHERE s.vehicle_id IN(80)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.dd_LINE_);
/* DAILY TOP DESTROYER */
while ($row = $gdtdd->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,ddcallsign, ddpoints) VALUES ('2','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.ddinsert_LINE_); } 

/* Top PatrolBoats by points -- point formula = KILLS * KD    --- FOR Axis SIDE*/
$gtpb = mysqli_query($dbConnCommunity, "SELECT w.callsign, 

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c  
                                        WHERE s.vehicle_id IN(64)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.pb_LINE_);
/* TOP PATROL BOATS */
while ($row = $gtpb->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,pbcallsign, pbpoints) VALUES ('2','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.pbinsert_LINE_); }

    /* Daily Top PatrolBoats by points -- point formula = KILLS * KD    --- FOR Axis SIDE*/
$gdtpb = mysqli_query($dbConnCommunity, "SELECT w.callsign, 

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid  
                                        WHERE s.vehicle_id IN(64)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.pb_LINE_);
/* DAILY TOP PATROL BOATS */ 
while ($row = $gdtpb->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,pbcallsign, pbpoints) VALUES ('2','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.pbinsert_LINE_); } 


/* Top Freighter by points -- point formula = (RTB % * Sorties)    --- FOR Axis SIDE*/
$gttt = mysqli_query($dbConnCommunity, "SELECT w.callsign, 
                                        
                                        ((SUM(CASE WHEN s.rtb = '0' THEN 1 ELSE 0 END)/COUNT(s.player_id)) * COUNT(CASE WHEN s.player_id = '*' THEN 1 ELSE 0 END))  as points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c  
                                        WHERE s.vehicle_id IN(102)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0) 
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.ttquery_LINE_);
/* TOP FREIGHTER (Transport (tt)) */
while ($row = $gttt->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,ttcallsign, ttpoints) VALUES ('2','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.tttinsert_LINE_); }
/* Daily Top Freighter by points -- point formula = (RTB % * Sorties)    --- FOR Axis SIDE*/
$gdttt = mysqli_query($dbConnCommunity, "SELECT w.callsign, 
    
                                        ((SUM(CASE WHEN s.rtb = '0' THEN 1 ELSE 0 END)/COUNT(s.player_id)) * COUNT(CASE WHEN s.player_id = '*' THEN 1 ELSE 0 END))  as points 
                                    FROM scoring_campaign_sorties s
                                    JOIN wwii_player w ON s.player_id=w.playerid 
                                        WHERE s.vehicle_id IN(102)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR) 
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.ttquery_LINE_);
/* DAILY TOP FREIGHTER  */
while ($row = $gdttt->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,ttcallsign, ttpoints) VALUES ('2','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.tttinsert_LINE_); }


/* Axis Total Kills for Current Campaign */
$axkills = mysqli_query($dbConnCommunity, "SELECT callsign, 
                                            SUM(p.kills) AS kills

                                        FROM scoring_campaign_personas AS p 
                                        LEFT JOIN scoring_persona_configs c ON (p.persona_id = c.persona_id), wwii_persona, wwii_player 
                                        WHERE wwii_persona.personaid = p.persona_id 
                                            AND wwii_player.playerid = wwii_persona.playerid 
                                            AND wwii_persona.countryid ='4' 
                                            AND (ISNULL(c.bans) OR c.bans = 0) 
                                        GROUP BY callsign ORDER BY kills DESC LIMIT 100") or die ($dbConnCommunity->error.kills_LINE_);
/* TOP KILLS  */
while ($row = $axkills->fetch_assoc())
    {mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,killscallsign,kills) VALUES ('2','camp','".$row['callsign']."','".$row['kills']."')") or die ($dbconn->error.killsinsert_LINE_); }


/* Axis Kills for last 24 hour */
$daxkills = mysqli_query($dbConnCommunity, "SELECT callsign, 
                                            SUM(s.kills) AS kills
                                        FROM scoring_campaign_sorties AS s 
                                        LEFT JOIN scoring_persona_configs c ON (s.persona_id = c.persona_id), wwii_persona, wwii_player 
                                        WHERE DATE(s.`sortie_start`) >= DATE_SUB(NOW(), INTERVAL 24 HOUR) 
                                            AND wwii_persona.personaid = s.persona_id 
                                            AND wwii_player.playerid = wwii_persona.playerid 
                                            AND wwii_persona.countryid ='4' 
                                            AND (ISNULL(c.bans) OR c.bans = 0) GROUP BY callsign ORDER BY kills DESC LIMIT 100") or die ($dbConnCommunity->error.kills_LINE_);
/* DAILY TOP KILLS */
while ($row = $daxkills->fetch_assoc())
    {mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,killscallsign,kills) VALUES ('2','day','".$row['callsign']."','".$row['kills']."')") or die ($dbconn->error.killsinsert_LINE_);  }

/*  top captures info */
$axcaps = mysqli_query($dbConnCommunity, "SELECT callsign, 
                                            SUM(p.captures) AS caps
                                            
                                       FROM scoring_campaign_personas AS p 
                                       LEFT JOIN scoring_persona_configs c ON (p.persona_id = c.persona_id), wwii_persona, wwii_player 
                                       WHERE wwii_persona.personaid = p.persona_id 
                                            AND wwii_player.playerid = wwii_persona.playerid 
                                            AND wwii_persona.countryid ='4' 
                                            AND (ISNULL(c.bans) OR c.bans = 0) 
                                       GROUP BY callsign ORDER BY caps DESC LIMIT 100") or die ($dbConnCommunity->error.caps_LINE_);
/* MOST CAPTURES */
while ($row = $axcaps->fetch_assoc())
    {mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,capscallsign,caps) VALUES ('2','camp','".$row['callsign']."','".$row['caps']."')") or die ($dbconn->error.capsinsert_LINE_); }

/* Caps for last 24 hours */
$daxcaps = mysqli_query($dbConnCommunity, "SELECT callsign, SUM(s.captures) AS caps 
                                        FROM scoring_campaign_sorties AS s 
                                        LEFT JOIN scoring_persona_configs c ON (s.persona_id = c.persona_id),wwii_persona, wwii_player 
                                        WHERE DATE(s.`sortie_start`) >= DATE_SUB(NOW(), INTERVAL 24 HOUR) 
                                            AND wwii_persona.personaid = s.persona_id 
                                            AND wwii_player.playerid = wwii_persona.playerid 
                                            AND wwii_persona.countryid ='4' 
                                            AND (ISNULL(c.bans) OR c.bans = 0) 
                                        GROUP BY callsign ORDER BY caps DESC LIMIT 100") or die ($dbConnCommunity->error.dcaps_LINE_);
/* DAILY MOST CAPTURES */
while ($row = $daxcaps->fetch_assoc())
    {mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,capscallsign,caps) VALUES ('2','day','".$row['callsign']."','".$row['caps']."')") or die ($dbconn->error.capsinsert_LINE_);  }

/* Kills/Death (Kd) */
$axkd = mysqli_query($dbConnCommunity, "SELECT callsign, SUM(p.kills) as kills, SUM(p.deaths) as deaths, 
                                        SUM(p.kd) as kd
                                     FROM scoring_campaign_personas p 
                                     LEFT JOIN scoring_persona_configs c ON (p.persona_id = c.persona_id), wwii_persona, wwii_player 
                                     WHERE wwii_persona.personaid = p.persona_id 
                                            AND wwii_player.playerid = wwii_persona.playerid 
                                            AND wwii_persona.countryid ='4' 
                                            AND (isnull(c.bans) OR c.bans = 0) 
                                    GROUP BY callsign ORDER BY kd DESC LIMIT 100") or die ($dbConnCommunity->error.kdget_LINE_);
/* Top K/D */
while ($row = $axkd->fetch_assoc())
    {mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,kdcallsign,kd) VALUES ('2','camp','".$row['callsign']."','".$row['kd']."')") or die ($dbconn->error.kdinsert_LINE_); }

/* K/D For last 24 Hours */
$daxkd = mysqli_query($dbConnCommunity, "SELECT callsign, 
                                        ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2) AS kd 
                                      FROM scoring_campaign_sorties s 
                                      LEFT JOIN scoring_persona_configs c ON (s.persona_id = c.persona_id), wwii_persona, wwii_player 
                                      WHERE wwii_persona.personaid = s.persona_id 
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR) 
                                        AND wwii_player.playerid = wwii_persona.playerid 
                                        AND wwii_persona.countryid ='4' 
                                        AND (isnull(c.bans) OR c.bans = 0) 
                                      GROUP BY callsign ORDER BY kd DESC LIMIT 100") or die ($dbConnCommunity->error.kdget_LINE_);
while ($row = $daxkd->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,kdcallsign,kd) VALUES ('2','day','".$row['callsign']."','".$row['kd']."')") or die ($dbconn->error.kdinsert_LINE_); }

/* Axis MOST Time On Mission */
$axtom = mysqli_query($dbConnCommunity, "SELECT callsign, p.tom AS tom 
                                      FROM scoring_campaign_personas p 
                                      LEFT JOIN scoring_persona_configs c ON (p.persona_id = c.persona_id), wwii_persona, wwii_player 
                                      WHERE wwii_persona.personaid = p.persona_id 
                                        AND wwii_player.playerid = wwii_persona.playerid 
                                        AND wwii_persona.countryid ='4' 
                                        AND (isnull(c.bans) OR c.bans = 0) 
                                      GROUP BY callsign ORDER BY tom DESC LIMIT 100") or die ($dbConnCommunity->error.tomget_LINE_);
/* TIME ON MISSION */
while ($row = $axtom->fetch_assoc())
    {mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,tomcallsign,tom) VALUES ('2','camp','".$row['callsign']."','".$row['tom']."')") or die ($dbconn->error.tominsert_LINE_); }

/* Axis MOST Timeon Mission Last 24 Hours */                                  
$daxtom = mysqli_query($dbConnCommunity, "SELECT callsign, SUM(s.tom) AS tom 
                                       FROM scoring_campaign_sorties s 
                                       LEFT JOIN scoring_persona_configs c ON (s.persona_id = c.persona_id), wwii_persona, wwii_player 
                                       WHERE DATE(s.sortie_start) >= DATE_SUB(NOW(), INTERVAL 24 HOUR) 
                                            AND wwii_persona.personaid = s.persona_id 
                                            AND wwii_player.playerid = wwii_persona.playerid 
                                            AND wwii_persona.countryid ='4' 
                                            AND (isnull(c.bans) OR c.bans = 0) 
                                       GROUP BY callsign ORDER BY tom DESC LIMIT 100") or die ($dbConnCommunity->error.dtomget_LINE_);
while ($row = $daxtom->fetch_assoc())
    {mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,tomcallsign,tom) VALUES ('2','day','".$row['callsign']."','".$row['tom']."')") or die ($dbconn->error.dtominsert_LINE_); }


/* Top Kill Streak -- Number of consecutive Sorties with at least one kill.    --- FOR Axis SIDE*/
$gtkrow = mysqli_query($dbConnCommunity, "SELECT callsign, streak_id, best as value2, UNIX_TIMESTAMP(achieved), v.name, 0, 0 
                                    FROM scoring_vehicles v, scoring_campaign_streak_bests s 
                                    LEFT JOIN scoring_persona_configs c ON (s.persona_id = c.persona_id), wwii_player, wwii_persona 
                                    WHERE v.vehicle_id=s.vehicle_id 
                                        AND wwii_persona.personaid = s.persona_id 
                                        AND wwii_player.playerid = wwii_persona.playerid 
                                        AND s.streak_id = 4 
                                        AND wwii_persona.countryid ='4'  
                                        AND (isnull(c.bans) OR c.bans = 0) ORDER BY value2 DESC LIMIT 100") or die ($dbConnCommunity->error.tkrowquery_LINE_);
/* Most consecutive Sorties with a kill */
while ($row = $gtkrow->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,krowcallsign, krowpoints) VALUES ('2','camp','".$row['callsign']."','".$row['value2']."')") or die ($dbconn->error.tkrowinsert_LINE_);}

/* Daily Top Kill Streak -- Top players based on the Consecutive Sorties with a Kill streak.    --- FOR Axis SIDE*/
$gdtkrow = mysqli_query($dbConnCommunity, "SELECT DISTINCT(callsign) as callsign, streak_id, current as value2 
                                    FROM scoring_campaign_streaks s, wwii_player, wwii_persona 
                                    WHERE s.streak_id = 4
                                        AND s.achieved >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                        AND wwii_persona.personaid = s.persona_id
                                        AND wwii_player.playerid = wwii_persona.playerid 
                                        ORDER BY value2 DESC LIMIT 100") or die ($dbConnCommunity->error.tkrowquery_LINE_);
/* DAILY CONSECUTIVE SORTIES WITH A KILL */
while ($row = $gdtkrow->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,krowcallsign, krowpoints) VALUES ('2','day','".$row['callsign']."','".$row['value2']."')") or die ($dbconn->error.tkrowinsert_LINE_); }

/* Top Capture Streak -- Most sorties in a row with a capture */
$gtcrow = mysqli_query($dbConnCommunity, "SELECT callsign, streak_id, best as value2, UNIX_TIMESTAMP(achieved), v.name, 0, 0 
                                    FROM scoring_vehicles v, scoring_campaign_streak_bests s 
                                    LEFT JOIN scoring_persona_configs c ON (s.persona_id = c.persona_id), wwii_player, wwii_persona 
                                    WHERE v.vehicle_id=s.vehicle_id 
                                        AND wwii_persona.personaid = s.persona_id 
                                        AND wwii_player.playerid = wwii_persona.playerid 
                                        AND s.streak_id = 5 
                                        AND wwii_persona.countryid ='4' 
                                        AND (isnull(c.bans) OR c.bans = 0) ORDER BY value2 DESC LIMIT 100") or die ($dbConnCommunity->error.tkrowquery_LINE_);
/* SORTIES IN A ROW WITH A CAPTURE */
while ($row = $gtcrow->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,crowcallsign, crowpoints) VALUES ('2','camp','".$row['callsign']."','".$row['value2']."')") or die ($dbconn->error.tkrowinsert_LINE_); }

/* Daily Top Capture Streak -- Most sorties in a row with a capture */
$gdtcrow = mysqli_query($dbConnCommunity, "SELECT DISTINCT(callsign) as callsign, streak_id, current as value2 
                                    FROM scoring_campaign_streaks s, wwii_player, wwii_persona 
                                    WHERE s.streak_id = 5
                                        AND s.achieved >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                        AND wwii_persona.personaid = s.persona_id
                                        AND wwii_player.playerid = wwii_persona.playerid 
                                        ORDER BY value2 DESC LIMIT 100") or die ($dbConnCommunity->error.tkrowquery_LINE_);
/* DAILY - MOST SORTIES IN A ROW WITH A CAPTURE */
while ($row = $gdtcrow->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,crowcallsign, crowpoints) VALUES ('2','day','".$row['callsign']."','".$row['value2']."')") or die ($dbconn->error.tkrowinsert_LINE_); }

/* Top kills in a sortie (top kills streak) Top players based on the most Kills in a Sortie */
$axsks = mysqli_query($dbConnCommunity, "SELECT callsign,  
                                            s.kills as kills 
                                      FROM scoring_campaign_sorties s 
                                      LEFT JOIN wwii_player w ON (s.player_id=w.playerid), scoring_persona_configs c 
                                      WHERE s.persona_id=c.persona_id 
                                        AND s.country_id='4' 
                                        AND (isnull(c.bans) OR c.bans=0) 
                                      ORDER BY kills DESC LIMIT 100 ") or die ($dbConnCommunity->error.kstreaksget_LINE_);
/* Most kills on a SINGLE SORTIE */
while ($row = $axsks->fetch_assoc())
    {mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, sortmkillcallsign, sortmkill) VALUES ('2','camp','".$row['callsign']."','".$row['kills']."')") or die ($dbconn->error.kstreaksinsert_LINE_);}

/* Daily Top kills in a sortie (top kills streak) Top players based on the most Kills in a Sortie */
$daxsks = mysqli_query($dbConnCommunity, "SELECT callsign, 
                                            s.kills as kills 
                                       FROM scoring_campaign_sorties s 
                                       LEFT JOIN wwii_player w ON (s.player_id=w.playerid), scoring_persona_configs c
                                       WHERE s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                            AND s.persona_id=c.persona_id
                                            AND s.country_id='4'  
                                            AND (isnull(c.bans) OR c.bans=0)
                                       ORDER BY kills DESC LIMIT 100") or die ($dbConnCommunity->error.kstreaksget_LINE_);
/* Most kills on a SINGLE SORTIE - last 24 hours */
while ($row = $daxsks->fetch_assoc())
    {
       mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, sortmkillcallsign, sortmkill) VALUES ('2','day','".$row['callsign']."','".$row['kills']."')") or die ($dbconn->error.kstreaksinsert_LINE_); }

/* Axis Top Captures -- MOST CAPTURES IN 1 SORTIE */
$sortaxcaps = mysqli_query($dbConnCommunity, "SELECT callsign, 
                                            s.captures as caps 
                                      FROM scoring_campaign_sorties s 
                                      LEFT JOIN wwii_player w ON (s.player_id=w.playerid), scoring_persona_configs c 
                                      WHERE s.persona_id=c.persona_id 
                                        AND (isnull(c.bans) OR c.bans=0)
                                        AND s.country_id='4'
                                      ORDER BY caps DESC LIMIT 100") or die ($dbConnCommunity->error.capstreaksget_LINE_);
/* Axis MOST CAPTURES ON SINGLE SORTIE */
while ($row = $sortaxcaps->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, sortcapscallsign, capstreak) VALUES ('2','camp','".$row['callsign']."','".$row['caps']."')") or die ($dbconn->error.capstreaksinsert_LINE_); }



/* Daily Axis Top Captures -- MOST CAPTURES IN 1 SORTIE */
$dsortaxcaps = mysqli_query($dbConnCommunity, "SELECT callsign, 
                                            s.captures as caps 
                                      FROM scoring_campaign_sorties s 
                                      LEFT JOIN wwii_player w ON (s.player_id=w.playerid) 
                                      WHERE s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR) 
                                        AND s.country_id='4' 
                                      ORDER BY caps DESC LIMIT 100") or die ($dbConnCommunity->error.capstreaksget_LINE_);

/* Axis MOST CAPTURES ON SINGLE SORTIE - FOR LAST 24 HOURS */
while ($row = $dsortaxcaps->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, sortcapscallsign, capstreak) VALUES ('2','day','".$row['callsign']."','".$row['caps']."')") or die ($dbconn->error.capstreaksinsert_LINE_); }

echo "done";

?>