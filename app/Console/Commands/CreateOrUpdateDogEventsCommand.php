<?php

namespace App\Console\Commands;

use App\Services\DogEventService;
use Illuminate\Console\Command;

class CreateOrUpdateDogEventsCommand extends Command
{
    public function __construct(DogEventService $dogEventService)
    {
        parent::__construct();
        $this->dogEventService = $dogEventService;
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitoring:dog-events';

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
       $this->dogEventService->store();
       $this->horseEventService->store();
       $this->motorraceEventService->store();
       $this->dogEventService->store();
    }
}
