<?php

use Phinx\Migration\AbstractMigration;

class AddMissingCampaigns extends AbstractMigration
{

	public function up()
	{
        $this->execute("UPDATE `campaigns` SET `status` = 'Completed', `stop_time` = '2014-09-12 19:33:15' WHERE `campaign_id` = 104");
		
		$this->execute("INSERT INTO `campaigns` (`campaign_id`, `status`, `start_time`, `stop_time`)
			VALUES(105,'Completed','2014-09-12 19:33:15','2014-09-23 13:59:41'),
			(106,'Completed','2014-09-23 13:59:41','2014-11-05 11:53:12'),
			(107,'Completed','2014-11-05 12:02:08','2014-12-09 14:08:00'),
			(108,'Completed','2014-12-12 11:27:49','2015-01-08 12:37:46'),
			(109,'Completed','2015-01-13 11:37:40','2015-02-01 14:43:35'),
			(110,'Completed','2015-02-02 11:26:44','2015-02-19 12:02:12'),
			(111,'Completed','2015-02-20 11:12:41','2015-03-13 14:04:12'),
			(112,'Completed','2015-03-16 11:19:47','2015-04-03 11:32:07'),
			(113,'Completed','2015-04-03 12:10:07','2015-04-24 14:32:14'),
			(114,'Completed','2015-04-24 20:00:09','2015-06-17 20:53:25'),
			(115,'Completed','2015-06-19 12:29:25','2015-08-06 21:35:01'),
			(116,'Completed','2015-08-10 12:52:13','2015-09-20 00:18:39'),
			(117,'Completed','2015-09-21 18:28:53','2015-10-09 23:15:04'),
			(118,'Completed','2015-10-12 16:41:40','2015-11-02 22:14:08'),
			(119,'Completed','2015-11-02 22:42:19','2015-12-05 18:36:18')"
		);
	}
	
	public function down() 
	{
		$this->execute('DELETE FROM `campaigns` WHERE campaign_id >= 105');
		
		$this->execute("UPDATE `campaigns` SET `status` = 'Running', `stop_time` = NULL WHERE `campaign_id` = 104");
	}
}
