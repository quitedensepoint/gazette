<?php

namespace Playnet\WwiiOnline\Gazette\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Exception;

use Playnet\WwiiOnline\Gazette\Models\Campaign;


class CampaignStartCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gazette:campaign_start {campaign_id} {start}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify the gazette that a new campaign has started';

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
		/**
		 * Check the input campaign code
		 */		
		$campaignId = $this->argument('campaign_id');
		
		if($campaignId == null) {
			$this->info('Please enter a number identifying the new campaign id.');
			return 1;
		}
		
		if(!ctype_digit($campaignId)) {
			$this->info('Please enter a number for the new campaign as the first argument');
			return 1;
		}
		
		/**
		 * Pull in and parse a DateTime string
		 */
		$startArg = $this->argument('start');
		
		try {
			$start = Carbon::parse($startArg);
		} 
		catch (Exception $ex) {
			$this->error('Could not parse the start time of the campaign - try the format "yyyy-mm-dd HH:mm:ss"');
			return 1;
		}

		/**
		 * Now check if the campaign is already in the system
		 */
		$campaign = Campaign::find($campaignId);
		
		if($campaign != null) {
			$this->info(sprintf('Campaign %s already exists in the database!', $campaignId));
			return 1;
		}
		
		/**
		 * Check if there is already a running campaign
		 */		
		if(Campaign::where('status', 'Running')->count() > 0) {
			$this->info(sprintf('There is already a running campaign! Use gazette:stop to end the running campaign.'));
			return 1;			
		}
		
		/**
		 * Get the last stopped campaign and check that our new start is after that stop
		 * 
		 * @var $lastCampaign Campaign
		 */
		$lastCampaign = Campaign::orderBy('stop_time', 'desc')->first();
		
		if($start < $lastCampaign->stop_time)
		{
			$this->info(sprintf('Start date must be greater than the stop time of the last campaign: %s', $lastCampaign->stop_time));
			return 1;			
		}
		
		
		/**
		 * Create the new campaign and set it to running
		 */
		$newCampaign = Campaign::create([
			'campaign_id'	=> $campaignId,
			'status'		=> 'Running',
			'start_time'	=> $start->toW3cString(),
			'stop_time'		=> null
		]);
		
		$this->info(sprintf('Campaign %s has been marked as started!', $newCampaign->campaign_id));
		
		return 0;
    }
}
