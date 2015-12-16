<?php

namespace Playnet\WwiiOnline\Gazette\Console\Commands;

use Illuminate\Console\Command;

use Playnet\WwiiOnline\Gazette\Models\Campaign;

use Carbon\Carbon;

class CampaignStopCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gazette:campaign_stop {stop}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Stop a running campaign';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
	 * 
	 * From the command line, will return a value of 0 for success, 1 for failure.
	 * This is standard unix script convention and will allow syadmins to alert
	 * when this process fails.
	 * 
     * @return mixed
     */
    public function handle()
    {
		$stopArg = $this->argument('stop');
		
		$stop = null;
		try {
			$stop = Carbon::parse($stopArg);
		} 
		catch (Exception $ex) {
			$this->error('Could not parse the stop time of the campaign - try the format "yyyy-mm-dd HH:mm:ss"');
			return 1;
		}		
		
		/**
		 * Retrieve all the campaigns that are marked as 'Running'
		 * 
		 * There should only be one
		 */
		
		/* @var $runningCampaigns \Illuminate\Database\Eloquent\Collection */
		$runningCampaigns = Campaign::where('status', Campaign::CAMPAIGN_STATUS_RUNNING)->get();
				
		if($runningCampaigns->count() > 1) {
			$this->info(sprintf('DATA CONSISTENCY ERROR: There appears to be %s campaigns running at the moment!', $runningCampaigns->count()));
			return 1;			
		}
		
		if($runningCampaigns->count() == 0) {
			$this->info('There are no campaigns running at the moment.');
			return 1;			
		}		
		
		/* @var $campaign Campaign */
        $campaign = $runningCampaigns->first();
		
		$campaign->status = Campaign::CAMPAIGN_STATUS_COMPLETED;
		$campaign->stop_time = $stop;
		$campaign->save();
		
		return 0;
    }
}
