<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixCampaignsTable extends Migration
{
    /**
     * Updates the campaigns to copy the enum fields to a new string field
     *
     * @return void
     */
    public function up()
    {
		/**
		 * This one is a little tricky. I am removing enums from the table because
		 * they are a Bad Thing. You can't rename a column in the table if it
		 * contains an enum
		 */
        Schema::table('campaigns', function(Blueprint $table) {
			
			/**
			 * Holds the state of the campaign. This is in place to 
			 * deprecate the enum field "status"
			 */
			$table->string('state', 15);
		});
		
		/**
		 * Copy the enum states across to the new string field.
		 * 
		 * Please note this is a hack to have a data modification statement in
		 * a migration. You should not ordinarily do this, but the use of ENUMS
		 * forces the hand a little.
		 */
		
		DB::statement('UPDATE campaigns SET state = status');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campaigns', function(Blueprint $table) {
			
			$table->dropColumn('state');
		});
    }
}
