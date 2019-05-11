<?php
/**
 * Created by PhpStorm.
 * User: vladislavkim
 * Date: 5/10/19
 * Time: 10:23
 */

namespace App\Helpers;


use Carbon\Carbon;
use GuzzleHttp\Client;

class HttpClientHelper
{

    public static function getFlights($from, $to)
    {
        $client = new Client();
        $final_date = Carbon::now()->addMonth('1')->format('j/n/Y');
        $response = $client->get('https://api.skypicker.com/flights?fly_from=' . $from . '&fly_to=' . $to . '&date_from=' . date('j/n/Y') . '&date_to=' . $final_date . '&adults=1&infants=1');

        return $response;
    }

    public static function validateFlights($booking_token)
    {
        $client = new Client();
        $response = $client->get('https://booking-api.skypicker.com/api/v0.1/check_flights?v=2&booking_token=' . $booking_token . '&currency=USD&&adults=1&children=0&infants=1&bnum=1');

        return $response;
    }

}