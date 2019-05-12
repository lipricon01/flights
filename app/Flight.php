<?php

namespace App;

use App\Helpers\FormatterHelper;
use App\Helpers\HttpClientHelper;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use App\Route;

/**
 * @property int route_id
 * @property string flightJson
 * @property Route getRoute
 */
class Flight extends Model
{
    protected $table = 'flights';

    protected $fillable = [
        'route_id',
        'flightJson',
    ];


    public function getRoute()
    {
        return $this->belongsTo(Route::class, 'route_id', 'id');
    }

    /**
     * Get flights by route
     * @param \App\Route $route
     * @return boolean
     */
    public function getFlights(Route $route)
    {
        $response = (new HttpClientHelper($route->id))->getFlights($route->directionFrom->code, $route->directionTo->code);
        $body = FormatterHelper::formatBody($response);
        $allFlights = $body->data;

        $flightsArray = [];
        foreach ($allFlights as $index => $flight) {
            $date = Carbon::createFromTimestamp($flight->dTimeUTC)->toDateString();
            $flightsArray[$date][] = $flight;
        }

        $cheapestArray = $this->getCheapestFlights($flightsArray);
        $flights = $this->validateFlights($cheapestArray, $route->id);
        $flight = self::where(['route_id' => $route->id])->first();
        if (is_null($flight)) {
            $flight = new Flight();
            $flight->route_id = $route->id;
        }
        $flight->flightJson = $flights;
        if ($flight->save()) {
            return true;
        }

        return false;
    }


    /**
     * find and form array of Cheapest Flights
     * @param $flightsArray
     * @return array
     */
    public function getCheapestFlights($flightsArray)
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


    public function validateFlights($flightsArray, $route_id)
    {
        $urlArray = [];
        //form array
        foreach ($flightsArray as $index => $item) {
            $urlArray[] =
                [
                    'date' => $index,
                    'price' => $item['price'],
                    'booking_token' => $item['booking_token'],
                    'url' => 'https://booking-api.skypicker.com/api/v0.1/check_flights?v=2&booking_token=' . $item['booking_token'] . '&currency=USD&&adults=1&children=0&infants=1&bnum=1'
                ];
        }

        //sleep 5 потому что api не выдерживает ассинхронные запросы, мб еще уыеличится
        sleep(5);
        $response = (new HttpClientHelper($route_id))->sendToValidate(collect($urlArray)->chunk(5));


        return json_encode($response);
    }


}
