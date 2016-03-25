<?php

use Phinx\Migration\AbstractMigration;

class alterSorties extends AbstractMigration
{

	public function up()
	{
		/* Alter some sorties to test allied daily stats */
		$this->execute("UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 07:06:36' WHERE `scoring_campaign_sorties`.`sortie_id` = '48' AND `scoring_campaign_sorties`.`persona_id` = '989728' AND `scoring_campaign_sorties`.`player_id` = 1398688;
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 07:06:48' WHERE `scoring_campaign_sorties`.`sortie_id` = '51' AND `scoring_campaign_sorties`.`persona_id` = '1032688' AND `scoring_campaign_sorties`.`player_id` = '1435718'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 08:06:58' WHERE `scoring_campaign_sorties`.`sortie_id` = '55' AND `scoring_campaign_sorties`.`persona_id` = '114332' AND `scoring_campaign_sorties`.`player_id` = '1478487'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 08:07:05' WHERE `scoring_campaign_sorties`.`sortie_id` = '56' AND `scoring_campaign_sorties`.`persona_id` = '568100' AND `scoring_campaign_sorties`.`player_id` = '1194479'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 08:07:16' WHERE `scoring_campaign_sorties`.`sortie_id` = '59' AND `scoring_campaign_sorties`.`persona_id` = '44321' AND `scoring_campaign_sorties`.`player_id` = '33347'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 08:07:17' WHERE `scoring_campaign_sorties`.`sortie_id` = '60' AND `scoring_campaign_sorties`.`persona_id` = '340212' AND `scoring_campaign_sorties`.`player_id` = '1039232'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 08:07:47' WHERE `scoring_campaign_sorties`.`sortie_id` = '66' AND `scoring_campaign_sorties`.`persona_id` = '809193' AND `scoring_campaign_sorties`.`player_id` = '1021689'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 08:08:10' WHERE `scoring_campaign_sorties`.`sortie_id` = '68' AND `scoring_campaign_sorties`.`persona_id` = '1083202' AND `scoring_campaign_sorties`.`player_id` = '1283139'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 09:08:21' WHERE `scoring_campaign_sorties`.`sortie_id` = '70' AND `scoring_campaign_sorties`.`persona_id` = '719515' AND `scoring_campaign_sorties`.`player_id` = '1306865'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 09:07:56' WHERE `scoring_campaign_sorties`.`sortie_id` = '71' AND `scoring_campaign_sorties`.`persona_id` = '139514' AND `scoring_campaign_sorties`.`player_id` = '50290'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 09:08:04' WHERE `scoring_campaign_sorties`.`sortie_id` = '74' AND `scoring_campaign_sorties`.`persona_id` = '223887' AND `scoring_campaign_sorties`.`player_id` = '84885'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 09:08:12' WHERE `scoring_campaign_sorties`.`sortie_id` = '76' AND `scoring_campaign_sorties`.`persona_id` = '1034986' AND `scoring_campaign_sorties`.`player_id` = '1436987'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 09:08:15' WHERE `scoring_campaign_sorties`.`sortie_id` = '78' AND `scoring_campaign_sorties`.`persona_id` = '44271' AND `scoring_campaign_sorties`.`player_id` = '34465'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 10:08:19' WHERE `scoring_campaign_sorties`.`sortie_id` = '80' AND `scoring_campaign_sorties`.`persona_id` = '809193' AND `scoring_campaign_sorties`.`player_id` = '1021689'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 10:08:30' WHERE `scoring_campaign_sorties`.`sortie_id` = '82' AND `scoring_campaign_sorties`.`persona_id` = '1108469' AND `scoring_campaign_sorties`.`player_id` = '1466527'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 10:08:33' WHERE `scoring_campaign_sorties`.`sortie_id` = '84' AND `scoring_campaign_sorties`.`persona_id` = '13592' AND `scoring_campaign_sorties`.`player_id` = '15407';
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 10:09:04' WHERE `scoring_campaign_sorties`.`sortie_id` = '86' AND `scoring_campaign_sorties`.`persona_id` = '1164196' AND `scoring_campaign_sorties`.`player_id` = '1490440'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 10:08:45' WHERE `scoring_campaign_sorties`.`sortie_id` = '89' AND `scoring_campaign_sorties`.`persona_id` = '113245' AND `scoring_campaign_sorties`.`player_id` = '7000'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 10:08:45' WHERE `scoring_campaign_sorties`.`sortie_id` = '91' AND `scoring_campaign_sorties`.`persona_id` = '986429' AND `scoring_campaign_sorties`.`player_id` = '1418973'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 11:09:11' WHERE `scoring_campaign_sorties`.`sortie_id` = '92' AND `scoring_campaign_sorties`.`persona_id` = '232470' AND `scoring_campaign_sorties`.`player_id` = '89394'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 11:09:11' WHERE `scoring_campaign_sorties`.`sortie_id` = '98' AND `scoring_campaign_sorties`.`persona_id` = '809193' AND `scoring_campaign_sorties`.`player_id` = '1021689'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 11:09:36' WHERE `scoring_campaign_sorties`.`sortie_id` = '104' AND `scoring_campaign_sorties`.`persona_id` = '941444' AND `scoring_campaign_sorties`.`player_id` = '1402196'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 11:09:45' WHERE `scoring_campaign_sorties`.`sortie_id` = '108' AND `scoring_campaign_sorties`.`persona_id` = '91232' AND `scoring_campaign_sorties`.`player_id` = '49437'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 11:10:29' WHERE `scoring_campaign_sorties`.`sortie_id` = '111' AND `scoring_campaign_sorties`.`persona_id` = '212281' AND `scoring_campaign_sorties`.`player_id` = '83073'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 11:09:55' WHERE `scoring_campaign_sorties`.`sortie_id` = '112' AND `scoring_campaign_sorties`.`persona_id` = '1161720' AND `scoring_campaign_sorties`.`player_id` = '1489591'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 11:10:13' WHERE `scoring_campaign_sorties`.`sortie_id` = '115' AND `scoring_campaign_sorties`.`persona_id` = '5778' AND `scoring_campaign_sorties`.`player_id` = '39137'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 11:10:03' WHERE `scoring_campaign_sorties`.`sortie_id` = '116' AND `scoring_campaign_sorties`.`persona_id` = '497951' AND `scoring_campaign_sorties`.`player_id` = '1117957'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 11:10:45' WHERE `scoring_campaign_sorties`.`sortie_id` = '125' AND `scoring_campaign_sorties`.`persona_id` = '809193' AND `scoring_campaign_sorties`.`player_id` = '1021689'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 11:11:03' WHERE `scoring_campaign_sorties`.`sortie_id` = '131' AND `scoring_campaign_sorties`.`persona_id` = '497951' AND `scoring_campaign_sorties`.`player_id` = '1117957'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 11:11:34' WHERE `scoring_campaign_sorties`.`sortie_id` = '140' AND `scoring_campaign_sorties`.`persona_id` = '223887' AND `scoring_campaign_sorties`.`player_id` = '84885'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 12:42:51' WHERE `scoring_campaign_sorties`.`sortie_id` = '279' AND `scoring_campaign_sorties`.`persona_id` = '737873' AND `scoring_campaign_sorties`.`player_id` = '1320423'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 12:43:02' WHERE `scoring_campaign_sorties`.`sortie_id` = '283' AND `scoring_campaign_sorties`.`persona_id` = '355186' AND `scoring_campaign_sorties`.`player_id` = '1046124'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 12:43:25' WHERE `scoring_campaign_sorties`.`sortie_id` = '287' AND `scoring_campaign_sorties`.`persona_id` = '497951' AND `scoring_campaign_sorties`.`player_id` = '1117957'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 12:43:47' WHERE `scoring_campaign_sorties`.`sortie_id` = '294' AND `scoring_campaign_sorties`.`persona_id` = '113245' AND `scoring_campaign_sorties`.`player_id` = '7000'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 12:43:52' WHERE `scoring_campaign_sorties`.`sortie_id` = '295' AND `scoring_campaign_sorties`.`persona_id` = '1153520' AND `scoring_campaign_sorties`.`player_id` = '1478487'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 12:44:01' WHERE `scoring_campaign_sorties`.`sortie_id` = '296' AND `scoring_campaign_sorties`.`persona_id` = '20830' AND `scoring_campaign_sorties`.`player_id` = '31166'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 12:43:57' WHERE `scoring_campaign_sorties`.`sortie_id` = '297' AND `scoring_campaign_sorties`.`persona_id` = '1151745' AND `scoring_campaign_sorties`.`player_id` = '1485464'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 12:44:02' WHERE `scoring_campaign_sorties`.`sortie_id` = '298' AND `scoring_campaign_sorties`.`persona_id` = '1154783' AND `scoring_campaign_sorties`.`player_id` = '1486339'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 12:44:02' WHERE `scoring_campaign_sorties`.`sortie_id` = '299' AND `scoring_campaign_sorties`.`persona_id` = '1030850' AND `scoring_campaign_sorties`.`player_id` = '1435533'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 12:44:06' WHERE `scoring_campaign_sorties`.`sortie_id` = '300' AND `scoring_campaign_sorties`.`persona_id` = '13592' AND `scoring_campaign_sorties`.`player_id` = '15407'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 13:44:06' WHERE `scoring_campaign_sorties`.`sortie_id` = '604' AND `scoring_campaign_sorties`.`persona_id` = '232574' AND `scoring_campaign_sorties`.`player_id` = '89150'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 13:44:06' WHERE `scoring_campaign_sorties`.`sortie_id` = '472' AND `scoring_campaign_sorties`.`persona_id` = '472147' AND `scoring_campaign_sorties`.`player_id` = '1104581';
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 13:44:06' WHERE `scoring_campaign_sorties`.`sortie_id` = '504' AND `scoring_campaign_sorties`.`persona_id` = '126313' AND `scoring_campaign_sorties`.`player_id` = '28109';
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 13:44:06' WHERE `scoring_campaign_sorties`.`sortie_id` = '1932' AND `scoring_campaign_sorties`.`persona_id` = '509421' AND `scoring_campaign_sorties`.`player_id` = '1133731';
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 14:44:06' WHERE `scoring_campaign_sorties`.`sortie_id` = '3879' AND `scoring_campaign_sorties`.`persona_id` = '27200' AND `scoring_campaign_sorties`.`player_id` = '39137';
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 14:44:06' WHERE `scoring_campaign_sorties`.`sortie_id` = '5929' AND `scoring_campaign_sorties`.`persona_id` = '524052' AND `scoring_campaign_sorties`.`player_id` = '1147959';
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 14:44:06' WHERE `scoring_campaign_sorties`.`sortie_id` = '27119' AND `scoring_campaign_sorties`.`persona_id` = '624804' AND `scoring_campaign_sorties`.`player_id` = '1035385';
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 14:44:06' WHERE `scoring_campaign_sorties`.`sortie_id` = '588533' AND `scoring_campaign_sorties`.`persona_id` = '1040052' AND `scoring_campaign_sorties`.`player_id` = '1438922';
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 14:44:06' WHERE `scoring_campaign_sorties`.`sortie_id` = '4817' AND `scoring_campaign_sorties`.`persona_id` = '516013' AND `scoring_campaign_sorties`.`player_id` = '1133731';
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 15:44:06' WHERE `scoring_campaign_sorties`.`sortie_id` = '192221' AND `scoring_campaign_sorties`.`persona_id` = '10404' AND `scoring_campaign_sorties`.`player_id` = '24015';
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 15:44:06' WHERE `scoring_campaign_sorties`.`sortie_id` = '588263' AND `scoring_campaign_sorties`.`persona_id` = '884818'  AND `scoring_campaign_sorties`.`player_id` = '1186641';
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 15:44:06' WHERE `scoring_campaign_sorties`.`sortie_id` = '30468' AND `scoring_campaign_sorties`.`persona_id` = '245450'  AND `scoring_campaign_sorties`.`player_id` = '92587';
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 15:44:06' WHERE `scoring_campaign_sorties`.`sortie_id` = '47232' AND `scoring_campaign_sorties`.`persona_id` = '5201'  AND `scoring_campaign_sorties`.`player_id` = '33347';
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 15:44:06' WHERE `scoring_campaign_sorties`.`sortie_id` = '308' AND `scoring_campaign_sorties`.`persona_id` = '3617123'  AND `scoring_campaign_sorties`.`player_id` = '1046124';
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 15:44:06' WHERE `scoring_campaign_sorties`.`sortie_id` = '5521' AND `scoring_campaign_sorties`.`persona_id` = '5972'  AND `scoring_campaign_sorties`.`player_id` = '5359';
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 15:45:06' WHERE `scoring_campaign_sorties`.`sortie_id` = '5872' AND `scoring_campaign_sorties`.`persona_id` = '5972'  AND `scoring_campaign_sorties`.`player_id` = '5359';
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 15:45:06' WHERE `scoring_campaign_sorties`.`sortie_id` = '55' AND `scoring_campaign_sorties`.`persona_id` = '1143322'  AND `scoring_campaign_sorties`.`player_id` = '1478487';
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2016-03-26 15:45:06' WHERE `scoring_campaign_sorties`.`sortie_id` = '1396' AND `scoring_campaign_sorties`.`persona_id` = '700987'  AND `scoring_campaign_sorties`.`player_id` = '1289234';





");		

		
	}
	
	public function down() 
	{
        $this->execute("UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:06:36' WHERE `scoring_campaign_sorties`.`sortie_id` = '48' AND `scoring_campaign_sorties`.`persona_id` = '989728' AND `scoring_campaign_sorties`.`player_id` = '1398688'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:06:48' WHERE `scoring_campaign_sorties`.`sortie_id` = '51' AND `scoring_campaign_sorties`.`persona_id` = '1032688' AND `scoring_campaign_sorties`.`player_id` = '1435718'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:06:58' WHERE `scoring_campaign_sorties`.`sortie_id` = '55' AND `scoring_campaign_sorties`.`persona_id` = '114332' AND `scoring_campaign_sorties`.`player_id` = '1478487'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:07:05' WHERE `scoring_campaign_sorties`.`sortie_id` = '56' AND `scoring_campaign_sorties`.`persona_id` = '568100' AND `scoring_campaign_sorties`.`player_id` = '1194479'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:07:16' WHERE `scoring_campaign_sorties`.`sortie_id` = '59' AND `scoring_campaign_sorties`.`persona_id` = '44321' AND `scoring_campaign_sorties`.`player_id` = '33347'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:07:17' WHERE `scoring_campaign_sorties`.`sortie_id` = '60' AND `scoring_campaign_sorties`.`persona_id` = '340212' AND `scoring_campaign_sorties`.`player_id` = '1039232'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:07:47' WHERE `scoring_campaign_sorties`.`sortie_id` = '66' AND `scoring_campaign_sorties`.`persona_id` = '809193' AND `scoring_campaign_sorties`.`player_id` = '1021689'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:08:10' WHERE `scoring_campaign_sorties`.`sortie_id` = '68' AND `scoring_campaign_sorties`.`persona_id` = '1083202' AND `scoring_campaign_sorties`.`player_id` = '1283139'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:08:21' WHERE `scoring_campaign_sorties`.`sortie_id` = '70' AND `scoring_campaign_sorties`.`persona_id` = '719515' AND `scoring_campaign_sorties`.`player_id` = '1306865'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:07:56' WHERE `scoring_campaign_sorties`.`sortie_id` = '71' AND `scoring_campaign_sorties`.`persona_id` = '139514' AND `scoring_campaign_sorties`.`player_id` = '50290'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:08:04' WHERE `scoring_campaign_sorties`.`sortie_id` = '74' AND `scoring_campaign_sorties`.`persona_id` = '223887' AND `scoring_campaign_sorties`.`player_id` = '84885'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:08:12' WHERE `scoring_campaign_sorties`.`sortie_id` = '76' AND `scoring_campaign_sorties`.`persona_id` = '1034986' AND `scoring_campaign_sorties`.`player_id` = '1436987'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:08:15' WHERE `scoring_campaign_sorties`.`sortie_id` = '78' AND `scoring_campaign_sorties`.`persona_id` = '44271' AND `scoring_campaign_sorties`.`player_id` = '34465'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:08:19' WHERE `scoring_campaign_sorties`.`sortie_id` = '80' AND `scoring_campaign_sorties`.`persona_id` = '809193' AND `scoring_campaign_sorties`.`player_id` = '1021689'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:08:30' WHERE `scoring_campaign_sorties`.`sortie_id` = '82' AND `scoring_campaign_sorties`.`persona_id` = '1108469' AND `scoring_campaign_sorties`.`player_id` = '1466527'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:08:33' WHERE `scoring_campaign_sorties`.`sortie_id` = '84' AND `scoring_campaign_sorties`.`persona_id` = '13592' AND `scoring_campaign_sorties`.`player_id` = '15407';
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:09:04' WHERE `scoring_campaign_sorties`.`sortie_id` = '86' AND `scoring_campaign_sorties`.`persona_id` = '1164196' AND `scoring_campaign_sorties`.`player_id` = '1490440'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:08:45' WHERE `scoring_campaign_sorties`.`sortie_id` = '89' AND `scoring_campaign_sorties`.`persona_id` = '113245' AND `scoring_campaign_sorties`.`player_id` = '7000'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:08:45' WHERE `scoring_campaign_sorties`.`sortie_id` = '91' AND `scoring_campaign_sorties`.`persona_id` = '986429' AND `scoring_campaign_sorties`.`player_id` = '1418973'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:09:11' WHERE `scoring_campaign_sorties`.`sortie_id` = '92' AND `scoring_campaign_sorties`.`persona_id` = '232470' AND `scoring_campaign_sorties`.`player_id` = '89394'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:09:11' WHERE `scoring_campaign_sorties`.`sortie_id` = '98' AND `scoring_campaign_sorties`.`persona_id` = '809193' AND `scoring_campaign_sorties`.`player_id` = '1021689'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:09:36' WHERE `scoring_campaign_sorties`.`sortie_id` = '104' AND `scoring_campaign_sorties`.`persona_id` = '941444' AND `scoring_campaign_sorties`.`player_id` = '1402196'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:09:45' WHERE `scoring_campaign_sorties`.`sortie_id` = '108' AND `scoring_campaign_sorties`.`persona_id` = '91232' AND `scoring_campaign_sorties`.`player_id` = '49437'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:10:29' WHERE `scoring_campaign_sorties`.`sortie_id` = '111' AND `scoring_campaign_sorties`.`persona_id` = '212281' AND `scoring_campaign_sorties`.`player_id` = '83073'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:09:55' WHERE `scoring_campaign_sorties`.`sortie_id` = '112' AND `scoring_campaign_sorties`.`persona_id` = '1161720' AND `scoring_campaign_sorties`.`player_id` = '1489591'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:10:13' WHERE `scoring_campaign_sorties`.`sortie_id` = '115' AND `scoring_campaign_sorties`.`persona_id` = '5778' AND `scoring_campaign_sorties`.`player_id` = '39137'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:10:03' WHERE `scoring_campaign_sorties`.`sortie_id` = '116' AND `scoring_campaign_sorties`.`persona_id` = '497951' AND `scoring_campaign_sorties`.`player_id` = '1117957'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:10:45' WHERE `scoring_campaign_sorties`.`sortie_id` = '125' AND `scoring_campaign_sorties`.`persona_id` = '809193' AND `scoring_campaign_sorties`.`player_id` = '1021689'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:11:03' WHERE `scoring_campaign_sorties`.`sortie_id` = '131' AND `scoring_campaign_sorties`.`persona_id` = '497951' AND `scoring_campaign_sorties`.`player_id` = '1117957'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:11:34' WHERE `scoring_campaign_sorties`.`sortie_id` = '140' AND `scoring_campaign_sorties`.`persona_id` = '223887' AND `scoring_campaign_sorties`.`player_id` = '84885'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:42:51' WHERE `scoring_campaign_sorties`.`sortie_id` = '279' AND `scoring_campaign_sorties`.`persona_id` = '737873' AND `scoring_campaign_sorties`.`player_id` = '1320423'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:43:02' WHERE `scoring_campaign_sorties`.`sortie_id` = '283' AND `scoring_campaign_sorties`.`persona_id` = '355186' AND `scoring_campaign_sorties`.`player_id` = '1046124'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:43:25' WHERE `scoring_campaign_sorties`.`sortie_id` = '287' AND `scoring_campaign_sorties`.`persona_id` = '497951' AND `scoring_campaign_sorties`.`player_id` = '1117957'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:43:47' WHERE `scoring_campaign_sorties`.`sortie_id` = '294' AND `scoring_campaign_sorties`.`persona_id` = '113245' AND `scoring_campaign_sorties`.`player_id` = '7000'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:43:52' WHERE `scoring_campaign_sorties`.`sortie_id` = '295' AND `scoring_campaign_sorties`.`persona_id` = '1153520' AND `scoring_campaign_sorties`.`player_id` = '1478487'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:44:01' WHERE `scoring_campaign_sorties`.`sortie_id` = '296' AND `scoring_campaign_sorties`.`persona_id` = '20830' AND `scoring_campaign_sorties`.`player_id` = '31166'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:43:57' WHERE `scoring_campaign_sorties`.`sortie_id` = '297' AND `scoring_campaign_sorties`.`persona_id` = '1151745' AND `scoring_campaign_sorties`.`player_id` = '1485464'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:44:02' WHERE `scoring_campaign_sorties`.`sortie_id` = '298' AND `scoring_campaign_sorties`.`persona_id` = '1154783' AND `scoring_campaign_sorties`.`player_id` = '1486339'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:44:02' WHERE `scoring_campaign_sorties`.`sortie_id` = '299' AND `scoring_campaign_sorties`.`persona_id` = '1030850' AND `scoring_campaign_sorties`.`player_id` = '1435533'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:44:06' WHERE `scoring_campaign_sorties`.`sortie_id` = '300' AND `scoring_campaign_sorties`.`persona_id` = '13592' AND `scoring_campaign_sorties`.`player_id` =' 15407'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 15:00:11' WHERE `scoring_campaign_sorties`.`sortie_id` = '604' AND `scoring_campaign_sorties`.`persona_id` = '232574' AND `scoring_campaign_sorties`.`player_id` = '89150'; 
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:53:59' WHERE `scoring_campaign_sorties`.`sortie_id` = '472' AND `scoring_campaign_sorties`.`persona_id` = '472147' AND `scoring_campaign_sorties`.`player_id` = '1104581';
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:55:57' WHERE `scoring_campaign_sorties`.`sortie_id` = '504' AND `scoring_campaign_sorties`.`persona_id` = '126313' AND `scoring_campaign_sorties`.`player_id` = '28109';
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 15:48:11' WHERE `scoring_campaign_sorties`.`sortie_id` = '1932' AND `scoring_campaign_sorties`.`persona_id` = '509421' AND `scoring_campaign_sorties`.`player_id` = '1133731';
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 16:51:25' WHERE `scoring_campaign_sorties`.`sortie_id` = '3879' AND `scoring_campaign_sorties`.`persona_id` = '27200' AND `scoring_campaign_sorties`.`player_id` = '39137';
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 17:59:02' WHERE `scoring_campaign_sorties`.`sortie_id` = '5929' AND `scoring_campaign_sorties`.`persona_id` = '524052' AND `scoring_campaign_sorties`.`player_id` = '1147959';
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:17:11' WHERE `scoring_campaign_sorties`.`sortie_id` = '27119' AND `scoring_campaign_sorties`.`persona_id` = '624804' AND `scoring_campaign_sorties`.`player_id` = '1035385';
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 10:58:15' WHERE `scoring_campaign_sorties`.`sortie_id` = '588533' AND `scoring_campaign_sorties`.`persona_id` = '1040052' AND `scoring_campaign_sorties`.`player_id` = '1438922';
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 17:19:16' WHERE `scoring_campaign_sorties`.`sortie_id` = '4817' AND `scoring_campaign_sorties`.`persona_id` = '516013' AND `scoring_campaign_sorties`.`player_id` = '1133731';
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 19:08:56' WHERE `scoring_campaign_sorties`.`sortie_id` = '192221' AND `scoring_campaign_sorties`.`persona_id` = '10404' AND `scoring_campaign_sorties`.`player_id` = '24015';
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-30 10:34:53' WHERE `scoring_campaign_sorties`.`sortie_id` = '588263' AND `scoring_campaign_sorties`.`persona_id` = '884818' AND `scoring_campaign_sorties`.`player_id` = '1186641';
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-06 16:36:22' WHERE `scoring_campaign_sorties`.`sortie_id` = '30468' AND `scoring_campaign_sorties`.`persona_id` = '245450'  AND `scoring_campaign_sorties`.`player_id` = '92587';
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-07 11:29:49' WHERE `scoring_campaign_sorties`.`sortie_id` = '47232' AND `scoring_campaign_sorties`.`persona_id` = '5201'  AND `scoring_campaign_sorties`.`player_id` = '33347';
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:44:34' WHERE `scoring_campaign_sorties`.`sortie_id` = '308' AND `scoring_campaign_sorties`.`persona_id` = '3617123'  AND `scoring_campaign_sorties`.`player_id` = '1046124';
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 17:23:50' WHERE `scoring_campaign_sorties`.`sortie_id` = '5521' AND `scoring_campaign_sorties`.`persona_id` = '5972'  AND `scoring_campaign_sorties`.`player_id` = '5359';
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 17:43:35' WHERE `scoring_campaign_sorties`.`sortie_id` = '5872' AND `scoring_campaign_sorties`.`persona_id` = '5972'  AND `scoring_campaign_sorties`.`player_id` = '5359';
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 14:06:58' WHERE `scoring_campaign_sorties`.`sortie_id` = '55' AND `scoring_campaign_sorties`.`persona_id` = '1143322'  AND `scoring_campaign_sorties`.`player_id` = '1478487';
                        UPDATE `scoring_campaign_sorties` SET `sortie_start` = '2015-12-05 15:39:30' WHERE `scoring_campaign_sorties`.`sortie_id` = '1396' AND `scoring_campaign_sorties`.`persona_id` = '700987'  AND `scoring_campaign_sorties`.`player_id` = '1289234';

");
	}
}
