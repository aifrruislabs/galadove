<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoginLog extends Model
{
    //Fillables
    protected $fillable = [

    	'userId', 'osSys', 'browser', 'ip', 'country',

    	'city', 'location', 'latitude', 'longitude'

    ];
}
