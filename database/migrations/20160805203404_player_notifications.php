<?php

use Phinx\Migration\AbstractMigration;

class PlayerNotifications extends AbstractMigration
{
    /**
	 * Create a new table to hold the unsubscribe status of the emailer
     */
    public function up()
    {
		/**
		 * An id column will be automagically created for the table
		 */
		$table = $this->table('player_notifications');
		$table->addColumn('player_id', 'integer', ['signed' => false])
			->addColumn('created_at', 'datetime')
			->addColumn('updated_at', 'datetime')
			// randomly generated token (2 x MD5 hash, strip 4 chars) that will be in the email
			// confirming the unsubscribe request
			->addColumn('unsubscribe_token', 'string', ['limit' => '60'])
			->addColumn('unsubscribed_at','datetime',['null' => true])
			// A column to hold when the player was last notified to prevent
			// spamming them
			->addColumn('last_sent_at','datetime')
			->create();
    }
	
    public function down()
    {
		/**
		 * Kill off the table
		 */
		$this->table('player_notifications')->drop();
		
    }	
}
