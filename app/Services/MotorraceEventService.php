<?php

namespace App\Services;

use App\Models\Entry;
use App\Models\Forecast;
use App\Models\Forecastresults;
use App\Models\Highlow;
use App\Models\Highlowresults;
use App\Models\Market;
use App\Models\Oddeven;
use App\Models\Oddevenresults;
use App\Models\Placeresults;
use App\Models\Raceevent;
use App\Models\Raceresult;
use App\Models\Racewinresults;
use App\Models\Reverseforecast;
use App\Models\Reverseforecastresults;
use App\Models\Reversetricast;
use App\Models\Reversetricasttresults;
use App\Models\Selection;
use App\Models\Swinger;
use App\Models\Swingerresults;
use App\Models\Tricast;
use App\Models\Tricastresults;
use App\Models\Winandplace;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Http;


class MotorraceEventService
{
    public function getEvents()
    {

    }
    public function store()
    {
        $dogEvents = Http::get('http://vseintegration.kironinteractive.com:8013/VseGameServer/DataService/UpcomingEvents?type=MotorRacing');
        $xml = simplexml_load_string($dogEvents->body());
        $local_time = json_decode(json_encode($xml), true)['@attributes']['LocalTime'];
        $json = json_encode($xml->children());


        foreach (json_decode($json, true) as $items) {
            foreach ($items as $item) {
                $datetime = new DateTime($item['@attributes']['EventTime']);
                $finishtime = new DateTime($item['@attributes']['FinishTime']);
                $fin_time = $finishtime->format('H:i:s');
                $event_time =  $datetime->format('H:i:s');
                $event_date =  $datetime->format('Y-m-d');
                $local_start = date('H:i:s', strtotime($event_time. ' + 3 hours'));
                $local_finish = date('H:i:s', strtotime($fin_time. ' + 3 hours'));
                $event_id = $item['@attributes']['ID'];
                $forecast = Http::get('http://vseintegration.kironinteractive.com:8013/vsegameserver/dataservice/raceeventcombinationodds/' . $event_id . '');
                $forecast_xml = simplexml_load_string($forecast->body());
                $forecast_json = json_encode($forecast_xml->children());
                $forecast_item = json_decode($forecast_json, true)["Forecast"];
                $tricast_item = json_decode($forecast_json, true)["Tricast"];
                $reverseForecast_item = json_decode($forecast_json, true)["ReverseForecast"];
                $reverseTricast_item = json_decode($forecast_json, true)["ReverseTricast"];
                $swinger_item = json_decode($forecast_json, true)["Swinger"];
                $forecast_item = explode("|", $forecast_item);
                $tricast_item = explode("|", $tricast_item);
                $reverseForecast_item = explode("|", $reverseForecast_item);
                $reverseTricast_item = explode("|", $reverseTricast_item);
                $swinger_item = explode("|", $swinger_item);

                if (!Raceevent::where('id', $item['@attributes']['ID'])->first()) {
                    $race_event = new Raceevent();
                    $race_event->id = $item['@attributes']['ID'];
                    $race_event->eventType = $item['@attributes']['EventType'];
                    $race_event->eventNumber = $item['@attributes']['EventNumber'];
                    $race_event->event_time = $event_time;
                    $race_event->event_date = $event_date;
                    $race_event->finishTime = $item['@attributes']['FinishTime'];
                    $race_event->local_eventTimetostart = $local_start;
                    $race_event->local_eventTimetofinish = $local_finish;
                    $race_event->eventStatus = $item['@attributes']['EventStatus'];
                    $race_event->distance = $item['@attributes']['Distance'];
                    $race_event->name = $item['@attributes']['Name'];
                    $race_event->playsPaysOn = $item['@attributes']['PlacePaysOn'];
                    $race_event->localTime = $local_time;
                    $race_event->save();
                } else {
                    $editable_race_event = Raceevent::where('id', $item['@attributes']['ID'])->first();
                    $editable_race_event->id = $item['@attributes']['ID'];
                    $editable_race_event->eventType = $item['@attributes']['EventType'];
                    $editable_race_event->eventNumber = $item['@attributes']['EventNumber'];
                    $editable_race_event->event_time = $event_time;
                    $editable_race_event->event_date = $event_date;
                    $editable_race_event->local_eventTimetostart = $local_start;
                    $editable_race_event->local_eventTimetofinish = $local_finish;
                    $editable_race_event->finishTime = $item['@attributes']['FinishTime'];
                    $editable_race_event->eventStatus = $item['@attributes']['EventStatus'];
                    $editable_race_event->name = $item['@attributes']['Name'];
                    $editable_race_event->playsPaysOn = $item['@attributes']['PlacePaysOn'];
                    $editable_race_event->localTime = $local_time;
                    $editable_race_event->update();
                }
                foreach ($item['Entry'] as $entry) {
                    if (isset($entry)) {
                        if (!Entry::where('id', $entry['@attributes']['ID'])->first()) {
                            $new_entry = new Entry();
                            $new_entry->id = $entry['@attributes']['ID'];
                            $new_entry->draw = $entry['@attributes']['Draw'] ?? null;
                            $new_entry->name = $entry['@attributes']['Name'] ?? null;
                            $new_entry->event_id = $item['@attributes']['ID'];
                            $new_entry->player_id = $entry['@attributes']['ID'];
                            $new_entry->event_number = $item['@attributes']['EventNumber'];
                            $new_entry->event_status = $item['@attributes']['EventStatus'];
                            $new_entry->event_type = $item['@attributes']['EventType'];
                            $new_entry->event_time = $event_time;
                            $new_entry->event_date = $event_date;
                            $new_entry->local_eventTimetostart = $local_start;
                            $new_entry->local_eventTimetofinish = $local_finish;
                            $new_entry->finish_time = $item['@attributes']['FinishTime'];
                            $new_entry->save();
                        } else {
                            $editable_entry = Entry::where('id', $entry['@attributes']['ID'])->first();
                            $editable_entry->id = $entry['@attributes']['ID'];
                            $editable_entry->draw = $entry['@attributes']['Draw'];
                            $editable_entry->name = $entry['@attributes']['Name'];
                            $editable_entry->event_id = $item['@attributes']['ID'];
                            $editable_entry->player_id = $entry['@attributes']['ID'];
                            $editable_entry->event_number = $item['@attributes']['EventNumber'];
                            $editable_entry->event_type = $item['@attributes']['EventType'];
                            $editable_entry->event_status = $item['@attributes']['EventStatus'];
                            $editable_entry->event_time = $event_time;
                            $editable_entry->event_date = $event_date;
                            $editable_entry->local_eventTimetostart = $local_start;
                            $editable_entry->local_eventTimetofinish = $local_finish;
                            $editable_entry->finish_time = $item['@attributes']['FinishTime'];
                            $editable_entry->update();
                        }
                    }
                }
                foreach ($item['Market'] as $market) {
                    if (!Market::where('id', $market['@attributes']['ID'])->first()) {
                        $new_market = new Market();
                        $new_market->id = $market['@attributes']['ID'];
                        $new_market->event_number = $item['@attributes']['EventNumber'];
                        $new_market->event_type = $item['@attributes']['EventType'];
                        $new_market->event_time = $event_time;
                        $new_market->event_date = $event_date;
                        $new_market->local_eventTimetostart = $local_start;
                        $new_market->local_eventTimetofinish = $local_finish;
                        $new_market->finish_time = $item['@attributes']['FinishTime'];

                        $new_market->save();
                    } else {
                        $editable_market = Market::where('id', $market['@attributes']['ID'])->first();
                        $editable_market->id = $market['@attributes']['ID'];
                        $editable_market->event_number = $item['@attributes']['EventNumber'];
                        $editable_market->event_type = $item['@attributes']['EventType'];
                        $editable_market->event_time = $event_time;
                        $editable_market->event_date = $event_date;
                        $editable_market->local_eventTimetostart = $local_start;
                        $editable_market->local_eventTimetofinish = $local_finish;
                        $editable_market->finish_time = $item['@attributes']['FinishTime'];
                        $editable_market->update();
                    }
                    foreach ($market['Selection'] as $selection) {
                        if ($market['@attributes']['ID'] == "Win") {
                            $entry = Entry::where('event_number', $item['@attributes']['EventNumber'])->where('event_id', $item['@attributes']['ID'])->where('draw', $selection['@attributes']['ID'])->first();
                            $winandplace = new Winandplace();
                            $winandplace->win_odd = $selection['@attributes']['Odds'] ?? null;
                            $winandplace->event_id = $item['@attributes']['ID'];
                            $winandplace->event_no = $item['@attributes']['EventNumber'];
                            $winandplace->event_type = $item['@attributes']['EventType'];
                            $winandplace->event_time = $event_time;
                            if($entry) {
                                $winandplace->player_id = $entry->id;
                            }
                            $winandplace->event_date = $event_date;
                            $winandplace->local_eventTimetostart = $local_start;
                            $winandplace->local_eventTimetofinish = $local_finish;
                            $winandplace->finish_time = $item['@attributes']['FinishTime'];
                            $winandplace->event_status = $item['@attributes']['EventStatus'];
                            $winandplace->draw = $selection['@attributes']['ID'];
                            if ($entry) {
                                $winandplace->name = $entry->name;
                            }
                            $winandplace->save();
                        }
                        if ($market['@attributes']['ID'] == "Place") {
                            $winandplace = Winandplace::where('event_no', $item['@attributes']['EventNumber'])->where('event_id', $item['@attributes']['ID'])->where('draw', $selection['@attributes']['ID'])->first();
                            $entry = Entry::where('event_number', $item['@attributes']['EventNumber'])->where('event_id', $item['@attributes']['ID'])->where('draw', $selection['@attributes']['ID'])->first();
                            $winandplace->place_odd = $selection['@attributes']['Odds'] ?? null;
                            $winandplace->event_id = $item['@attributes']['ID'];
                            $winandplace->event_no = $item['@attributes']['EventNumber'];
                            $winandplace->event_type = $item['@attributes']['EventType'];
                            if($entry) {
                                $winandplace->player_id = $entry->id;
                            }
                            $winandplace->event_time = $event_time;
                            $winandplace->event_date = $event_date;
                            $winandplace->local_eventTimetostart = $local_start;
                            $winandplace->local_eventTimetofinish = $local_finish;
                            $winandplace->finish_time = $item['@attributes']['FinishTime'];
                            $winandplace->event_status = $item['@attributes']['EventStatus'];
                            $winandplace->draw = $selection['@attributes']['ID'];
                            if ($entry) {
                                $winandplace->name = $entry->name;
                            }
                            $winandplace->update();
                        }
                    }
                    if ($market['@attributes']['ID'] == "OE") {
                        if (!Oddeven::where('event_no', $item['@attributes']['EventNumber'])->where('event_id', $item['@attributes']['ID'])->first()) {
                            $new_oddeven = new Oddeven();
                            foreach ($market['Selection'] as $selection) {
                                if ($selection['@attributes']['ID'] === "O") {
                                    $new_oddeven->o_odd = $selection['@attributes']['Odds'] ?? null;
                                }
                                if ($selection['@attributes']['ID'] === "E") {
                                    $new_oddeven->e_odd = $selection['@attributes']['Odds'] ?? null;
                                }
                                $new_oddeven->event_id = $item['@attributes']['ID'];
                                $new_oddeven->event_no = $item['@attributes']['EventNumber'];
                                $new_oddeven->event_type = $item['@attributes']['EventType'];
                                $new_oddeven->event_status = $item['@attributes']['EventStatus'];
                                $new_oddeven->event_time = $event_time;
                                $new_oddeven->event_date = $event_date;
                                $new_oddeven->local_eventTimetostart = $local_start;
                                $new_oddeven->local_eventTimetofinish = $local_finish;
                                $new_oddeven->finish_time = $item['@attributes']['FinishTime'];
                                $new_oddeven->save();
                            }
                        } else {
                            $editable_oddeven = Oddeven::where('event_no', $item['@attributes']['EventNumber'])->where('event_id', $item['@attributes']['ID'])->first();
                            foreach ($market['Selection'] as $selection) {
                                if ($selection['@attributes']['ID'] === "O") {
                                    $editable_oddeven->o_odd = $selection['@attributes']['Odds'] ?? null;
                                }
                                if ($selection['@attributes']['ID'] === "E") {
                                    $editable_oddeven->e_odd = $selection['@attributes']['Odds'] ?? null;
                                }
                                $editable_oddeven->event_id = $item['@attributes']['ID'];
                                $editable_oddeven->event_no = $item['@attributes']['EventNumber'];
                                $editable_oddeven->event_type = $item['@attributes']['EventType'];
                                $editable_oddeven->event_status = $item['@attributes']['EventStatus'];
                                $editable_oddeven->event_time = $event_time;
                                $editable_oddeven->event_date = $event_date;
                                $editable_oddeven->local_eventTimetostart = $local_start;
                                $editable_oddeven->local_eventTimetofinish = $local_finish;
                                $editable_oddeven->finish_time = $item['@attributes']['FinishTime'];
                                $editable_oddeven->update();
                            }

                        }
                    }
                    if ($market['@attributes']['ID'] == "HL") {
                        if (!Highlow::where('event_no', $item['@attributes']['EventNumber'])->where('event_id', $item['@attributes']['ID'])->first()) {
                            $new_highlow = new Highlow();
                            foreach ($market['Selection'] as $selection) {
                                if ($selection['@attributes']['ID'] === "H") {
                                    $new_highlow->h_odd = $selection['@attributes']['Odds'] ?? null;
                                }
                                if ($selection['@attributes']['ID'] === "L") {
                                    $new_highlow->l_odd = $selection['@attributes']['Odds'] ?? null;
                                }
                                $new_highlow->event_id = $item['@attributes']['ID'];
                                $new_highlow->event_no = $item['@attributes']['EventNumber'];
                                $new_highlow->event_status = $item['@attributes']['EventStatus'];
                                $new_highlow->event_type = $item['@attributes']['EventType'];
                                $new_highlow->event_time = $event_time;
                                $new_highlow->event_date = $event_date;
                                $new_highlow->local_eventTimetostart = $local_start;
                                $new_highlow->local_eventTimetofinish = $local_finish;
                                $new_highlow->finish_time = $item['@attributes']['FinishTime'];

                                $new_highlow->save();
                            }
                        } else {
                            $editable_highlow = Highlow::where('event_no', $item['@attributes']['EventNumber'])->where('event_id', $item['@attributes']['ID'])->first();
                            foreach ($market['Selection'] as $selection) {
                                if ($selection['@attributes']['ID'] === "H") {
                                    $editable_highlow->h_odd = $selection['@attributes']['Odds'] ?? null;
                                }
                                if ($selection['@attributes']['ID'] === "L") {
                                    $editable_highlow->l_odd = $selection['@attributes']['Odds'] ?? null;
                                }
                                $editable_highlow->event_id = $item['@attributes']['ID'];
                                $editable_highlow->event_no = $item['@attributes']['EventNumber'];
                                $editable_highlow->event_type = $item['@attributes']['EventType'];
                                $editable_highlow->event_time = $event_time;
                                $editable_highlow->event_date = $event_date;
                                $editable_highlow->local_eventTimetostart = $local_start;
                                $editable_highlow->local_eventTimetofinish = $local_finish;
                                $editable_highlow->event_status = $item['@attributes']['EventStatus'];
                                $editable_highlow->finish_time = $item['@attributes']['FinishTime'];
                                $editable_highlow->update();
                            }
                        }
                    }
                    foreach ($market['Selection'] as $selection) {
                        $entry = Entry::where('event_number', $item['@attributes']['EventNumber'])->where('event_id', $item['@attributes']['ID'])->where('draw', $selection['@attributes']['ID'])->first();
                        $new_selection = new Selection();
                        $new_selection->odds = $selection['@attributes']['Odds'] ?? null;
                        $new_selection->event_id = $item['@attributes']['ID'];
                        $new_selection->event_number = $item['@attributes']['EventNumber'];
                        $new_selection->event_type = $item['@attributes']['EventType'];
                        $new_selection->player_id = $entry->id ?? null;
                        $new_selection->event_time = $event_time;
                        $new_selection->event_date = $event_date;
                        $new_selection->local_eventTimetostart = $local_start;
                        $new_selection->local_eventTimetofinish = $local_finish;
                        $new_selection->finish_time = $item['@attributes']['FinishTime'];
                        $new_selection->market = $market['@attributes']['ID'];
                        $new_selection->selection_id = $selection['@attributes']['ID'];
                        $new_selection->save();
                    }
                    $forecast_items = $this->split_data($forecast_item);
                    foreach ($forecast_items as $forecast) {
                        if (!Forecast::where('odd', $forecast[1])->where('name', $forecast[0])->first()) {
                            $new_forecast = new Forecast();
                            $new_forecast->odd = $forecast[1];
                            $new_forecast->name = $forecast[0];
                            $new_forecast->event_no = $item['@attributes']['EventNumber'];
                            $new_forecast->event_type = $item['@attributes']['EventType'];
                            $new_forecast->event_time = $event_time;
                            $new_forecast->event_date = $event_date;
                            $new_forecast->local_eventTimetostart = $local_start;
                            $new_forecast->local_eventTimetofinish = $local_finish;
                            $new_forecast->finish_time = $item['@attributes']['FinishTime'];
                            $new_forecast->event_id = $item['@attributes']['ID'];
                            $new_forecast->save();
                        } else {
                            $editable_forecast = Forecast::where('odd', $forecast[1])->where('name', $forecast[0])->first();
                            $editable_forecast->odd = $forecast[1];
                            $editable_forecast->name = $forecast[0];
                            $editable_forecast->event_no = $item['@attributes']['EventNumber'];
                            $editable_forecast->event_type = $item['@attributes']['EventType'];
                            $editable_forecast->event_time = $event_time;
                            $editable_forecast->event_date = $event_date;
                            $editable_forecast->local_eventTimetostart = $local_start;
                            $editable_forecast->local_eventTimetofinish = $local_finish;
                            $editable_forecast->finish_time = $item['@attributes']['FinishTime'];
                            $editable_forecast->event_id = $item['@attributes']['ID'];

                            $editable_forecast->update();
                        }
                    }
                    $tricast_items = $this->split_data($tricast_item);
                    foreach ($tricast_items as $tricast) {
                        if (!Tricast::where('odd', $tricast[1])->where('name', $tricast[0])->first()) {
                            $new_tricast = new Tricast();
                            $new_tricast->odd = $tricast[1];
                            $new_tricast->name = $tricast[0];
                            $new_tricast->event_no = $item['@attributes']['EventNumber'];
                            $new_tricast->event_type = $item['@attributes']['EventType'];
                            $new_tricast->event_time = $event_time;
                            $new_tricast->event_date = $event_date;
                            $new_tricast->local_eventTimetostart = $local_start;
                            $new_tricast->local_eventTimetofinish = $local_finish;
                            $new_tricast->finish_time = $item['@attributes']['FinishTime'];
                            $new_tricast->event_id = $item['@attributes']['ID'];
                            $new_tricast->save();
                        } else {
                            $editable_tricast = Tricast::where('odd', $tricast[1])->where('name', $tricast[0])->first();
                            $editable_tricast->odd = $tricast[1];
                            $editable_tricast->name = $tricast[0];
                            $editable_tricast->event_no = $item['@attributes']['EventNumber'];
                            $editable_tricast->event_type = $item['@attributes']['EventType'];
                            $editable_tricast->event_time = $event_time;
                            $editable_tricast->event_date = $event_date;
                            $editable_tricast->local_eventTimetostart = $local_start;
                            $editable_tricast->local_eventTimetofinish = $local_finish;
                            $editable_tricast->finish_time = $item['@attributes']['FinishTime'];
                            $editable_tricast->event_id = $item['@attributes']['ID'];
                            $editable_tricast->update();
                        }
                    }
                    $reverseForecast_items = $this->split_data($reverseForecast_item);
                    foreach ($reverseForecast_items as $reverseForecast) {
                        if (!Reverseforecast::where('odd', $reverseForecast[1])->where('name', $reverseForecast[0])->first()) {
                            $new_reversed_forecast = new Reverseforecast();
                            $new_reversed_forecast->odd = $reverseForecast[1];
                            $new_reversed_forecast->name = $reverseForecast[0];
                            $new_reversed_forecast->event_no = $item['@attributes']['EventNumber'];
                            $new_reversed_forecast->event_type = $item['@attributes']['EventType'];
                            $new_reversed_forecast->event_time = $event_time;
                            $new_reversed_forecast->event_date = $event_date;
                            $new_reversed_forecast->local_eventTimetostart = $local_start;
                            $new_reversed_forecast->local_eventTimetofinish = $local_finish;
                            $new_reversed_forecast->finish_time = $item['@attributes']['FinishTime'];
                            $new_reversed_forecast->event_id = $item['@attributes']['ID'];
                            $new_reversed_forecast->save();
                        } else {
                            $editable_reversed_forecast = Reverseforecast::where('odd', $reverseForecast[1])->where('name', $reverseForecast[0])->first();
                            $editable_reversed_forecast->odd = $reverseForecast[1];
                            $editable_reversed_forecast->name = $reverseForecast[0];
                            $editable_reversed_forecast->event_no = $item['@attributes']['EventNumber'];
                            $editable_reversed_forecast->event_type = $item['@attributes']['EventType'];
                            $editable_reversed_forecast->event_time = $event_time;
                            $editable_reversed_forecast->event_date = $event_date;
                            $editable_reversed_forecast->local_eventTimetostart = $local_start;
                            $editable_reversed_forecast->local_eventTimetofinish = $local_finish;
                            $editable_reversed_forecast->finish_time = $item['@attributes']['FinishTime'];
                            $editable_reversed_forecast->event_id = $item['@attributes']['ID'];
                            $editable_reversed_forecast->update();
                        }
                    }
                    $reverseTricast_items = $this->split_data($reverseTricast_item);
                    foreach ($reverseTricast_items as $reverseTricast) {
                        if (!Reversetricast::where('odd', $reverseTricast[1])->where('name', $reverseTricast[0])->first()) {
                            $new_reversed_tricast = new Reversetricast();
                            $new_reversed_tricast->odd = $reverseTricast[1];
                            $new_reversed_tricast->name = $reverseTricast[0];
                            $new_reversed_tricast->event_no = $item['@attributes']['EventNumber'];
                            $new_reversed_tricast->event_type = $item['@attributes']['EventType'];
                            $new_reversed_tricast->event_time = $event_time;
                            $new_reversed_tricast->event_date = $event_date;
                            $new_reversed_tricast->local_eventTimetostart = $local_start;
                            $new_reversed_tricast->local_eventTimetofinish = $local_finish;
                            $new_reversed_tricast->finish_time = $item['@attributes']['FinishTime'];
                            $new_reversed_tricast->event_id = $item['@attributes']['ID'];
                            $new_reversed_tricast->save();
                        } else {
                            $editable_reversed_tricast = Reversetricast::where('odd', $reverseTricast[1])->where('name', $reverseTricast[0])->first();
                            $editable_reversed_tricast->odd = $reverseTricast[1];
                            $editable_reversed_tricast->name = $reverseTricast[0];
                            $editable_reversed_tricast->event_no = $item['@attributes']['EventNumber'];
                            $editable_reversed_tricast->event_type = $item['@attributes']['EventType'];
                            $editable_reversed_tricast->event_time = $event_time;
                            $editable_reversed_tricast->event_date = $event_date;
                            $editable_reversed_tricast->local_eventTimetostart = $local_start;
                            $editable_reversed_tricast->local_eventTimetofinish = $local_finish;
                            $editable_reversed_tricast->finish_time = $item['@attributes']['FinishTime'];
                            $editable_reversed_tricast->event_id = $item['@attributes']['ID'];
                            $editable_reversed_tricast->update();
                        }
                    }
                    $swinger_items = $this->split_data($swinger_item);
                    foreach ($swinger_items as $swinger) {
                        if (!Swinger::where('odd', $swinger[1])->where('name', $swinger[0])->first()) {
                            $new_swinger = new Swinger();
                            $new_swinger->odd = $swinger[1];
                            $new_swinger->name = $swinger[0];
                            $new_swinger->event_no = $item['@attributes']['EventNumber'];
                            $new_swinger->event_type = $item['@attributes']['EventType'];
                            $new_swinger->event_time = $event_time;
                            $new_swinger->event_date = $event_date;
                            $new_swinger->local_eventTimetostart = $local_start;
                            $new_swinger->local_eventTimetofinish = $local_finish;
                            $new_swinger->finish_time = $item['@attributes']['FinishTime'];
                            $new_swinger->event_id = $item['@attributes']['ID'];
                            $new_swinger->save();
                        } else {
                            $editable_swinger = Swinger::where('odd', $swinger[1])->where('name', $swinger[0])->first();
                            $editable_swinger->odd = $swinger[1];
                            $editable_swinger->name = $swinger[0];
                            $editable_swinger->event_no = $item['@attributes']['EventNumber'];
                            $editable_swinger->event_type = $item['@attributes']['EventType'];
                            $editable_swinger->event_time = $event_time;
                            $editable_swinger->event_date = $event_date;
                            $editable_swinger->local_eventTimetostart = $local_start;
                            $editable_swinger->local_eventTimetofinish = $local_finish;
                            $editable_swinger->finish_time = $item['@attributes']['FinishTime'];
                            $editable_swinger->event_id = $item['@attributes']['ID'];
                            $editable_swinger->update();
                        }
                    }
                }

                if($finishtime < Carbon::now()) {
                    $event_id = $item['@attributes']['ID'];
                    $results_data = Http::get('http://vseintegration.kironinteractive.com:8013/VseGameServer/DataService/result/'.$event_id.'');
                    $xml = simplexml_load_string($results_data->body());
                    $json = json_encode($xml->children());

                    foreach (json_decode($json, true) as $items) {
                        foreach ($items['Market'] as $market) {
                            if ($market['@attributes']['ID'] === "Forecast") {
                                $results = explode(',', $items['@attributes']['Result']);
                                $forecast_result = new Forecastresults();
                                $forecast_result->event_id = $items['@attributes']['ID'];
                                $forecast_result->event_no = $items['@attributes']['EventNumber'];
                                $forecast_result->event_time = $event_time;
                                $forecast_result->event_date = $event_date;
                                $forecast_result->local_eventTimetostart = $local_start;
                                $forecast_result->local_eventTimetofinish = $local_finish;
                                $forecast_result->event_type = $items['@attributes']['EventType'];
                                $forecast_result->event_finishTime = $items['@attributes']['FinishTime'];
                                $forecast_result->position_one = $results[0];
                                $forecast_result->position_two = $results[1];
                                $forecast_result->selection_id = $market["Selection"]['@attributes']['ID'] ?? null;
                                $forecast_result->odd = $market["Selection"]['@attributes']['Odds'];
                                $forecast_result->save();
                            }
                            if ($market['@attributes']['ID'] === "ReverseForecast") {
                                $results = explode(',', $items['@attributes']['Result']);
                                $reversed_forecast_result = new Reverseforecastresults();
                                $reversed_forecast_result->event_id = $items['@attributes']['ID'];
                                $reversed_forecast_result->event_no = $items['@attributes']['EventNumber'];
                                $reversed_forecast_result->event_time = $event_time;
                                $reversed_forecast_result->event_date = $event_date;
                                $reversed_forecast_result->local_eventTimetostart = $local_start;
                                $reversed_forecast_result->local_eventTimetofinish = $local_finish;
                                $reversed_forecast_result->event_type = $items['@attributes']['EventType'];
                                $reversed_forecast_result->event_finishTime = $items['@attributes']['FinishTime'];
                                $reversed_forecast_result->position_one = $results[0];
                                $reversed_forecast_result->position_two = $results[1];
                                $reversed_forecast_result->selection_id = $market["Selection"]['@attributes']['ID'] ?? null;
                                $reversed_forecast_result->odd = $market["Selection"]['@attributes']['Odds'];
                                $reversed_forecast_result->save();
                            }
                            if ($market['@attributes']['ID'] === "Tricast") {
                                $results = explode(',', $items['@attributes']['Result']);
                                $tricast_result = new Tricastresults();
                                $tricast_result->event_id = $items['@attributes']['ID'];
                                $tricast_result->event_no = $items['@attributes']['EventNumber'];
                                $tricast_result->event_time = $event_time;
                                $tricast_result->event_date = $event_date;
                                $tricast_result->local_eventTimetostart = $local_start;
                                $tricast_result->local_eventTimetofinish = $local_finish;
                                $tricast_result->event_type = $items['@attributes']['EventType'];
                                $tricast_result->event_finishTime = $items['@attributes']['FinishTime'];
                                $tricast_result->position_one = $results[0];
                                $tricast_result->position_two = $results[1];
                                $tricast_result->position_three = $results[2];
                                $tricast_result->selection_id = $market["Selection"]['@attributes']['ID'] ?? null;
                                $tricast_result->odd = $market["Selection"]['@attributes']['Odds'];
                                $tricast_result->save();
                            }
                            if ($market['@attributes']['ID'] === "ReverseTricast") {
                                $results = explode(',', $items['@attributes']['Result']);
                                $reversed_tricast_result = new Reversetricasttresults();
                                $reversed_tricast_result->event_id = $items['@attributes']['ID'];
                                $reversed_tricast_result->event_no = $items['@attributes']['EventNumber'];
                                $reversed_tricast_result->event_time = $event_time;
                                $reversed_tricast_result->event_date = $event_date;
                                $reversed_tricast_result->local_eventTimetostart = $local_start;
                                $reversed_tricast_result->local_eventTimetofinish = $local_finish;
                                $reversed_tricast_result->event_type = $items['@attributes']['EventType'];
                                $reversed_tricast_result->event_finishTime = $items['@attributes']['FinishTime'];
                                $reversed_tricast_result->position_one = $results[0];
                                $reversed_tricast_result->position_two = $results[1];
                                $reversed_tricast_result->position_three = $results[2];
                                $reversed_tricast_result->selection_id = $market["Selection"]['@attributes']['ID'] ?? null;
                                $reversed_tricast_result->odd = $market["Selection"]['@attributes']['Odds'];
                                $reversed_tricast_result->save();
                            }
                            if ($market['@attributes']['ID'] === "Swinger") {
                                foreach ($market["Selection"] as $selection) {
                                    $swinger_result_result = new Swingerresults();
                                    $swinger_result_result->event_id = $items['@attributes']['ID'];
                                    $swinger_result_result->event_no = $items['@attributes']['EventNumber'];
                                    $swinger_result_result->event_time = $event_time;
                                    $swinger_result_result->event_date = $event_date;
                                    $swinger_result_result->local_eventTimetostart = $local_start;
                                    $swinger_result_result->local_eventTimetofinish = $local_finish;
                                    $swinger_result_result->event_type = $items['@attributes']['EventType'];
                                    $swinger_result_result->event_finishTime = $items['@attributes']['FinishTime'];
                                    $swinger_result_result->entry_id = $selection['@attributes']['ID'];
                                    $swinger_result_result->entry_name = $selection['@attributes']['ID'];
                                    $swinger_result_result->win_status = 1;
                                    $swinger_result_result->odd = $selection['@attributes']['Odds'];
                                    $swinger_result_result->selection_id = $selection['@attributes']['ID'] ?? null;
                                    $swinger_result_result->save();
                                }
                            }
                            if ($market['@attributes']['ID'] === "OE") {
                                foreach ($market["Selection"] as $selection) {
                                    if ($market['@attributes']['WinningSelectionIDs'] === $selection['@attributes']['ID']) {
                                        $oddeven_result = new Oddevenresults();
                                        $oddeven_result->event_id = $items['@attributes']['ID'];
                                        $oddeven_result->event_no = $items['@attributes']['EventNumber'];
                                        $oddeven_result->event_time = $event_time;
                                        $oddeven_result->event_date = $event_date;
                                        $oddeven_result->local_eventTimetostart = $local_start;
                                        $oddeven_result->local_eventTimetofinish = $local_finish;
                                        $oddeven_result->event_type = $items['@attributes']['EventType'];
                                        $oddeven_result->event_finishTime = $items['@attributes']['FinishTime'];
                                        $oddeven_result->selection_id = $selection['@attributes']['ID'] ?? null;
                                        $oddeven_result->odd = $selection['@attributes']['Odds'];
                                        $oddeven_result->entry_id = $selection['@attributes']['ID'];
                                        $oddeven_result->win_status = 1;
                                        $oddeven_result->entry_name = $selection['@attributes']['ID'];
                                        $oddeven_result->save();
                                    }
                                }
                            }
                            if ($market['@attributes']['ID'] === "HL") {
                                foreach ($market["Selection"] as $selection) {
                                    if ($market['@attributes']['WinningSelectionIDs'] === $selection['@attributes']['ID']) {
                                        $high_low_results = new Highlowresults();
                                        $high_low_results->event_id = $items['@attributes']['ID'];
                                        $high_low_results->event_no = $items['@attributes']['EventNumber'];
                                        $high_low_results->event_time = $event_time;
                                        $high_low_results->event_date = $event_date;
                                        $high_low_results->local_eventTimetostart = $local_start;
                                        $high_low_results->local_eventTimetofinish = $local_finish;
                                        $high_low_results->event_type = $items['@attributes']['EventType'];
                                        $high_low_results->event_finishTime = $items['@attributes']['FinishTime'];
                                        $high_low_results->selection_id = $selection['@attributes']['ID'] ?? null;
                                        $high_low_results->odd = $selection['@attributes']['Odds'];
                                        $high_low_results->entry_id = $selection['@attributes']['ID'];
                                        $high_low_results->win_status = 1;
                                        $high_low_results->entry_name = $selection['@attributes']['ID'];
                                        $high_low_results->save();
                                    }
                                }
                            }

                        }
                        foreach ($items['Entry'] as $result) {
                            $race_result = new Raceresult();
                            $race_result->event_id = $items['@attributes']['ID'];
                            $race_result->event_no = $items['@attributes']['EventNumber'];
                            $race_result->event_time = $event_time;
                            $race_result->event_date = $event_date;
                            $race_result->local_eventTimetostart = $local_start;
                            $race_result->local_eventTimetofinish = $local_finish;
                            $race_result->event_type = $items['@attributes']['EventType'];
                            $race_result->event_finishTime = $items['@attributes']['FinishTime'];
                            $race_result->playsPaysOn = $items['@attributes']['PlacePaysOn'];
                            $race_result->entry_id = $result['@attributes']['ID'];
                            $race_result->entry_name = $items['@attributes']['Name'];
                            if ($item['@attributes']['PlacePaysOn'] === "2" || $items['@attributes']['PlacePaysOn'] === "3") {
                                if (isset($result['@attributes']["Finish"])) {
                                    $race_result->place_position = "1";
                                } else {
                                    $race_result->place_position = "0";
                                }
                            } else {
                                $race_result->place_position = "0";
                            }
                            $race_result->finish_position = $result['@attributes']['Finish'] ?? null;
                            $race_result->save();

                            foreach ($items['Market'] as $market) {
                                if ($market['@attributes']['ID'] === "Win") {
                                    if (isset($result['@attributes']["Finish"]) && $result['@attributes']["Finish"] === "1") {
                                        foreach ($market["Selection"] as $selection) {
                                            if ($selection['@attributes']['ID'] === $result['@attributes']['Draw']) {
                                                $win_result = new Racewinresults();
                                                if ($market['@attributes']['WinningSelectionIDs'] === "2" && $result['@attributes']["Finish"] === "1") {
                                                    $win_result->win_status = 1;
                                                } else {
                                                    $win_result->win_status = 0;
                                                }
                                                $win_result->event_id = $items['@attributes']['ID'];
                                                $win_result->event_no = $items['@attributes']['EventNumber'];
                                                $win_result->event_time = $event_time;
                                                $win_result->event_date = $event_date;
                                                $win_result->local_eventTimetostart = $local_start;
                                                $win_result->local_eventTimetofinish = $local_finish;
                                                $win_result->event_type = $items['@attributes']['EventType'];
                                                $win_result->event_finishTime = $items['@attributes']['FinishTime'];
                                                $win_result->selection_id = $selection['@attributes']['ID'] ?? null;
                                                $win_result->odd = $selection['@attributes']['Odds'];
                                                $win_result->entry_id = $result['@attributes']['ID'] ?? null;
                                                $win_result->entry_name = $result['@attributes']['Name'] ?? null;
                                                $win_result->save();
                                            }
                                        }
                                    }

                                }
                                if ($market['@attributes']['ID'] === "Place") {
                                    foreach (explode(',', $items['@attributes']['Result']) as $result_number) {
                                        foreach ($market["Selection"] as $selection) {
                                            if ($selection['@attributes']['ID'] === $result_number && $result['@attributes']['Draw'] === $result_number) {
                                                $place_result = new Placeresults();
                                                $place_result->place_status = 1;
                                                $place_result->event_id = $items['@attributes']['ID'];
                                                $place_result->event_no = $items['@attributes']['EventNumber'];
                                                $place_result->event_time = $event_time;
                                                $place_result->event_date = $event_date;
                                                $place_result->local_eventTimetostart = $local_start;
                                                $place_result->local_eventTimetofinish = $local_finish;
                                                $place_result->event_type = $items['@attributes']['EventType'];
                                                $place_result->event_finishTime = $items['@attributes']['FinishTime'];
                                                $place_result->selection_id = $selection['@attributes']['ID'] ?? null;
                                                $place_result->odd = $selection['@attributes']['Odds'];
                                                $place_result->entry_id = $result['@attributes']['ID'] ?? null;
                                                $place_result->entry_name = $result['@attributes']['Name'] ?? null;
                                                $place_result->save();
                                            }
                                        }
                                    }
                                }
                            }

                        }
                    }
                }
            }
        }
    }

    public function split_data($string) {
        $forecast_items = [];
        $new_item = [];
        foreach ($string as $i) {
            if (count($new_item) === 2) {
                $forecast_items []= $new_item;
                $new_item = [];
            } else {
                $new_item []= $i;
            }
        }
        return $forecast_items;
    }
}

