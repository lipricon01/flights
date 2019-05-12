<?php
/**
 * Created by PhpStorm.
 * User: vladislavkim
 * Date: 5/10/19
 * Time: 10:23
 */

namespace App\Helpers;


use App\CheckFlight;
use Carbon\Carbon;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Collection;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;

class HttpClientHelper
{

    public $route_id;
    /**
     * Http client.
     *
     * @var Client
     */
    protected $client = null;

    public function __construct($route_id)
    {
        $this->route_id = $route_id;
        $this->client = new Client();
    }

    /**
     * just to get all flights
     * @param $from
     * @param $to
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getFlights($from, $to)
    {
        $final_date = Carbon::now()->addMonth('1')->format('j/n/Y');
        $response = $this->client->get('https://api.skypicker.com/flights?fly_from=' . $from . '&fly_to=' . $to . '&date_from=' . date('j/n/Y') . '&date_to=' . $final_date . '&adults=1&infants=1');
        return $response;
    }

    /**
     * validateFlights
     * @param Collection $urlArray
     * @return Collection
     */
    public function sendToValidate(Collection $urlArray)
    {
        $arrayOfCollections = [];
        foreach ($urlArray as $index => $item) {
            $arrayOfCollections[] = $this->sendPromise($item);
        }

        $totalArray = $this->mergeCollections($arrayOfCollections);

        return $totalArray;
    }

    /**
     * Send assync request to validate flights
     * @param Collection $array
     * @return array $collection
     */
    public function sendPromise(Collection $array)
    {
        $promises = $array
            ->map(function ($item) {
                return $this->client
                    ->getAsync($item['url'])
                    ->then(function ($res) use ($item) {
                        $body = FormatterHelper::formatBody($res);
                        if($body->flights_checked === false){
                            $this->addFlightToCheck($item['booking_token']);
                        }
                        return [
                            'date' => $item['date'],
                            'price' => $item['price'],
                            'booking_token' => $item['booking_token'],
                            'flights_invalid' => $body->flights_invalid,
                            'price_change' => $body->price_change,
                            'flights_checked' => $body->flights_checked
                        ];
                    }, function (RequestException $e) use ($item) {
                        echo $e->getMessage();
                    });
            })->toArray();

        //Promise\settle to continue if any errors
        return collect(Promise\settle($promises)->wait())
            ->filter(function ($item) {
                return isset($item['value']) && $item['value'];
            })
            ->map(function ($item) {
                return $item['value'];
            });
    }


    /**
     * Merge all collections
     * @param $array
     * @return Collection
     */
    public function mergeCollections($array)
    {
        $merged_array = new Collection();
        foreach ($array as $collections) {
            foreach ($collections as $index => $collection) {

                $merged_array->push($collection);
            }
        }
        return $merged_array;
    }

    /**
     * create flight to check
     * @param $booking_token
     * @return bool
     */
    public function addFlightToCheck($booking_token)
    {
        $model = new CheckFlight();
        $model->route_id = $this->route_id;
        $model->booking_token = $booking_token;
        if($model->save()){
            return true;
        }
        return false;
    }
}