<?php
/*
For collecting stats and updating the Top_players tables for both CSR and Gazette top player lists DB's as follows:
scoring_campaign_sorties= s
scoring_campaign_personas = p wwiionline.wwiionline.wwii_player
scoring_persona_configs = c
wwiionline.wwii_player = w

*/

set_time_limit(1500);
$dbconn = mysqli_connect("127.0.0.1:3306", "root", "changeme", "gazette");
if (mysqli_connect_errno()){
    echo "Failed to connect to the Gazette database: " . mysqli_connect_error();
}

$dbConnWWII = mysqli_connect("127.0.0.1:3306","root","changeme","wwii");
if (mysqli_connect_errno()){
    echo "Failed to connect to WWII database: " . mysqli_connect_error();
}

$dbConnWWIIOL = mysqli_connect("127.0.0.1:3306","root","changeme","wwiionline");
if (mysqli_connect_errno()){
    echo "Failed to connect to WWIIOnline database: " . mysqli_connect_error();
}

$dbconnAuth = mysqli_connect("127.0.0.1:3306", "root", "changeme", "auth");
if (mysqli_connect_errno()){
    echo "Failed to connect to the Auth database: " . mysqli_connect_error();
}

$dbConnToe = mysqli_connect("127.0.0.1:3306", "root", "changeme", "toe");
if (mysqli_connect_errno()){
    echo "Failed to connect to the Toe database: " . mysqli_connect_error();
}

$dbConnCommunity = mysqli_connect("127.0.0.1:3306", "root", "changeme", "community");
if (mysqli_connect_errno()){
    echo "Failed to connect to the Community database: " . mysqli_connect_error();
}

$dbConnWebmap = mysqli_connect("127.0.0.1:3306", "root", "changeme", "webmap");
if (mysqli_connect_errno()){
    echo "Failed to connect to the Webmap database: " . mysqli_connect_error();
}
##################### ALLIED STORIES ############################
/* Queries Pull Data for Allied side*/
/* ALLIED STATS REDUCDED TO 1 LINE ARE ALL THOSE THAT ARE COMPLETELY DONE */

