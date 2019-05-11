<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property string code
 * @property string title
 * @property string created_at
 * @property string updated_at
 */
class Direction extends Model
{
    protected $table = 'directions';

    protected $fillable =[
        'title',
        'code'
    ];
}
