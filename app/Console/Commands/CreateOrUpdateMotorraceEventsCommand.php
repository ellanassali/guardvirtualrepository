<?php

namespace App\Console\Commands;

use App\Services\MotorraceEventService;
use Illuminate\Console\Command;

class CreateOrUpdateMotorraceEventsCommand extends Command
{
    public function __construct(MotorraceEventService $motorraceEventService)
    {
        parent::__construct();
        $this->motorraceEventService = $motorraceEventService;
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitoring:motor-events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
       $this->motorraceEventService->store();
    }
}
