<?php
$dbConnCommunity = mysqli_connect("127.0.0.1:3306", "root", "changeme", "community");
if (mysqli_connect_errno()){
    echo "Failed to connect to the Community database: " . mysqli_connect_error();
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

if(!$taa) {
    echo 'Query failed.';
} else {
    echo 'Query successful.';
}
