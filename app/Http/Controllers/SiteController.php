<?php
/**
 * Created by PhpStorm.
 * User: vladislavkim
 * Date: 5/7/19
 * Time: 15:33
 */

namespace App\Http\Controllers;

use App\Flight;
use App\Route;

class SiteController extends Controller
{

    public function index()
    {

        $models = Flight::all();

        return view('welcome', compact('models'));
    }

    public
    function view($id)
    {

        $model = Flight::where(['id' => $id])->first();

        return view('view', compact('model'));

    }

}