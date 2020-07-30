<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

    protected $fillable = [
        'from_address', 'to_address', 'sum'
    ];


}
