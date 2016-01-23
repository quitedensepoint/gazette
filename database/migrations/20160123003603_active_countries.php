<?php

use Phinx\Migration\AbstractMigration;

class ActiveCountries extends AbstractMigration
{
	public function up() {
		
		/**
		 * New columns to track if a country is active
		 */
		$this->table('countries')->
			addColumn('is_active', 'boolean', ['default' => 0, 'null' => false])->
			addColumn('activated_at', 'datetime', ['null' => true])
			->update();
		
		/**
		 * Add the missing countries
		 */
		$this->execute("INSERT INTO `countries` (`country_id`, `side_id`, `cycle_id`, `name`, `adjective`, `side`, `added`, `modified`) VALUES
			(5, 2, 0, 'Italy', 'Italian', 'Axis', NOW(), NOW()),
			(6, 2, 0, 'Japan', 'Japanese', 'Axis', NOW(), NOW()),
			(7, 1, 0, 'Commonwealth', 'Commonwealth', 'Allied', NOW(), NOW()),
			(8, 1, 0, 'China', 'Chinese', 'Allied', NOW(), NOW()),
			(9, 1, 0, 'Russia', 'Russian', 'Allied', NOW(), NOW());"
			);
	}
	
	public function down() {
		
		$this->execute("DELETE FROM `countries` WHERE `country_id` >= 5");
		
		$this->table('countries')
			->removeColumn('activated_at')
			->removeColumn('is_active')
			->update();
	}
}
