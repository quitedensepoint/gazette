<?php

use Phinx\Migration\AbstractMigration;

class AddGermanStories extends AbstractMigration
{

	public function up()
	{
		/** Add NEW Stories */
		$this->execute("INSERT INTO `stories` (`story_id`, `page_id`, `filename`, `content`, `template`, `used_id`, `size`, `alt`, `expires`, `story_key`, `title_case`, `expire`, `story_format_id`) VALUES
                            ('61', '1', 'best_dd.html', '', '%TITLE% %BODY%', '122', '0', NULL, NULL, 'index_best_dd', 'Pass', '0', '1'), 
                            ('64', '3', 'playnow_axis_promotion.html', '', '%TITLE% %BODY%', '121', '0', NULL, NULL, 'playnow_axis_promotion', 'Pass', '0', '1'),
                            ('62', '3', 'playnow_axis_stats3.html', '', '%TITLE% %BODY%', '107', '0', NULL, NULL, 'playnow_axis_stats3', 'Pass', '0', '1'),
                            ('63', '3', 'playnow_axis_stats4.html', '', '%TITLE% %BODY%', '106', '0', NULL, NULL, 'playnow_axis_stats4', 'Pass', '0', '1')
                            ");		

        /** Adjust story_countries for new stories */
		$this->execute("INSERT INTO `story_countries` (`story_id`, `country_id`) VALUES ('61', '1'), ('61', '2'), ('61', '3'), ('61', '4'), ('62', '4'), ('63', '4'), ('64','4')");

		/** Adjust Story_types for new stories */
		$this->execute("INSERT INTO `story_types` (`story_id`, `type_id`) VALUES ('61', '21'), ('62', '5'), ('63', '5'), ('64', '5')");

		
                        
		
	}
	
	public function down() 
	{
        $this->execute("DELETE FROM `story_types` WHERE `story_id` = '61' OR `story_id` ='62' OR`story_id` = '63' OR`story_id` = '64'");
		$this->execute("DELETE FROM `story_countries` WHERE `story_id` = '61' OR `story_id` ='62' OR`story_id` = '63' OR`story_id` = '64'");
		$this->execute("DELETE FROM `stories` WHERE `story_id` = '61' OR `story_id` ='62' OR`story_id` = '63' OR`story_id` = '64'");
		
	}
}
