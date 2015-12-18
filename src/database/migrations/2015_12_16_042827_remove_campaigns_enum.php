<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveCampaignsEnum extends Migration
{
    /**
     * Kill off the old enumerated database fields
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaigns', function(Blueprint $table) {
			
			/**
			 * Remove the old enum field
			 */
			$table->dropColumn('status');
		});
    }

    /**
     * Re-add and repopulate the old enum field
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campaigns', function(Blueprint $table) {
			
			/**
			 * Re-added the old enum field
			 */
			$table->enum('status', ['Running', 'Completed']);
		});
		
		/**
		 * Reset the state of the enums back to their original values
		 */
		DB::statement('UPDATE campaigns SET status = state');
    }
}
