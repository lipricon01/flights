<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property Direction directionFrom
 * @property Direction directionTo
 * @property integer id
 * @property integer fly_from
 * @property integer fly_to
 */
class Route extends Model
{
    protected $table = 'routes';

    public function directionFrom()
    {
        return $this->belongsTo(Direction::class, 'fly_from', 'id');
    }
    public function directionTo()
    {
        return $this->belongsTo(Direction::class, 'fly_to', 'id');
    }
}