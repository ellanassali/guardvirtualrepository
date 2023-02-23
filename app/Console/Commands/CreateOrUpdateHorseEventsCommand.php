<?php

namespace App\Console\Commands;

use App\Services\HorsesEventService;
use Illuminate\Console\Command;

class CreateOrUpdateHorseEventsCommand extends Command
{
    public function __construct(HorsesEventService $horsesEventService)
    {
        parent::__construct();
        $this->horseEventService = $horsesEventService;
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitoring:horse-events';

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
        $this->horseEventService->store();
    }
}
