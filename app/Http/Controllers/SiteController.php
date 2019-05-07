<?php
/**
 * Created by PhpStorm.
 * User: vladislavkim
 * Date: 5/7/19
 * Time: 15:33
 */

namespace App\Http\Controllers;


use Carbon\Carbon;
use GuzzleHttp\Client;

class SiteController extends Controller
{
    public function index()
    {
        $client = new Client();
        $final_date = Carbon::now()->addMonth('1')->format('j/n/Y');
        $response = $client->get('https://api.skypicker.com/flights?fly_from=ALA&fly_to=MOW&date_from='.date('j/n/Y').'&date_to='.$final_date.'&adults=1&infants=1');
        $body = (string)$response->getBody();
        $allFlights = json_decode($body)->data;
        $cheapestArray = [];
        foreach ($allFlights as $index => $flight) {
            $cheapestArray[date('Y-m-d',$flight->dTime)] = $flight;
        }
        ksort($cheapestArray);
       dd($cheapestArray);die;

    }
}