<?php

namespace App\Console\Commands;

use App\CheckFlight;
use App\Flight;
use App\Helpers\FormatterHelper;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class CheckFlights extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check-flights';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Проверить полеты у который статус check_flights = false';

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
     * @return mixed
     */
    public function handle()
    {
        //
        $client = new Client();
        $models = CheckFlight::all();
        foreach ($models as $model) {
            try {
                $response = $client->get('https://booking-api.skypicker.com/api/v0.1/check_flights?v=2&booking_token=' . $model->booking_token . '&currency=USD&&adults=1&children=0&infants=1&bnum=1');
                $body = FormatterHelper::formatBody($response);
                if ($body->flights_checked === true) {
                    $flight = Flight::where(['route_id' => $model->route_id])->first();
                    $flightArray = json_decode($flight->flightJson);
                    foreach ($flightArray as $index => $item) {
                        if ($item->booking_token === $model->booking_token) {
                            $flightArray[$index]->flights_checked = $body->flights_checked;
                        }
                    }
                    $flight->flightJson = json_encode($flightArray);
                    $flight->save();
                    $model->delete();
                }
            } catch (\Exception $exception) {
                $this->error('Something gone wrong');
                $this->error($exception->getMessage());
                return 2;
            }
        }
        $this->info('Flights have been updated');
        return 0;
    }
}
