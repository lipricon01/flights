<?php

namespace App;

use App\Helpers\FormatterHelper;
use App\Helpers\HttpClientHelper;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use App\Route;
/**
 * @property int direction_id
 * @property string flightJson
 */
class Flight extends Model
{
    protected $fillable =[
            'direction_id',
            'flightJson',
        ];


    public  function getFlights(Route $route)
    {
        $response = HttpClientHelper::getFlights($route->directionFrom->code, $route->directionTo->code);
        $body = FormatterHelper::formatBody($response);
        $allFlights = $body->data;
        $flightsArray = [];
        foreach ($allFlights as $index => $flight) {
            $date = Carbon::createFromTimestamp($flight->dTimeUTC)->toDateString();
            $flightsArray[$date][] = $flight;
        }

        $cheapestArray =$this->getCheapestFlights($flightsArray);

        $flight = self::where(['direction_id' => $route->id])->first();
        if (is_null($flight)) {
            $flight = new Flight();
            $flight->direction_id = $route->id;
        }
        $flight->flightJson = $this->validateFlights($cheapestArray);
        $flight->save();

        return $cheapestArray;
    }

    //find and form array of Cheapest Flights
    public  function getCheapestFlights($flightsArray)
    {
        $cheapestArray = [];
        foreach ($flightsArray as $index => $items) {
            foreach ($items as $item) {
                if ($item->price === min(array_column($items, 'price'))) {
                    $cheapestArray[$index] = ['price' => $item->price, 'booking_token' => $item->booking_token];
                }
            }
        }
        ksort($cheapestArray);
        return $cheapestArray;
    }


    public function validateFlights($flightsArray)
    {
        $totalArray = [];
        foreach ($flightsArray as $index => $item) {
            $response = HttpClientHelper::validateFlights($item['booking_token']);
            $data = FormatterHelper::formatBody($response);
            $totalArray[$index] = [
                'price' => $item['price'],
                'booking_token' => $item['booking_token'],
                'flights_invalid' => $data->flights_invalid,
                'price_change' => $data->price_change,
                'flights_checked' => $data->flights_checked
            ];
        }
        return json_encode($totalArray);
    }


}
