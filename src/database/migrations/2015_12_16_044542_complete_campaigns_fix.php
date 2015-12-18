<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CompleteCampaignsFix extends Migration
{
    /**
     * Now we have to make the final changes to the campaigns table to make it usable
     *
     * @return void
     */
    public function up()
    {
		/**
		 * Rename the primary key to the standarised "id"
		 */
        Schema::table('campaigns', function(Blueprint $table) {
			
			$table->renameColumn('campaign_id', 'id');
		});
		
		/**
		 * Create a new campaign_id field
		 */
        Schema::table('campaigns', function(Blueprint $table) {
			
			$table->integer('campaign_id')->unsigned();
		});
		
		/**
		 * Populate the correct campaign ids
		 */
		DB::statement('UPDATE campaigns SET campaign_id = id');
		
		/**
		 * Rename the state table to status
		 */
        Schema::table('campaigns', function(Blueprint $table) {
			
			$table->renameColumn('state', 'status');
		});		
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		/**
		 * Rename the status table back to status
		 */
        Schema::table('campaigns', function(Blueprint $table) {
			
			$table->renameColumn('status', 'state');
		});		
		
		/**
		 * Remove the campaign_id
		 */
        Schema::table('campaigns', function(Blueprint $table) {
			
			$table->dropColumn('campaign_id');
		});			
		
		/**
		 * Move the id value back to campaign_id
		 */
        Schema::table('campaigns', function(Blueprint $table) {
			
			$table->renameColumn('id', 'campaign_id');
		});
    }
}
