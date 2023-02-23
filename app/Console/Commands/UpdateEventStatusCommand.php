<?php

namespace App\Console\Commands;

use App\Models\Raceevent;
use App\Services\DogEventService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class UpdateEventStatusCommand extends Command
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
    protected $signature = 'monitoring:status-change';

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
        $dogEvents = Http::get('http://vseintegration.kironinteractive.com:8013/VseGameServer/DataService/UpcomingEvents?type=PlatinumHounds');
        $xml = simplexml_load_string($dogEvents->body());
        $json = json_encode($xml->children());


        foreach (json_decode($json, true) as $items) {
            foreach ($items as $item) {
                $event = Raceevent::where('id', $item['@attributes']['ID'])->first();
                if($event) {
                    $event->eventStatus = $item['@attributes']['EventStatus'];
                    $event->update();
                }
            }
        }
        $horseEvents = Http::get('http://vseintegration.kironinteractive.com:8013/VseGameServer/DataService/UpcomingEvents?type=DashingDerby');
        $xml = simplexml_load_string($horseEvents->body());
        $horse_json = json_encode($xml->children());


        foreach (json_decode($horse_json, true) as $items) {
            foreach ($items as $item) {
                $event = Raceevent::where('id', $item['@attributes']['ID'])->first();
                if($event) {
                    $event->eventStatus = $item['@attributes']['EventStatus'];
                    $event->update();
                }
            }
        }
        $motorRacing = Http::get('http://vseintegration.kironinteractive.com:8013/VseGameServer/DataService/UpcomingEvents?type=MotorRacing');
        $xml = simplexml_load_string($motorRacing->body());
        $motor_json = json_encode($xml->children());


        foreach (json_decode($motor_json, true) as $items) {
            foreach ($items as $item) {
                $event = Raceevent::where('id', $item['@attributes']['ID'])->first();
                if($event) {
                    $event->eventStatus = $item['@attributes']['EventStatus'];
                    $event->update();
                }
            }
        }
    }
}
