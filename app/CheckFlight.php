<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CheckFlight
 * @package App
 * @property integer route_id
 * @property string booking_token
 * @property Route getRoute
 */
class CheckFlight extends Model
{
    //
    protected $table = 'check_flights';

    protected $fillable = [
        'route_id',
        'booking_token'
    ];

}
