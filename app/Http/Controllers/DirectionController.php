<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DirectionController extends Controller
{
    //

    public function actionIndex()
    {
        return view('directions');
    }
}
