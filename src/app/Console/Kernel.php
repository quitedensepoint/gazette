<?php

namespace Playnet\WwiiOnline\Gazette\Console;

use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * These are the command files used by the application. If you create a new
	 * command, add it here so it can be found
     *
     * @var array
     */
    protected $commands = [
		\Playnet\WwiiOnline\Gazette\Console\Commands\CampaignStartCommand::class,	
    ];

}