/* Top AAA by points -- point formula = KILLS * KD    --- FOR ALLIED SIDE -- Need Sortie counts*/
$taa = mysqli_query($dbConnCommunity, "SELECT w.callsign,
                                        IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                       FROM scoring_campaign_sorties s
                                        JOIN wwiionline.wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c
                                            WHERE s.vehicle_id IN(85,109,201,78,79,259)
                                            AND s.persona_id=c.persona_id
                                            AND (isnull(c.bans) OR c.bans = 0)
                                  GROUP BY w.callsign ORDER BY points DESC limit 100") or die ($dbConnCommunity->error.$taa);

/* Daily Top AAA by points -- point formula = KILLS * KD    --- FOR ALLIED SIDE -- Need Sortie counts*/
$dtaa = mysqli_query($dbConnCommunity, "SELECT w.callsign,
                                        SUM(s.kills) as kills,
                                        IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                   FROM scoring_campaign_sorties s
                                        JOIN wwiionline.wwii_player w ON s.player_id=w.playerid
                                            WHERE s.vehicle_id IN(85,109,201,78,79,259)
                                            AND s.`sortie_start` > DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                   GROUP BY w.callsign ORDER BY points DESC limit 100") or die ($dbConnCommunity->error.$dtaa);

/* Top ATG by points -- point formula = KILLS * KD    --- FOR ALLIED SIDE -- Need Sortie counts*/
$tatg = mysqli_query($dbConnCommunity, "SELECT w.callsign,
                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c
                                        WHERE s.vehicle_id IN(156,124,207,202,69,17,18,154,7,123)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.$tatg);

/* Daily Top ATG by points -- point formula = KILLS * KD    --- FOR ALLIED SIDE -- Need Sortie counts*/
$dtatg = mysqli_query($dbConnCommunity, "SELECT w.callsign,
                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid
                                        WHERE s.vehicle_id IN(156,124,207,202,69,17,18,154,7,123)
                                        AND s.`sortie_start` > DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.$datg);

/* Top ATR by points -- point formula = (KILLS + CAPS )) * KD    --- FOR ALLIED SIDE*/
$tatr = mysqli_query($dbConnCommunity, "SELECT w.callsign,
                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+SUM(s.captures)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c
                                        WHERE s.vehicle_id IN(120,121,166,172,260,193,196,197)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.atr_LINE_);

/* Daily Top ATR by points -- point formula = (KILLS + CAPS )) * KD    --- FOR ALLIED SIDE*/
$dtatr = mysqli_query($dbConnCommunity, "SELECT w.callsign,
                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+SUM(s.captures)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid
                                        WHERE s.vehicle_id IN(120,121,166,172,260,193,196,197)
                                        AND s.`sortie_start` > DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.datr_LINE_);

/* Top Engineer by points -- point formula = (KILLS + (CAPS * 5)) * KD    --- FOR ALLIED SIDE*/
$teng = mysqli_query($dbConnCommunity, "SELECT w.callsign,
                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+(SUM(s.captures)*5)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c
                                        WHERE s.vehicle_id IN(10,21,205,265,266,274)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.engquery_LINE_);

/* Daily Top Engineer by points -- point formula = (KILLS + (CAPS * 5)) * KD    --- FOR ALLIED SIDE*/
$dteng = mysqli_query($dbConnCommunity, "SELECT w.callsign,
                                              IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+(SUM(s.captures)*5)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid
                                        WHERE s.vehicle_id IN(10,21,205,265,266,274)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.dengquery_LINE_);

/* Top Grenadier by points -- point formula = (KILLS + CAPS )) * KD    --- FOR ALLIED SIDE*/
$tgren = mysqli_query($dbConnCommunity, "SELECT w.callsign,

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+SUM(s.captures)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c
                                        WHERE s.vehicle_id IN(112,113)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.grenadierquery_LINE_);

/* Daily Top Grenadier by points -- point formula = (KILLS + CAPS )) * KD    --- FOR ALLIED SIDE*/
$dtgren = mysqli_query($dbConnCommunity, "SELECT w.callsign,

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+SUM(s.captures)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid
                                        WHERE s.vehicle_id IN(112,113)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.grenadierquery_LINE_);

/* Top LMG by points -- point formula = (KILLS + (CAPS * 2)) * KD    --- FOR ALLIED SIDE*/
$tlmg = mysqli_query($dbConnCommunity, "SELECT w.callsign,

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+(SUM(s.captures)*2)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c
                                        WHERE s.vehicle_id IN(105,104,165,171)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.lmgquery_LINE_);

/* Daily  Top LMG by points -- point formula = (KILLS + (CAPS * 2)) * KD    --- FOR ALLIED SIDE*/
$dtlmg = mysqli_query($dbConnCommunity, "SELECT w.callsign,

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+(SUM(s.captures)*2)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid
                                        WHERE s.vehicle_id IN(105,104,165,171)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.lmgquery_LINE_);

/* Top Mortar by points -- point formula = (KILLS + (CAPS * 2)) * KD    --- FOR ALLIED SIDE*/
$tmort = mysqli_query($dbConnCommunity, "SELECT w.callsign,
                                        IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+(SUM(s.captures)*2)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c
                                        WHERE s.vehicle_id IN(161,162,174,168,261)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.mortquery_LINE_);

/* Daily Top Mortar by points -- point formula = (KILLS + (CAPS * 2)) * KD    --- FOR ALLIED SIDE*/
$dtmort = mysqli_query($dbConnCommunity, "SELECT w.callsign,

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+(SUM(s.captures)*2)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid
                                        WHERE s.vehicle_id IN(161,162,174,168,261)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.mortquery_LINE_);

/* Top Rifleman by points -- point formula = (KILLS + (CAPS * 5)) * KD    --- FOR ALLIED SIDE*/
$trifle = mysqli_query($dbConnCommunity, "SELECT w.callsign,

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+(SUM(s.captures)*5)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c
                                        WHERE s.vehicle_id IN(8,19,188,169,163,189,191,262,264)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.testriflequery_LINE_);

/* Daily Top Rifleman by points -- point formula = (KILLS + (CAPS * 5)) * KD    --- FOR ALLIED SIDE*/
$dtrifle = mysqli_query($dbConnCommunity, "SELECT w.callsign,

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+(SUM(s.captures)*5)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid
                                        WHERE s.vehicle_id IN(8,19,188,169,163,189,191,262,264)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.testriflequery_LINE_);

/* Top kills in a sortie (top kills streak) Top players based on the most Kills in a Sortie */
$axsks = mysqli_query($dbConnCommunity, "SELECT callsign,
                                            s.kills as kills
                                      FROM scoring_campaign_sorties s
                                      LEFT JOIN wwiionline.wwii_player w ON (s.player_id=w.playerid), scoring_persona_configs c
                                      WHERE s.persona_id=c.persona_id
                                        AND s.country_id='4'
                                        AND (isnull(c.bans) OR c.bans=0)
                                      ORDER BY kills DESC LIMIT 100 ") or die ($dbConnCommunity->error.kstreaksget_LINE_);

/* Daily Top kills in a sortie (top kills streak) Top players based on the most Kills in a Sortie */
$daxsks = mysqli_query($dbConnCommunity, "SELECT callsign,
                                            s.kills as kills
                                       FROM scoring_campaign_sorties s
                                       LEFT JOIN wwiionline.wwii_player w ON (s.player_id=w.playerid), scoring_persona_configs c
                                       WHERE s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                            AND s.persona_id=c.persona_id
                                            AND s.country_id='4'
                                            AND (isnull(c.bans) OR c.bans=0)
                                       ORDER BY kills DESC LIMIT 100") or die ($dbConnCommunity->error.kstreaksget_LINE_);

/* Axis Top Captures -- MOST CAPTURES IN 1 SORTIE */
$sortaxcaps = mysqli_query($dbConnCommunity, "SELECT callsign,
                                            s.captures as caps
                                      FROM scoring_campaign_sorties s
                                      LEFT JOIN wwiionline.wwii_player w ON (s.player_id=w.playerid), scoring_persona_configs c
                                      WHERE s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans=0)
                                        AND s.country_id='4'
                                      ORDER BY caps DESC LIMIT 100") or die ($dbConnCommunity->error.capstreaksget_LINE_);

/* Daily Axis Top Captures -- MOST CAPTURES IN 1 SORTIE */
$dsortaxcaps = mysqli_query($dbConnCommunity, "SELECT callsign,
                                            s.captures as caps
                                      FROM scoring_campaign_sorties s
                                      LEFT JOIN wwiionline.wwii_player w ON (s.player_id=w.playerid)
                                      WHERE s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                        AND s.country_id='4'
                                      ORDER BY caps DESC LIMIT 100") or die ($dbConnCommunity->error.capstreaksget_LINE_);

/* Daily Top Capture Streak -- Most sorties in a row with a capture */
$dtcrow = mysqli_query($dbConnCommunity, "SELECT DISTINCT(callsign) as callsign, streak_id, current as value2
                                    FROM scoring_campaign_streaks s, wwiionline.wwii_player, wwiionline.wwii_persona
                                    WHERE s.streak_id = 5
                                        AND s.achieved >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                        AND wwiionline.wwii_persona.personaid = s.persona_id
                                        AND wwiionline.wwii_player.playerid = wwiionline.wwii_persona.playerid
                                        ORDER BY value2 DESC LIMIT 100") or die ($dbConnCommunity->error.tkrowquery_LINE_);

/* Top kills in a sortie (top kills streak) Top players based on the most Kills in a Sortie */
$allysks = mysqli_query($dbConnCommunity, "SELECT callsign,
                                            s.kills as kills
                                      FROM scoring_campaign_sorties s
                                      LEFT JOIN wwiionline.wwii_player w ON (s.player_id=w.playerid), scoring_persona_configs c
                                      WHERE s.persona_id=c.persona_id
                                        AND s.country_id!='4'
                                        AND (isnull(c.bans) OR c.bans=0)
                                      ORDER BY kills DESC LIMIT 100 ") or die ($dbConnCommunity->error.kstreaksget_LINE_);

/* Daily Top kills in a sortie (top kills streak) Top players based on the most Kills in a Sortie */
$dallysks = mysqli_query($dbConnCommunity, "SELECT callsign,
                                            s.kills as kills
                                       FROM scoring_campaign_sorties s
                                       LEFT JOIN wwiionline.wwii_player w ON (s.player_id=w.playerid), scoring_persona_configs c
                                       WHERE s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                            AND s.persona_id=c.persona_id
                                            AND s.country_id!='4'
                                            AND (isnull(c.bans) OR c.bans=0)
                                       ORDER BY kills DESC LIMIT 100") or die ($dbConnCommunity->error.kstreaksget_LINE_);

/* Allied Top Captures -- MOST CAPTURES IN 1 SORTIE */
$sortallycaps = mysqli_query($dbConnCommunity, "SELECT callsign,
                                            s.captures as caps
                                      FROM scoring_campaign_sorties s
                                      LEFT JOIN wwiionline.wwii_player w ON (s.player_id=w.playerid), scoring_persona_configs c
                                      WHERE s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans=0)
                                        AND s.country_id!='4'
                                      ORDER BY caps DESC LIMIT 100") or die ($dbConnCommunity->error.capstreaksget_LINE_);


/* Daily Allied Top Captures -- MOST CAPTURES IN 1 SORTIE */
$dsortallycaps = mysqli_query($dbConnCommunity, "SELECT callsign,
                                            s.captures as caps
                                      FROM scoring_campaign_sorties s
                                      LEFT JOIN wwiionline.wwii_player w ON (s.player_id=w.playerid)
                                      WHERE s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                        AND s.country_id!='4'
                                      ORDER BY caps DESC LIMIT 100") or die ($dbConnCommunity->error.capstreaksget_LINE_);

/* Daily Top Capture Streak -- Most sorties in a row with a capture */
$dtcrow = mysqli_query($dbConnCommunity, "SELECT DISTINCT(callsign) as callsign, streak_id, current as value2
                                    FROM scoring_campaign_streaks s, wwiionline.wwii_player, wwiionline.wwii_persona
                                    WHERE s.streak_id = 5
                                        AND s.achieved >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                        AND wwiionline.wwii_persona.personaid = s.persona_id
                                        AND wwiionline.wwii_player.playerid = wwiionline.wwii_persona.playerid
                                        ORDER BY value2 DESC LIMIT 100") or die ($dbConnCommunity->error.tkrowquery_LINE_);

/* Top kills in a sortie (top kills streak) Top players based on the most Kills in a Sortie */
$allysks = mysqli_query($dbConnCommunity, "SELECT callsign,
                                            s.kills as kills
                                      FROM scoring_campaign_sorties s
                                      LEFT JOIN wwiionline.wwii_player w ON (s.player_id=w.playerid), scoring_persona_configs c
                                      WHERE s.persona_id=c.persona_id
                                        AND s.country_id!='4'
                                        AND (isnull(c.bans) OR c.bans=0)
                                      ORDER BY kills DESC LIMIT 100 ") or die ($dbConnCommunity->error.kstreaksget_LINE_);

/* Daily Top kills in a sortie (top kills streak) Top players based on the most Kills in a Sortie */
$dallysks = mysqli_query($dbConnCommunity, "SELECT callsign,
                                            s.kills as kills
                                       FROM scoring_campaign_sorties s
                                       LEFT JOIN wwiionline.wwii_player w ON (s.player_id=w.playerid), scoring_persona_configs c
                                       WHERE s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                            AND s.persona_id=c.persona_id
                                            AND s.country_id!='4'
                                            AND (isnull(c.bans) OR c.bans=0)
                                       ORDER BY kills DESC LIMIT 100") or die ($dbConnCommunity->error.kstreaksget_LINE_);

/* Allied Top Captures -- MOST CAPTURES IN 1 SORTIE */
$sortallycaps = mysqli_query($dbConnCommunity, "SELECT callsign,
                                            s.captures as caps
                                      FROM scoring_campaign_sorties s
                                      LEFT JOIN wwiionline.wwii_player w ON (s.player_id=w.playerid), scoring_persona_configs c
                                      WHERE s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans=0)
                                        AND s.country_id!='4'
                                      ORDER BY caps DESC LIMIT 100") or die ($dbConnCommunity->error.capstreaksget_LINE_);

/* Daily Allied Top Captures -- MOST CAPTURES IN 1 SORTIE */
$dsortallycaps = mysqli_query($dbConnCommunity, "SELECT callsign,
                                            s.captures as caps
                                      FROM scoring_campaign_sorties s
                                      LEFT JOIN wwiionline.wwii_player w ON (s.player_id=w.playerid)
                                      WHERE s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                        AND s.country_id!='4'
                                      ORDER BY caps DESC LIMIT 100") or die ($dbConnCommunity->error.capstreaksget_LINE_);

/* K/D For last 24 Hours */
$dallykd = mysqli_query($dbConnCommunity, "SELECT callsign,
                                        ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2) AS kd
                                      FROM scoring_campaign_sorties s
                                      LEFT JOIN scoring_persona_configs c ON (s.persona_id = c.persona_id), wwiionline.wwii_persona, wwiionline.wwii_player
                                      WHERE wwiionline.wwii_persona.personaid = s.persona_id
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                        AND wwiionline.wwii_player.playerid = wwiionline.wwii_persona.playerid
                                        AND wwiionline.wwii_persona.countryid !='4'
                                        AND (isnull(c.bans) OR c.bans = 0)
                                      GROUP BY callsign ORDER BY kd DESC LIMIT 100") or die ($dbConnCommunity->error.kdget_LINE_);

/* Allied MOST Time On Mission */
$allytom = mysqli_query($dbConnCommunity, "SELECT callsign, p.tom AS tom
                                      FROM scoring_campaign_personas p
                                      LEFT JOIN scoring_persona_configs c ON (p.persona_id = c.persona_id), wwiionline.wwii_persona, wwiionline.wwii_player
                                      WHERE wwiionline.wwii_persona.personaid = p.persona_id
                                        AND wwiionline.wwii_player.playerid = wwiionline.wwii_persona.playerid
                                        AND wwiionline.wwii_persona.countryid !='4'
                                        AND (isnull(c.bans) OR c.bans = 0)
                                      GROUP BY callsign ORDER BY tom DESC LIMIT 100") or die ($dbConnCommunity->error.tomget_LINE_);

/* Allied MOST Timeon Mission Last 24 Hours */
$dallytom = mysqli_query($dbConnCommunity, "SELECT callsign, SUM(s.tom) AS tom
                                       FROM scoring_campaign_sorties s
                                       LEFT JOIN scoring_persona_configs c ON (s.persona_id = c.persona_id), wwiionline.wwii_persona, wwiionline.wwii_player
                                       WHERE DATE(s.sortie_start) >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                            AND wwiionline.wwii_persona.personaid = s.persona_id
                                            AND wwiionline.wwii_player.playerid = wwiionline.wwii_persona.playerid
                                            AND wwiionline.wwii_persona.countryid !='4'
                                            AND (isnull(c.bans) OR c.bans = 0)
                                       GROUP BY callsign ORDER BY tom DESC LIMIT 100") or die ($dbConnCommunity->error.dtomget_LINE_);

/* Top Kill Streak -- Number of consecutive Sorties with at least one kill.    --- FOR ALLIED SIDE*/
$tkrow = mysqli_query($dbConnCommunity, "SELECT callsign, streak_id, best as value2, UNIX_TIMESTAMP(achieved), v.name, 0, 0
                                    FROM scoring_vehicles v, scoring_campaign_streak_bests s
                                    LEFT JOIN scoring_persona_configs c ON (s.persona_id = c.persona_id), wwiionline.wwii_player, wwiionline.wwii_persona
                                    WHERE v.vehicle_id=s.vehicle_id
                                        AND wwiionline.wwii_persona.personaid = s.persona_id
                                        AND wwiionline.wwii_player.playerid = wwiionline.wwii_persona.playerid
                                        AND s.streak_id = 4
                                        AND wwiionline.wwii_persona.countryid !='4'
                                        AND (isnull(c.bans) OR c.bans = 0) ORDER BY value2 DESC LIMIT 100") or die ($dbConnCommunity->error.tkrowquery_LINE_);

/* Daily Top Kill Streak -- Top players based on the Consecutive Sorties with a Kill streak.    --- FOR ALLIED SIDE*/
$dtkrow = mysqli_query($dbConnCommunity, "SELECT DISTINCT(callsign) as callsign, streak_id, current as value2
                                    FROM scoring_campaign_streaks s, wwiionline.wwii_player, wwiionline.wwii_persona
                                    WHERE s.streak_id = 4
                                        AND s.achieved >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                        AND wwiionline.wwii_persona.personaid = s.persona_id
                                        AND wwiionline.wwii_player.playerid = wwiionline.wwii_persona.playerid
                                        ORDER BY value2 DESC LIMIT 100") or die ($dbConnCommunity->error.tkrowquery_LINE_);

/* Top Capture Streak -- Most sorties in a row with a capture */
$tcrow = mysqli_query($dbConnCommunity, "SELECT callsign, streak_id, best as value2, UNIX_TIMESTAMP(achieved), v.name, 0, 0
                                    FROM scoring_vehicles v, scoring_campaign_streak_bests s
                                    LEFT JOIN scoring_persona_configs c ON (s.persona_id = c.persona_id), wwiionline.wwii_player, wwiionline.wwii_persona
                                    WHERE v.vehicle_id=s.vehicle_id
                                        AND wwiionline.wwii_persona.personaid = s.persona_id
                                        AND wwiionline.wwii_player.playerid = wwiionline.wwii_persona.playerid
                                        AND s.streak_id = 5
                                        AND wwiionline.wwii_persona.countryid !='4'
                                        AND (isnull(c.bans) OR c.bans = 0) ORDER BY value2 DESC LIMIT 100") or die ($dbConnCommunity->error.tkrowquery_LINE_);

/* Top SMG by points -- point formula = (KILLS + (CAPS * 5)) * KD    --- FOR ALLIED SIDE*/
$tsmg = mysqli_query($dbConnCommunity, "SELECT w.callsign,

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+(SUM(s.captures)*5)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c
                                        WHERE s.vehicle_id IN(269,170,164,20,9,272,268,199,270,271,273)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.smgquery_LINE_);

/* Daliy Top SMG by points -- point formula = (KILLS + (CAPS * 5)) * KD    --- FOR ALLIED SIDE*/
$dtsmg = mysqli_query($dbConnCommunity, "SELECT w.callsign,

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+(SUM(s.captures)*5)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid
                                        WHERE s.vehicle_id IN(269,170,164,20,9,272,268,199,270,271,273)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.smgquery_LINE_);

/* Top Sniper by points -- point formula = (KILLS + CAPS )) * KD    --- FOR ALLIED SIDE*/
$tsniper = mysqli_query($dbConnCommunity, "SELECT w.callsign,

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+SUM(s.captures)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c
                                        WHERE s.vehicle_id IN(167,173,149,203,151)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.atr_LINE_);

/* Daily Top Sniper by points -- point formula = (KILLS + CAPS )) * KD    --- FOR ALLIED SIDE*/
$dtsniper = mysqli_query($dbConnCommunity, "SELECT w.callsign,

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+SUM(s.captures)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid
                                        WHERE s.vehicle_id IN(167,173,149,203,151)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.atr_LINE_);

/* Top Tanks by points -- point formula = KILLS * KD    --- FOR ALLIED SIDE*/
$ttank = mysqli_query($dbConnCommunity, "SELECT w.callsign,

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c
                                        WHERE s.vehicle_id IN(4,14,68,152,146,87,126,89,96,147,206,200,198,73,81,5,67,15,129,153,107,250,256,84,257)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.tankquery_LINE_);

/* Daily Top Tanks by points -- point formula = KILLS * KD    --- FOR ALLIED SIDE*/
$dttank = mysqli_query($dbConnCommunity, "SELECT w.callsign,

                                        IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid
                                        WHERE s.vehicle_id IN(4,14,68,152,146,87,126,89,96,147,206,200,198,73,81,5,67,15,129,153,107,250,256,84,257)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.tankquery_LINE_);

/* Top Freighter by points -- point formula = (RTB % * Sorties)    --- FOR ALLIED SIDE*/
$ttt = mysqli_query($dbConnCommunity, "SELECT w.callsign,

                                        ((SUM(CASE WHEN s.rtb = '0' THEN 1 ELSE 0 END)/COUNT(s.player_id)) * COUNT(CASE WHEN s.player_id = '*' THEN 1 ELSE 0 END))  as points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c
                                        WHERE s.vehicle_id IN(100,101)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.ttquery_LINE_);

/* Daily Top Freighter by points -- point formula = (RTB % * Sorties)    --- FOR ALLIED SIDE*/
$dttt = mysqli_query($dbConnCommunity, "SELECT w.callsign,

                                        ((SUM(CASE WHEN s.rtb = '0' THEN 1 ELSE 0 END)/COUNT(s.player_id)) * COUNT(CASE WHEN s.player_id = '*' THEN 1 ELSE 0 END))  as points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid
                                        WHERE s.vehicle_id IN(100,101)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.ttquery_LINE_);

/* Allied Total Kills for Current Campaign */
$allykills = mysqli_query($dbConnCommunity, "SELECT callsign,
                                            SUM(p.kills) AS kills

                                        FROM scoring_campaign_personas AS p
                                        LEFT JOIN scoring_persona_configs c ON (p.persona_id = c.persona_id), wwiionline.wwii_persona, wwiionline.wwii_player
                                        WHERE wwiionline.wwii_persona.personaid = p.persona_id
                                            AND wwiionline.wwii_player.playerid = wwiionline.wwii_persona.playerid
                                            AND wwiionline.wwii_persona.countryid !='4'
                                            AND (ISNULL(c.bans) OR c.bans = 0)
                                        GROUP BY callsign ORDER BY kills DESC LIMIT 100") or die ($dbConnCommunity->error.allykills);

/* Allied Kills for last 24 hour */
$dallykills = mysqli_query($dbConnCommunity, "SELECT callsign,
                                            SUM(s.kills) AS kills
                                        FROM scoring_campaign_sorties AS s
                                        LEFT JOIN scoring_persona_configs c ON (s.persona_id = c.persona_id), wwiionline.wwii_persona, wwiionline.wwii_player
                                        WHERE DATE(s.`sortie_start`) >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                            AND wwiionline.wwii_persona.personaid = s.persona_id
                                            AND wwiionline.wwii_player.playerid = wwiionline.wwii_persona.playerid
                                            AND wwiionline.wwii_persona.countryid !='4'
                                            AND (ISNULL(c.bans) OR c.bans = 0) GROUP BY callsign ORDER BY kills DESC LIMIT 100") or die ($dbConnCommunity->error.dallykills);

/*  top captures info */
$allycaps = mysqli_query($dbConnCommunity, "SELECT callsign,
                                            SUM(p.captures) AS caps

                                       FROM scoring_campaign_personas AS p
                                       LEFT JOIN scoring_persona_configs c ON (p.persona_id = c.persona_id), wwiionline.wwii_persona, wwiionline.wwii_player
                                       WHERE wwiionline.wwii_persona.personaid = p.persona_id
                                            AND wwiionline.wwii_player.playerid = wwiionline.wwii_persona.playerid
                                            AND wwiionline.wwii_persona.countryid !='4'
                                            AND (ISNULL(c.bans) OR c.bans = 0)
                                       GROUP BY callsign ORDER BY caps DESC LIMIT 100") or die ($dbConnCommunity->error.allycaps);

/* Caps for last 24 hours */
$dallycaps = mysqli_query($dbConnCommunity, "SELECT callsign, SUM(s.captures) AS caps
                                        FROM scoring_campaign_sorties AS s
                                        LEFT JOIN scoring_persona_configs c ON (s.persona_id = c.persona_id),wwiionline.wwii_persona, wwiionline.wwii_player
                                        WHERE DATE(s.`sortie_start`) >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                            AND wwiionline.wwii_persona.personaid = s.persona_id
                                            AND wwiionline.wwii_player.playerid = wwiionline.wwii_persona.playerid
                                            AND wwiionline.wwii_persona.countryid !='4'
                                            AND (ISNULL(c.bans) OR c.bans = 0)
                                        GROUP BY callsign ORDER BY caps DESC LIMIT 100") or die ($dbConnCommunity->error.dallycaps);

/* Kills/Death (Kd) */
$allykd = mysqli_query($dbConnCommunity, "SELECT callsign, SUM(p.kills) as kills, SUM(p.deaths) as deaths,
                                        SUM(p.kd) as kd
                                     FROM scoring_campaign_personas p
                                     LEFT JOIN scoring_persona_configs c ON (p.persona_id = c.persona_id), wwiionline.wwii_persona, wwiionline.wwii_player
                                     WHERE wwiionline.wwii_persona.personaid = p.persona_id
                                            AND wwiionline.wwii_player.playerid = wwiionline.wwii_persona.playerid
                                            AND wwiionline.wwii_persona.countryid !='4'
                                            AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY callsign ORDER BY kd DESC LIMIT 100") or die ($dbConnCommunity->error.allykd);

/* Top Truck by points -- point formula = (RTB % * Sorties)    --- FOR ALLIED SIDE*/
$ttruck = mysqli_query($dbConnCommunity, "SELECT w.callsign,
                                        COUNT(CASE WHEN s.player_id = '*' THEN 1 ELSE 0 END) as sortie,
                                        ((SUM(CASE WHEN s.rtb = '0' THEN 1 ELSE 0 END)/COUNT(s.player_id)) * COUNT(CASE WHEN s.player_id = '*' THEN 1 ELSE 0 END))  as points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c
                                        WHERE s.vehicle_id IN(16,6,190,77,83,258,110)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.truckquery_LINE_);

/* Daily Top Truck by points -- point formula = (RTB % * Sorties)    --- FOR ALLIED SIDE*/
$dttruck = mysqli_query($dbConnCommunity, "SELECT w.callsign,
                                        COUNT(CASE WHEN s.player_id = '*' THEN 1 ELSE 0 END) as sortie,
                                        IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid
                                        WHERE s.vehicle_id IN(16,6,190,77,83,258,110)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.truckquery_LINE_);

/* Top fighter by points -- point formula = KILLS * KD    --- FOR ALLIED SIDE*/
$tfight = mysqli_query($dbConnCommunity, "SELECT w.callsign,

                                            SUM(s.kills)*ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c
                                        WHERE s.vehicle_id IN(72,86,94,158,11,2,13,159,204,131,12,1,90,138,91,182,185,108)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.bomber_LINE_);

/* Daliy Top fighter by points -- point formula = KILLS * KD    --- FOR ALLIED SIDE*/
$dtfight = mysqli_query($dbConnCommunity, "SELECT w.callsign,

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid
                                        WHERE s.vehicle_id IN(72,86,94,158,11,2,13,159,204,131,12,1,90,138,91,182,185,108)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.bomber_LINE_);

/* Top bomber by points -- point formula = KILLS * KD    --- FOR ALLIED SIDE*/
$tbomb = mysqli_query($dbConnCommunity, "SELECT w.callsign,

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c
                                        WHERE s.vehicle_id IN(65,70,95,97,139,249)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.bomber_LINE_);

/* Daily Top bomber by points -- point formula = KILLS * KD    --- FOR ALLIED SIDE*/
$dtbomb = mysqli_query($dbConnCommunity, "SELECT w.callsign,

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid
                                        WHERE s.vehicle_id IN(65,70,95,97,139,249)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.bomber_LINE_);

/* Top Destroyer by points -- point formula = KILLS * KD    --- FOR ALLIED SIDE*/
$tdd = mysqli_query($dbConnCommunity, "SELECT w.callsign,

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c
                                        WHERE s.vehicle_id IN(98,99)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.dd_LINE_);

/* Daliy Top Destroyer by points -- point formula = KILLS * KD    --- FOR ALLIED SIDE*/
$dtdd = mysqli_query($dbConnCommunity, "SELECT w.callsign,

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid
                                        WHERE s.vehicle_id IN(98,99)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.dd_LINE_);

/* Top PatrolBoats by points -- point formula = KILLS * KD    --- FOR ALLIED SIDE*/
$tpb = mysqli_query($dbConnCommunity, "SELECT w.callsign,

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c
                                        WHERE s.vehicle_id IN(62,63)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.pb_LINE_);

/* Daily Top PatrolBoats by points -- point formula = KILLS * KD    --- FOR ALLIED SIDE*/
$dtpb = mysqli_query($dbConnCommunity, "SELECT w.callsign,

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid
                                        WHERE s.vehicle_id IN(62,63)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.pb_LINE_);


###########################################
######### AXIS QUERIES ####################
###########################################

/* Axis MOST Time On Mission */
$axtom = mysqli_query($dbConnCommunity, "SELECT callsign, p.tom AS tom
                                      FROM scoring_campaign_personas p
                                      LEFT JOIN scoring_persona_configs c ON (p.persona_id = c.persona_id), wwiionline.wwii_persona, wwiionline.wwii_player
                                      WHERE wwiionline.wwii_persona.personaid = p.persona_id
                                        AND wwiionline.wwii_player.playerid = wwiionline.wwii_persona.playerid
                                        AND wwiionline.wwii_persona.countryid ='4'
                                        AND (isnull(c.bans) OR c.bans = 0)
                                      GROUP BY callsign ORDER BY tom DESC LIMIT 100") or die ($dbConnCommunity->error.tomget_LINE_);

/* Axis MOST Timeon Mission Last 24 Hours */
$daxtom = mysqli_query($dbConnCommunity, "SELECT callsign, SUM(s.tom) AS tom
                                       FROM scoring_campaign_sorties s
                                       LEFT JOIN scoring_persona_configs c ON (s.persona_id = c.persona_id), wwiionline.wwii_persona, wwiionline.wwii_player
                                       WHERE DATE(s.sortie_start) >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                            AND wwiionline.wwii_persona.personaid = s.persona_id
                                            AND wwiionline.wwii_player.playerid = wwiionline.wwii_persona.playerid
                                            AND wwiionline.wwii_persona.countryid ='4'
                                            AND (isnull(c.bans) OR c.bans = 0)
                                       GROUP BY callsign ORDER BY tom DESC LIMIT 100") or die ($dbConnCommunity->error.dtomget_LINE_);

/* Top Kill Streak -- Number of consecutive Sorties with at least one kill.    --- FOR Axis SIDE*/
$gtkrow = mysqli_query($dbConnCommunity, "SELECT callsign, streak_id, best as value2, UNIX_TIMESTAMP(achieved), v.name, 0, 0
                                    FROM scoring_vehicles v, scoring_campaign_streak_bests s
                                    LEFT JOIN scoring_persona_configs c ON (s.persona_id = c.persona_id), wwiionline.wwii_player, wwiionline.wwii_persona
                                    WHERE v.vehicle_id=s.vehicle_id
                                        AND wwiionline.wwii_persona.personaid = s.persona_id
                                        AND wwiionline.wwii_player.playerid = wwiionline.wwii_persona.playerid
                                        AND s.streak_id = 4
                                        AND wwiionline.wwii_persona.countryid ='4'
                                        AND (isnull(c.bans) OR c.bans = 0) ORDER BY value2 DESC LIMIT 100") or die ($dbConnCommunity->error.tkrowquery_LINE_);

/* Daily Top Kill Streak -- Top players based on the Consecutive Sorties with a Kill streak.    --- FOR Axis SIDE*/
$gdtkrow = mysqli_query($dbConnCommunity, "SELECT DISTINCT(callsign) as callsign, streak_id, current as value2
                                    FROM scoring_campaign_streaks s, wwiionline.wwii_player, wwiionline.wwii_persona
                                    WHERE s.streak_id = 4
                                        AND s.achieved >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                        AND wwiionline.wwii_persona.personaid = s.persona_id
                                        AND wwiionline.wwii_player.playerid = wwiionline.wwii_persona.playerid
                                        ORDER BY value2 DESC LIMIT 100") or die ($dbConnCommunity->error.tkrowquery_LINE_);

/* Top Capture Streak -- Most sorties in a row with a capture */
$gtcrow = mysqli_query($dbConnCommunity, "SELECT callsign, streak_id, best as value2, UNIX_TIMESTAMP(achieved), v.name, 0, 0
                                    FROM scoring_vehicles v, scoring_campaign_streak_bests s
                                    LEFT JOIN scoring_persona_configs c ON (s.persona_id = c.persona_id), wwiionline.wwii_player, wwiionline.wwii_persona
                                    WHERE v.vehicle_id=s.vehicle_id
                                        AND wwiionline.wwii_persona.personaid = s.persona_id
                                        AND wwiionline.wwii_player.playerid = wwiionline.wwii_persona.playerid
                                        AND s.streak_id = 5
                                        AND wwiionline.wwii_persona.countryid ='4'
                                        AND (isnull(c.bans) OR c.bans = 0) ORDER BY value2 DESC LIMIT 100") or die ($dbConnCommunity->error.tkrowquery_LINE_);

/* Daily Top Capture Streak -- Most sorties in a row with a capture */
$gdtcrow = mysqli_query($dbConnCommunity, "SELECT DISTINCT(callsign) as callsign, streak_id, current as value2
                                    FROM scoring_campaign_streaks s, wwiionline.wwii_player, wwiionline.wwii_persona
                                    WHERE s.streak_id = 5
                                        AND s.achieved >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                        AND wwiionline.wwii_persona.personaid = s.persona_id
                                        AND wwiionline.wwii_player.playerid = wwiionline.wwii_persona.playerid
                                        ORDER BY value2 DESC LIMIT 100") or die ($dbConnCommunity->error.tkrowquery_LINE_);

/* Axis Total Kills for Current Campaign */
$axkills = mysqli_query($dbConnCommunity, "SELECT callsign,
                                            SUM(p.kills) AS kills

                                        FROM scoring_campaign_personas AS p
                                        LEFT JOIN scoring_persona_configs c ON (p.persona_id = c.persona_id), wwiionline.wwii_persona, wwiionline.wwii_player
                                        WHERE wwiionline.wwii_persona.personaid = p.persona_id
                                            AND wwiionline.wwii_player.playerid = wwiionline.wwii_persona.playerid
                                            AND wwiionline.wwii_persona.countryid ='4'
                                            AND (ISNULL(c.bans) OR c.bans = 0)
                                        GROUP BY callsign ORDER BY kills DESC LIMIT 100") or die ($dbConnCommunity->error.kills_LINE_);

/* Axis Kills for last 24 hour */
$daxkills = mysqli_query($dbConnCommunity, "SELECT callsign,
                                            SUM(s.kills) AS kills
                                        FROM scoring_campaign_sorties AS s
                                        LEFT JOIN scoring_persona_configs c ON (s.persona_id = c.persona_id), wwiionline.wwii_persona, wwiionline.wwii_player
                                        WHERE DATE(s.`sortie_start`) >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                            AND wwiionline.wwii_persona.personaid = s.persona_id
                                            AND wwiionline.wwii_player.playerid = wwiionline.wwii_persona.playerid
                                            AND wwiionline.wwii_persona.countryid ='4'
                                            AND (ISNULL(c.bans) OR c.bans = 0) GROUP BY callsign ORDER BY kills DESC LIMIT 100") or die ($dbConnCommunity->error.kills_LINE_);

/*  top captures info */
$axcaps = mysqli_query($dbConnCommunity, "SELECT callsign,
                                            SUM(p.captures) AS caps

                                       FROM scoring_campaign_personas AS p
                                       LEFT JOIN scoring_persona_configs c ON (p.persona_id = c.persona_id), wwiionline.wwii_persona, wwiionline.wwii_player
                                       WHERE wwiionline.wwii_persona.personaid = p.persona_id
                                            AND wwiionline.wwii_player.playerid = wwiionline.wwii_persona.playerid
                                            AND wwiionline.wwii_persona.countryid ='4'
                                            AND (ISNULL(c.bans) OR c.bans = 0)
                                       GROUP BY callsign ORDER BY caps DESC LIMIT 100") or die ($dbConnCommunity->error.caps_LINE_);

/* Caps for last 24 hours */
$daxcaps = mysqli_query($dbConnCommunity, "SELECT callsign, SUM(s.captures) AS caps
                                        FROM scoring_campaign_sorties AS s
                                        LEFT JOIN scoring_persona_configs c ON (s.persona_id = c.persona_id),wwiionline.wwii_persona, wwiionline.wwii_player
                                        WHERE DATE(s.`sortie_start`) >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                            AND wwiionline.wwii_persona.personaid = s.persona_id
                                            AND wwiionline.wwii_player.playerid = wwiionline.wwii_persona.playerid
                                            AND wwiionline.wwii_persona.countryid ='4'
                                            AND (ISNULL(c.bans) OR c.bans = 0)
                                        GROUP BY callsign ORDER BY caps DESC LIMIT 100") or die ($dbConnCommunity->error.dcaps_LINE_);

/* Kills/Death (Kd) */
$axkd = mysqli_query($dbConnCommunity, "SELECT callsign, SUM(p.kills) as kills, SUM(p.deaths) as deaths,
                                        SUM(p.kd) as kd
                                     FROM scoring_campaign_personas p
                                     LEFT JOIN scoring_persona_configs c ON (p.persona_id = c.persona_id), wwiionline.wwii_persona, wwiionline.wwii_player
                                     WHERE wwiionline.wwii_persona.personaid = p.persona_id
                                            AND wwiionline.wwii_player.playerid = wwiionline.wwii_persona.playerid
                                            AND wwiionline.wwii_persona.countryid ='4'
                                            AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY callsign ORDER BY kd DESC LIMIT 100") or die ($dbConnCommunity->error.kdget_LINE_);

/* K/D For last 24 Hours */
$daxkd = mysqli_query($dbConnCommunity, "SELECT callsign,
                                        ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2) AS kd
                                      FROM scoring_campaign_sorties s
                                      LEFT JOIN scoring_persona_configs c ON (s.persona_id = c.persona_id), wwiionline.wwii_persona, wwiionline.wwii_player
                                      WHERE wwiionline.wwii_persona.personaid = s.persona_id
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                        AND wwiionline.wwii_player.playerid = wwiionline.wwii_persona.playerid
                                        AND wwiionline.wwii_persona.countryid ='4'
                                        AND (isnull(c.bans) OR c.bans = 0)
                                      GROUP BY callsign ORDER BY kd DESC LIMIT 100") or die ($dbConnCommunity->error.kdget_LINE_);

/* Top Destroyer by points -- point formula = KILLS * KD    --- FOR Axis SIDE*/
$gtdd = mysqli_query($dbConnCommunity, "SELECT w.callsign,

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c
                                        WHERE s.vehicle_id IN(80)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.dd_LINE_);

/* Daliy Top Destroyer by points -- point formula = KILLS * KD    --- FOR Axis SIDE*/
$gdtdd = mysqli_query($dbConnCommunity, "SELECT w.callsign,

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid
                                        WHERE s.vehicle_id IN(80)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.dd_LINE_);

/* Top PatrolBoats by points -- point formula = KILLS * KD    --- FOR Axis SIDE*/
$gtpb = mysqli_query($dbConnCommunity, "SELECT w.callsign,

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c
                                        WHERE s.vehicle_id IN(64)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.pb_LINE_);

/* Daily Top PatrolBoats by points -- point formula = KILLS * KD    --- FOR Axis SIDE*/
$gdtpb = mysqli_query($dbConnCommunity, "SELECT w.callsign,

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid
                                        WHERE s.vehicle_id IN(64)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.pb_LINE_);

/* Top Freighter by points -- point formula = (RTB % * Sorties)    --- FOR Axis SIDE*/
$gttt = mysqli_query($dbConnCommunity, "SELECT w.callsign,

                                        ((SUM(CASE WHEN s.rtb = '0' THEN 1 ELSE 0 END)/COUNT(s.player_id)) * COUNT(CASE WHEN s.player_id = '*' THEN 1 ELSE 0 END))  as points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c
                                        WHERE s.vehicle_id IN(102)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.ttquery_LINE_);

/* Daily Top Freighter by points -- point formula = (RTB % * Sorties)    --- FOR Axis SIDE*/
$gdttt = mysqli_query($dbConnCommunity, "SELECT w.callsign,

                                        ((SUM(CASE WHEN s.rtb = '0' THEN 1 ELSE 0 END)/COUNT(s.player_id)) * COUNT(CASE WHEN s.player_id = '*' THEN 1 ELSE 0 END))  as points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid
                                        WHERE s.vehicle_id IN(102)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.ttquery_LINE_);

/* Top Truck by points -- point formula = (RTB % * Sorties)    --- FOR Axis SIDE*/
$gtruck = mysqli_query($dbConnCommunity, "SELECT w.callsign,
                                        COUNT(CASE WHEN s.player_id = '*' THEN 1 ELSE 0 END) as sortie,
                                        ((SUM(CASE WHEN s.rtb = '0' THEN 1 ELSE 0 END)/COUNT(s.player_id)) * COUNT(CASE WHEN s.player_id = '*' THEN 1 ELSE 0 END))  as points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c
                                        WHERE s.vehicle_id IN(27,28,103)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.truckquery_LINE_);

/* Daily Top Truck by points -- point formula = (RTB % * Sorties)    --- FOR Axis SIDE*/
$gdttruck = mysqli_query($dbConnCommunity, "SELECT w.callsign,
                                        COUNT(CASE WHEN s.player_id = '*' THEN 1 ELSE 0 END) as sortie,
                                        IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid
                                        WHERE s.vehicle_id IN(27,28,103)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.truckquery_LINE_);

/* Top fighter by points -- point formula = KILLS * KD    --- FOR Axis SIDE*/
$gfight = mysqli_query($dbConnCommunity, "SELECT w.callsign,

                                            SUM(s.kills)*ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c
                                        WHERE s.vehicle_id IN(23,66,92,137,157,181,184,183,186)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.bomber_LINE_);

/* Daliy Top fighter by points -- point formula = KILLS * KD    --- FOR Axis SIDE*/
$gdtfight = mysqli_query($dbConnCommunity, "SELECT w.callsign,

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid
                                        WHERE s.vehicle_id IN(23,66,92,137,157,181,184,183,186)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.bomber_LINE_);

/* Top bomber by points -- point formula = KILLS * KD    --- FOR Axis SIDE*/
$gtbomb = mysqli_query($dbConnCommunity, "SELECT w.callsign,

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c
                                        WHERE s.vehicle_id IN(22,76,93)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.bomber_LINE_);

/* Daily Top bomber by points -- point formula = KILLS * KD    --- FOR Axis SIDE*/
$gdtbomb = mysqli_query($dbConnCommunity, "SELECT w.callsign,

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid
                                        WHERE s.vehicle_id IN(76,22,93)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.bomber_LINE_);


/* Top LMG by points -- point formula = (KILLS + (CAPS * 2)) * KD    --- FOR Axis SIDE*/
$gtlmg = mysqli_query($dbConnCommunity, "SELECT w.callsign,

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+(SUM(s.captures)*2)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c
                                        WHERE s.vehicle_id IN(106,177)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.lmgquery_LINE_);

/* Daily  Top LMG by points -- point formula = (KILLS + (CAPS * 2)) * KD    --- FOR Axis SIDE*/
$gdtlmg = mysqli_query($dbConnCommunity, "SELECT w.callsign,

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+(SUM(s.captures)*2)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid
                                        WHERE s.vehicle_id IN(106,177)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.lmgquery_LINE_);

/* Top Mortar by points -- point formula = (KILLS + (CAPS * 2)) * KD    --- FOR Axis SIDE*/
$gtmort = mysqli_query($dbConnCommunity, "SELECT w.callsign,
                                        IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+(SUM(s.captures)*2)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c
                                        WHERE s.vehicle_id IN(160,180)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.mortquery_LINE_);

/* Daily Top Mortar by points -- point formula = (KILLS + (CAPS * 2)) * KD    --- FOR Axis SIDE*/
$gdtmort = mysqli_query($dbConnCommunity, "SELECT w.callsign,

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+(SUM(s.captures)*2)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid
                                        WHERE s.vehicle_id IN(160,180)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.mortquery_LINE_);

/* Top Rifleman by points -- point formula = (KILLS + (CAPS * 5)) * KD    --- FOR Axis SIDE*/
$gtrifle = mysqli_query($dbConnCommunity, "SELECT w.callsign,

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+(SUM(s.captures)*5)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c
                                        WHERE s.vehicle_id IN(31,175,194,263,277,279)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.testriflequery_LINE_);

/* Daily Top Rifleman by points -- point formula = (KILLS + (CAPS * 5)) * KD    --- FOR Axis SIDE*/
$gdtrifle = mysqli_query($dbConnCommunity, "SELECT w.callsign,

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+(SUM(s.captures)*5)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid
                                        WHERE s.vehicle_id IN(31,175,194,263,277,279)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.testriflequery_LINE_);

/* Top SMG by points -- point formula = (KILLS + (CAPS * 5)) * KD    --- FOR Axis SIDE*/
$gtsmg = mysqli_query($dbConnCommunity, "SELECT w.callsign,

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+(SUM(s.captures)*5)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c
                                        WHERE s.vehicle_id IN(275,276,32,176)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.smgquery_LINE_);

/* Daliy Top SMG by points -- point formula = (KILLS + (CAPS * 5)) * KD    --- FOR Axis SIDE*/
$gdtsmg = mysqli_query($dbConnCommunity, "SELECT w.callsign,

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+(SUM(s.captures)*5)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid
                                        WHERE s.vehicle_id IN(275,276,32,176)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.smgquery_LINE_);

/* Top Sniper by points -- point formula = (KILLS + CAPS )) * KD    --- FOR Axis SIDE*/
$gtsniper = mysqli_query($dbConnCommunity, "SELECT w.callsign,

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+SUM(s.captures)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c
                                        WHERE s.vehicle_id IN(179,150)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.atr_LINE_);

/* Daily Top Sniper by points -- point formula = (KILLS + CAPS )) * KD    --- FOR Axis SIDE*/
$gdtsniper = mysqli_query($dbConnCommunity, "SELECT w.callsign,

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+SUM(s.captures)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid
                                        WHERE s.vehicle_id IN(179,150)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.atr_LINE_);

/* Top Tanks by points -- point formula = KILLS * KD    --- FOR Axis SIDE*/
$gtank = mysqli_query($dbConnCommunity, "SELECT w.callsign,

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c
                                        WHERE s.vehicle_id IN(25,26,24,88,75,82,103,74,127,128,144)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.tankquery_LINE_);

/* Daily Top Tanks by points -- point formula = KILLS * KD    --- FOR Axis SIDE*/
$gdttank = mysqli_query($dbConnCommunity, "SELECT w.callsign,

                                        IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid
                                        WHERE s.vehicle_id IN(25,26,24,88,75,82,103,74,127,128,144)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.tankquery_LINE_);

/* Top AAA by points -- point formula = KILLS * KD    --- FOR Axis SIDE -- Need Sortie counts*/
$gtaa = mysqli_query($dbConnCommunity, "SELECT w.callsign,
                                        IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                  FROM scoring_campaign_sorties s
                                        JOIN wwiionline.wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c
                                            WHERE s.vehicle_id IN(111, 71)
                                            AND s.persona_id=c.persona_id
                                            AND (isnull(c.bans) OR c.bans = 0)
                                  GROUP BY w.callsign ORDER BY points DESC limit 100") or die ($dbConnCommunity->error.gtaa);

/* Daily Top AAA by points -- point formula = KILLS * KD    --- FOR Axis SIDE -- Need Sortie counts*/
$gdtaa = mysqli_query($dbConnCommunity, "SELECT w.callsign,
                                        SUM(s.kills) as kills,
                                        IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                   FROM scoring_campaign_sorties s
                                        JOIN wwiionline.wwii_player w ON s.player_id=w.playerid
                                            WHERE s.vehicle_id IN(111,71)
                                            AND s.`sortie_start` > DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                   GROUP BY w.callsign ORDER BY points DESC limit 100") or die ($dbConnCommunity->error.$gdtaa);

/* Top ATG by points -- point formula = KILLS * KD    --- FOR Axis SIDE -- Need Sortie counts*/
$gtatg = mysqli_query($dbConnCommunity, "SELECT w.callsign,
                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c
                                        WHERE s.vehicle_id IN(30, 130, 29, 166,125,155)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.tatg_LINE_);

/* Daily Top ATG by points -- point formula = KILLS * KD    --- FOR Axis SIDE -- Need Sortie counts*/
$gdtatg = mysqli_query($dbConnCommunity, "SELECT w.callsign,
                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, SUM(s.kills) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid
                                        WHERE s.vehicle_id IN(30, 130, 29, 166,125,155)
                                        AND s.`sortie_start` > DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.datg_LINE_);

/* Top ATR by points -- point formula = (KILLS + CAPS )) * KD    --- FOR Axis SIDE*/
$gtatr = mysqli_query($dbConnCommunity, "SELECT w.callsign,
                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+SUM(s.captures)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c
                                        WHERE s.vehicle_id IN(122,178,195)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.atr_LINE_);

/* Daily Top ATR by points -- point formula = (KILLS + CAPS )) * KD    --- FOR Axis SIDE*/
$gdtatr = mysqli_query($dbConnCommunity, "SELECT w.callsign,
                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+SUM(s.captures)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid
                                        WHERE s.vehicle_id IN(122,178,195)
                                        AND s.`sortie_start` > DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.datr_LINE_);

/* Top Engineer by points -- point formula = (KILLS + (CAPS * 5)) * KD    --- FOR Axis SIDE*/
$gteng = mysqli_query($dbConnCommunity, "SELECT w.callsign,
                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+(SUM(s.captures)*5)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c
                                        WHERE s.vehicle_id IN(33,267)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.engquery_LINE_);

/* Daily Top Engineer by points -- point formula = (KILLS + (CAPS * 5)) * KD    --- FOR Axis SIDE*/
$gdteng = mysqli_query($dbConnCommunity, "SELECT w.callsign,
                                              IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+(SUM(s.captures)*5)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid
                                        WHERE s.vehicle_id IN(33,267)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.dengquery_LINE_);

/* Top Grenadier by points -- point formula = (KILLS + CAPS )) * KD    --- FOR Axis SIDE*/
$gtgren = mysqli_query($dbConnCommunity, "SELECT w.callsign,

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+SUM(s.captures)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid, scoring_persona_configs c
                                        WHERE s.vehicle_id IN(114)
                                        AND s.persona_id=c.persona_id
                                        AND (isnull(c.bans) OR c.bans = 0)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.grenadierquery_LINE_);

/* Daily Top Grenadier by points -- point formula = (KILLS + CAPS )) * KD    --- FOR Axis SIDE*/
$gdtgren = mysqli_query($dbConnCommunity, "SELECT w.callsign,

                                            IF(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)>0, (SUM(s.kills)+SUM(s.captures)) * ROUND(SUM(s.kills)/(SUM(CASE s.rtb WHEN 3 THEN 1 ELSE 0 END)),2), SUM(s.kills)) AS points
                                    FROM scoring_campaign_sorties s
                                    JOIN wwiionline.wwii_player w ON s.player_id=w.playerid
                                        WHERE s.vehicle_id IN(114)
                                        AND s.`sortie_start` >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                    GROUP BY w.callsign ORDER BY `points` DESC limit 100") or die ($dbConnCommunity->error.grenadierquery_LINE_);


##########################################################
###################### TRUNCATE TABLE ####################
##########################################################

/* Truncate table to delete existing data */
mysqli_query($dbconn, "TRUNCATE scoring_top_players");

/* INPUT DATA TO Gazette.Scoring_top_players */

/* TOP AAA */
while ($row = $taa->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, aaacallsign, aaapoints) VALUES ('1','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.taa_LINE_);  }

/*Daily Top AAA */
while ($row = $dtaa->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, aaacallsign, aaapoints) VALUES ('1','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.dtaa_LINE_);  }

/* TOP ATG */
while ($row = $tatg->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, atgcallsign, atgpoints) VALUES ('1','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.tatg_LINE_); }

/* DAILY TOP ATG */
while ($row = $dtatg->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, atgcallsign, atgpoints) VALUES ('1','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.datg_LINE_);}

/* TOP ATR */
while ($row = $tatr->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, atrcallsign, atrpoints) VALUES ('1','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.atrinsert_LINE_); }

/* DAILY TOP ATR */
while ($row = $dtatr->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, atrcallsign, atrpoints) VALUES ('1','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.atrinsert_LINE_); }

/* TOP Engineer */
while ($row = $teng->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, engcallsign, engpoints) VALUES ('1','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.enginsert_LINE_);  }

/* DAILY TOP Engineer */
while ($row = $dteng->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, engcallsign, engpoints) VALUES ('1','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.enginsert_LINE_);  }

/* TOP GRENADIER */
while ($row = $tgren->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,grencallsign, grenpoints) VALUES ('1','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.grenadierinsert_LINE_); }

/* DAILY TOP GRENADIER */
while ($row = $dtgren->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,grencallsign, grenpoints) VALUES ('1','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.grenadierinsert_LINE_); }

/* TOP LMG */
while ($row = $tlmg->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,lmgcallsign, lmgpoints) VALUES ('1','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.tlmginsert_LINE_); }

/* DAILY TOP LMG */
while ($row = $dtlmg->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,lmgcallsign, lmgpoints) VALUES ('1','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.tlmginsert_LINE_); }

/* TOP MORTAR */
while ($row = $tmort->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,mortcallsign, mortpoints) VALUES ('1','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.tmortinsert_LINE_); }

/* DAILY TOP MORTAR */
while ($row = $dtmort->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,mortcallsign, mortpoints) VALUES ('1','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.tmortinsert_LINE_); }

/* TOP RIFLE */
while ($row = $trifle->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,rcallsign, rpoints) VALUES ('1','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.trifleinsert_LINE_); }

/* DAILY TOP RIFLE */
while ($row = $dtrifle->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,rcallsign, rpoints) VALUES ('1','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.trifleinsert_LINE_); }

/* TOP SMG */
while ($row = $tsmg->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,smgcallsign, smgpoints) VALUES ('1','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.smginsert_LINE_); }

/* DAILY TOP SMG */
while ($row = $dtsmg->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,smgcallsign, smgpoints) VALUES ('1','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.smginsert_LINE_); }

/* TOP SNIPER */
while ($row = $tsniper->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,snipercallsign, sniperpoints) VALUES ('1','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.atrinsert_LINE_); }

/* DAILY TOP SNIPER */
while ($row = $dtsniper->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,snipercallsign, sniperpoints) VALUES ('1','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.atrinsert_LINE_); }

/* TOP TANK */
while ($row = $ttank->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,tankcallsign, tankpoints) VALUES ('1','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.tankinsert_LINE_); }

/* DAILY TOP TANK */
while ($row = $dttank->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,tankcallsign, tankpoints) VALUES ('1','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.tankinsert_LINE_); }

/* TOP TRUCK */
while ($row = $ttruck->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, truckcallsign, truckpoints) VALUES ('1','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.ttruckinsert_LINE_); }

/* DAILY TOP TRUCK */
while ($row = $dttruck->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, truckcallsign, truckpoints) VALUES ('1','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.ttruckinsert_LINE_); }

/* TOP FIGHTER */
while ($row = $tfight->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,fightcallsign, fightpoints) VALUES ('1','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.fightinsert_LINE_); }

/* DAILY TOP FIGHTER */
while ($row = $dtfight->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,fightcallsign, fightpoints) VALUES ('1','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.fightinsert_LINE_); }

/* TOP BOMBER */
while ($row = $tbomb->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,bombcallsign, bombpoints) VALUES ('1','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.bomberinsert_LINE_); }

/* DAILY TOP BOMBER */
while ($row = $dtbomb->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,bombcallsign, bombpoints) VALUES ('1','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.bomberinsert_LINE_); }

/* TOP DESTROYER */
while ($row = $tdd->fetch_assoc())
{ mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,ddcallsign, ddpoints) VALUES ('1','camp','".$row['callsign']."','".$row['points']."')"); }
/* DAILY TOP DESTROYER */
while ($row = $dtdd->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,ddcallsign, ddpoints) VALUES ('1','day','".$row['callsign']."','".$row['points']."')") ; }

/* TOP PATROL BOATS */
while ($row = $tpb->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,pbcallsign, pbpoints) VALUES ('1','camp','".$row['callsign']."','".$row['points']."')") ; }

/* DAILY TOP PATROL BOATS */
while ($row = $dtpb->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,pbcallsign, pbpoints) VALUES ('1','day','".$row['callsign']."','".$row['points']."')") ; }

/* TOP FREIGHTER (Transport (tt)) */
while ($row = $ttt->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,ttcallsign, ttpoints) VALUES ('1','camp','".$row['callsign']."','".$row['points']."')") ; }
/* DAILY TOP FREIGHTER  */
while ($row = $dttt->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,ttcallsign, ttpoints) VALUES ('1','day','".$row['callsign']."','".$row['points']."')") ; }

/* TOP KILLS  */
while ($row = $allykills->fetch_assoc())
    {mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,killscallsign,kills) VALUES ('1','camp','".$row['callsign']."','".$row['kills']."')") or die ($dbconn->error.allykills); }

/* DAILY TOP KILLS */
while ($row = $dallykills->fetch_assoc())
    {mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,killscallsign,kills) VALUES ('1','day','".$row['callsign']."','".$row['kills']."')") or die ($dbconn->error.dallykills);  }

/* MOST CAPTURES */
/*
while ($row = $allycaps->fetch_assoc())
    {mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,capscallsign,caps) VALUES ('1','camp','".$row['callsign']."','".$row['caps']."')") or die ($dbconn->error.allycaps); }
    */

/* DAILY MOST CAPTURES */
// while ($row = $dallycaps->fetch_assoc())
//     {mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,capscallsign,caps) VALUES ('1','day','".$row['callsign']."','".$row['caps']."')") or die ($dbconn->error.dallycaps);  }

/* Top K/D */
while ($row = $allykd->fetch_assoc())
    {mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,kdcallsign,kd) VALUES ('1','camp','".$row['callsign']."','".$row['kd']."')") or die ($dbconn->error.allykd); }

/* Last 24 hour K/D Allied */
while ($row = $dallykd->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,kdcallsign,kd) VALUES ('1','day','".$row['callsign']."','".$row['kd']."')") or die ($dbconn->error.kdinsert_LINE_); }

/* TIME ON MISSION */
while ($row = $allytom->fetch_assoc())
    {mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,tomcallsign,tom) VALUES ('1','camp','".$row['callsign']."','".$row['tom']."')") or die ($dbconn->error.tominsert_LINE_); }

/*Allied most time on mission - last 24 hours */
while ($row = $dallytom->fetch_assoc())
    {mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,tomcallsign,tom) VALUES ('1','day','".$row['callsign']."','".$row['tom']."')") or die ($dbconn->error.dtominsert_LINE_); }

/* Most consecutive Sorties with a kill */
while ($row = $tkrow->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,krowcallsign, krowpoints) VALUES ('1','camp','".$row['callsign']."','".$row['value2']."')") or die ($dbconn->error.tkrowinsert_LINE_);}

/* DAILY CONSECUTIVE SORTIES WITH A KILL */
while ($row = $dtkrow->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,krowcallsign, krowpoints) VALUES ('1','day','".$row['callsign']."','".$row['value2']."')") or die ($dbconn->error.tkrowinsert_LINE_); }

/* SORTIES IN A ROW WITH A CAPTURE */
while ($row = $tcrow->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,crowcallsign, crowpoints) VALUES ('1','camp','".$row['callsign']."','".$row['value2']."')") or die ($dbconn->error.tkrowinsert_LINE_); }

/* DAILY - MOST SORTIES IN A ROW WITH A CAPTURE */
while ($row = $dtcrow->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,crowcallsign, crowpoints) VALUES ('1','day','".$row['callsign']."','".$row['value2']."')") or die ($dbconn->error.tkrowinsert_LINE_); }

/* Most kills on a SINGLE SORTIE */
while ($row = $allysks->fetch_assoc())
    {mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, sortmkillcallsign, sortmkill) VALUES ('1','camp','".$row['callsign']."','".$row['kills']."')") or die ($dbconn->error.kstreaksinsert_LINE_);}

/* Most kills on a SINGLE SORTIE - last 24 hours */
while ($row = $dallysks->fetch_assoc())
    {
       mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, sortmkillcallsign, sortmkill) VALUES ('1','day','".$row['callsign']."','".$row['kills']."')") or die ($dbconn->error.kstreaksinsert_LINE_); }

/* ALLIED MOST CAPTURES ON SINGLE SORTIE */
while ($row = $sortallycaps->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, sortcapscallsign, capstreak) VALUES ('1','camp','".$row['callsign']."','".$row['caps']."')") or die ($dbconn->error.capstreaksinsert_LINE_); }

/* ALLIED MOST CAPTURES ON SINGLE SORTIE - FOR LAST 24 HOURS */
while ($row = $dsortallycaps->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, sortcapscallsign, capstreak) VALUES ('1','day','".$row['callsign']."','".$row['caps']."')") or die ($dbconn->error.capstreaksinsert_LINE_); }

 ################# START AXIS STATS SECTION ################################################################

/* Queries */
/* Axis STATS REDUCDED TO 1 LINE ARE ALL THOSE THAT ARE COMPLETELY DONE */
/* TOP AAA */
while ($row = $gtaa->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, aaacallsign, aaapoints) VALUES ('2','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.gtaa);  }

/*Daily Top AAA */
while ($row = $gdtaa->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, aaacallsign, aaapoints) VALUES ('2','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.$gdtaa);  }

/* Axis top ATG */
while ($row = $gtatg->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, atgcallsign, atgpoints) VALUES ('2','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.tatg_LINE_); }

/* Axis top ATG Daily */
while ($row = $gdtatg->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, atgcallsign, atgpoints) VALUES ('2','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.datg_LINE_);}

/* Axis top ATR */
while ($row = $gtatr->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, atrcallsign, atrpoints) VALUES ('2','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.atrinsert_LINE_); }

/* Axis top ATR daily */
while ($row = $gdtatr->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, atrcallsign, atrpoints) VALUES ('2','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.atrinsert_LINE_); }

/* Axis Top engineer */
while ($row = $gteng->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, engcallsign, engpoints) VALUES ('2','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.enginsert_LINE_);  }

/* DAILY TOP Engineer */
while ($row = $gdteng->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, engcallsign, engpoints) VALUES ('2','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.enginsert_LINE_);  }

/* Axis Top Grenadier */
while ($row = $gtgren->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,grencallsign, grenpoints) VALUES ('2','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.grenadierinsert_LINE_); }

/* Axis top Grenadier - Daily */
while ($row = $gdtgren->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,grencallsign, grenpoints) VALUES ('2','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.grenadierinsert_LINE_); }

/* TOP LMG */
while ($row = $gtlmg->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,lmgcallsign, lmgpoints) VALUES ('2','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.tlmginsert_LINE_); }

/* DAILY TOP LMG */
while ($row = $gdtlmg->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,lmgcallsign, lmgpoints) VALUES ('2','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.tlmginsert_LINE_); }

/* TOP MORTAR */
while ($row = $gtmort->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,mortcallsign, mortpoints) VALUES ('2','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.tmortinsert_LINE_); }

/* DAILY TOP MORTAR */
while ($row = $gdtmort->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,mortcallsign, mortpoints) VALUES ('2','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.tmortinsert_LINE_); }

/* TOP RIFLE */
while ($row = $gtrifle->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,rcallsign, rpoints) VALUES ('2','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.trifleinsert_LINE_); }

/* DAILY TOP RIFLE */
while ($row = $gdtrifle->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,rcallsign, rpoints) VALUES ('2','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.trifleinsert_LINE_); }

/* Axis top SMG */
while ($row = $gtsmg->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,smgcallsign, smgpoints) VALUES ('2','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.smginsert_LINE_); }

/* Axis daily top SMG */
while ($row = $gdtsmg->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,smgcallsign, smgpoints) VALUES ('2','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.smginsert_LINE_); }

/* Axis top sniper */
while ($row = $gtsniper->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,snipercallsign, sniperpoints) VALUES ('2','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.atrinsert_LINE_); }

/* Axis Daily top Sniper */
while ($row = $gdtsniper->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,snipercallsign, sniperpoints) VALUES ('2','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.atrinsert_LINE_); }

/* Top Tank by Points */
while ($row = $gtank->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,tankcallsign, tankpoints) VALUES ('2','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.tankinsert_LINE_); }

/* Axis daily top Tank */
while ($row = $gdttank->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,tankcallsign, tankpoints) VALUES ('2','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.tankinsert_LINE_); }

/* TOP TRUCK */
while ($row = $gtruck->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, truckcallsign, truckpoints) VALUES ('2','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.ttruckinsert_LINE_); }

/* DAILY TOP TRUCK */
while ($row = $gdttruck->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, truckcallsign, truckpoints) VALUES ('2','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.ttruckinsert_LINE_); }

/* Asix Top Fighter */
while ($row = $gfight->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,fightcallsign, fightpoints) VALUES ('2','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.fightinsert_LINE_); }

/* Axis Daily top fighter */
while ($row = $gdtfight->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,fightcallsign, fightpoints) VALUES ('2','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.fightinsert_LINE_); }

/* TOP BOMBER */
while ($row = $gtbomb->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,bombcallsign, bombpoints) VALUES ('2','camp','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.bomberinsert_LINE_); }

/* DAILY TOP BOMBER */
while ($row = $gdtbomb->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,bombcallsign, bombpoints) VALUES ('2','day','".$row['callsign']."','".$row['points']."')") or die ($dbconn->error.bomberinsert_LINE_); }

/* TOP DESTROYER */
while ($row = $gtdd->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,ddcallsign, ddpoints) VALUES ('2','camp','".$row['callsign']."','".$row['points']."')") ; }

/* DAILY TOP DESTROYER */
while ($row = $gdtdd->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,ddcallsign, ddpoints) VALUES ('2','day','".$row['callsign']."','".$row['points']."')") ; }

/* TOP PATROL BOATS */
while ($row = $gtpb->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,pbcallsign, pbpoints) VALUES ('2','camp','".$row['callsign']."','".$row['points']."')") ; }

/* DAILY TOP PATROL BOATS */
while ($row = $gdtpb->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,pbcallsign, pbpoints) VALUES ('2','day','".$row['callsign']."','".$row['points']."')") ; }

/* TOP FREIGHTER (Transport (tt)) */
while ($row = $gttt->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,ttcallsign, ttpoints) VALUES ('2','camp','".$row['callsign']."','".$row['points']."')") ; }
/* DAILY TOP FREIGHTER  */
while ($row = $gdttt->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,ttcallsign, ttpoints) VALUES ('2','day','".$row['callsign']."','".$row['points']."')") ; }

/* TOP KILLS  */
while ($row = $axkills->fetch_assoc())
    {mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,killscallsign,kills) VALUES ('2','camp','".$row['callsign']."','".$row['kills']."')") or die ($dbconn->error.killsinsert_LINE_); }

/* DAILY TOP KILLS */
while ($row = $daxkills->fetch_assoc())
    {mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,killscallsign,kills) VALUES ('2','day','".$row['callsign']."','".$row['kills']."')") or die ($dbconn->error.killsinsert_LINE_);  }

// /* MOST CAPTURES */
// while ($row = $axcaps->fetch_assoc())
//     {mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,capscallsign,caps) VALUES ('2','camp','".$row['callsign']."','".$row['caps']."')") or die ($dbconn->error.capsinsert_LINE_); }
//
// /* DAILY MOST CAPTURES */
// while ($row = $daxcaps->fetch_assoc())
//     {mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,capscallsign,caps) VALUES ('2','day','".$row['callsign']."','".$row['caps']."')") or die ($dbconn->error.capsinsert_LINE_);  }

/* Top K/D */
while ($row = $axkd->fetch_assoc())
    {mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,kdcallsign,kd) VALUES ('2','camp','".$row['callsign']."','".$row['kd']."')") or die ($dbconn->error.kdinsert_LINE_); }

/* Axis K/D for the last 24 hours */
while ($row = $daxkd->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,kdcallsign,kd) VALUES ('2','day','".$row['callsign']."','".$row['kd']."')") or die ($dbconn->error.kdinsert_LINE_); }

/* TIME ON MISSION */
while ($row = $axtom->fetch_assoc())
    {mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,tomcallsign,tom) VALUES ('2','camp','".$row['callsign']."','".$row['tom']."')") or die ($dbconn->error.tominsert_LINE_); }

/* Most time on mission last 24 hour */
while ($row = $daxtom->fetch_assoc())
    {mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,tomcallsign,tom) VALUES ('2','day','".$row['callsign']."','".$row['tom']."')") or die ($dbconn->error.dtominsert_LINE_); }

/* Most consecutive Sorties with a kill */
while ($row = $gtkrow->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,krowcallsign, krowpoints) VALUES ('2','camp','".$row['callsign']."','".$row['value2']."')") or die ($dbconn->error.tkrowinsert_LINE_);}

/* DAILY CONSECUTIVE SORTIES WITH A KILL */
while ($row = $gdtkrow->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,krowcallsign, krowpoints) VALUES ('2','day','".$row['callsign']."','".$row['value2']."')") or die ($dbconn->error.tkrowinsert_LINE_); }

/* SORTIES IN A ROW WITH A CAPTURE */
while ($row = $gtcrow->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,crowcallsign, crowpoints) VALUES ('2','camp','".$row['callsign']."','".$row['value2']."')") or die ($dbconn->error.tkrowinsert_LINE_); }

/* DAILY - MOST SORTIES IN A ROW WITH A CAPTURE */
while ($row = $gdtcrow->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period,crowcallsign, crowpoints) VALUES ('2','day','".$row['callsign']."','".$row['value2']."')") or die ($dbconn->error.tkrowinsert_LINE_); }

/* Most kills on a SINGLE SORTIE */
while ($row = $axsks->fetch_assoc())
    {mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, sortmkillcallsign, sortmkill) VALUES ('2','camp','".$row['callsign']."','".$row['kills']."')") or die ($dbconn->error.kstreaksinsert_LINE_);}

/* Most kills on a SINGLE SORTIE - last 24 hours */
while ($row = $daxsks->fetch_assoc())
    {mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, sortmkillcallsign, sortmkill) VALUES ('2','day','".$row['callsign']."','".$row['kills']."')") or die ($dbconn->error.kstreaksinsert_LINE_); }

/* Axis MOST CAPTURES ON SINGLE SORTIE */
while ($row = $sortaxcaps->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, sortcapscallsign, capstreak) VALUES ('2','camp','".$row['callsign']."','".$row['caps']."')") or die ($dbconn->error.capstreaksinsert_LINE_); }

/* Axis MOST CAPTURES ON SINGLE SORTIE - FOR LAST 24 HOURS */
while ($row = $dsortaxcaps->fetch_assoc())
    { mysqli_query($dbconn, "INSERT INTO scoring_top_players (side, period, sortcapscallsign, capstreak) VALUES ('2','day','".$row['callsign']."','".$row['caps']."')") or die ($dbconn->error.capstreaksinsert_LINE_); }

echo "Side Leaderboard (axis.php and allied.php) stats update -- complete \n";

?>
