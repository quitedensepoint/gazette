<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCasualtiesTable extends Migration
{
    /**
     * This function performs the task of creating a new table called campaign_casualties
	 * 
	 * The table contains information of the number of casualties for a campaign, historically
     *
     * @return void
     */
    public function up()
    {
		/**
		 * This function automatically creates the new database table
		 */
        Schema::create('campaign_casualties', function(Blueprint $table) {
            
            /**
             * Create a primary auto-increment primary key called id
             */
            $table->increments('id');
            
            /**
             * Adds record created_at and updated_at timestamps (datetime)
             */
            $table->timestamps();
            
            /**
             * Adds deleted_at as a timestamp. The framework will automatically
             * ignore any populated deleted_at row from any queries that use
             * the standard query engine
             */
            $table->softDeletes();
            
            /**
             * The id of the campaign that the casualties were confirmed for
             */
            $table->integer('campaign_id')->unsigned();
            
            /**
             * Will contain allied 1 or axis 2
             */
            $table->integer('side_id')->unsigned();
            
            /**
             * Will contain 1 = air, 2 = inf, 3 = navy, 4 = armored
             */
            $table->integer('branch_id')->unsigned();
            
            /**
             * How many kills between period start and period end
             */
            $table->integer('kill_count')->unsigned();
            
            /**
             * Start range for this set of data
             */
            $table->datetime('period_start');
            
            /**
             * End range for this set of data
             */
            $table->datetime('period_end');
            
            
        });
    }

    /**
     * Reverse the creation of the new table
     *
     * @return void
     */
    public function down()
    {
        /**
         * Drop the table created in the previous step
         */
        Schema::drop('campaign_casualties');
    }
}
