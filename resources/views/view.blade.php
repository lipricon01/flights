<?php
/**
 * Created by PhpStorm.
 * User: vladislavkim
 * Date: 5/11/19
 * Time: 12:33
 */
?>

<!-- Fonts -->
<link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

<!-- Styles -->
<style>
    html, body {
        background-color: #fff;
        color: #636b6f;
        font-family: 'Nunito', sans-serif;
        font-weight: 200;
        height: 100vh;
        margin: 0;
    }

    .full-height {
        height: 100vh;
    }

    .flex-center {
        align-items: center;
        display: flex;
        justify-content: center;
    }

    .position-ref {
        position: relative;
    }

    .top-right {
        position: absolute;
        right: 10px;
        top: 18px;
    }

    .content {
        text-align: center;
    }

    .title {
        font-size: 84px;
    }

    .links > a {
        color: #636b6f;
        padding: 0 25px;
        font-size: 13px;
        font-weight: 600;
        letter-spacing: .1rem;
        text-decoration: none;
        text-transform: uppercase;
    }

    .m-b-md {
        margin-bottom: 30px;
    }
</style>
<a href="/">Home</a>
<h1>
    {{ $model->getRoute->directionFrom->title .' to '. $model->getRoute->directionTo->title }}
</h1>
@foreach(json_decode($model->flightJson) as $flight)
    <div class="">
        Date - <b> {{ $flight->date }}</b>
        Price - <b> {{ $flight->price}} </b>
        Price change - <b>{{ $flight->price_change === false ? 'false' : 'true'}}</b>
        Flight checked - <b>{{ $flight->flights_checked  === false ? 'false' : 'true'}}</b>
        Flight invalid - <b>{{ $flight->flights_invalid  === false ? 'false' : 'true'}}</b>
    </div>
@endforeach
