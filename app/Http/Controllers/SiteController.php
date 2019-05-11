<?php
/**
 * Created by PhpStorm.
 * User: vladislavkim
 * Date: 5/7/19
 * Time: 15:33
 */

namespace App\Http\Controllers;


use App\Direction;
use App\Flight;
use App\Helpers\HttpClientHelper;
use App\Route;
use Carbon\Carbon;
use GuzzleHttp\Client;

class SiteController extends Controller
{
    public function index()
    {
        $models = Flight::all();

        return view('welcome', compact('models'));
    }
}