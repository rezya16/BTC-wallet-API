<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{

    protected $fillable = [
        'balance', 'address'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generateAddress()
    {
        $permitted_chars = '123456789abcdefghijklmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';
        $length = random_int(26,35);

        $input_length = strlen($permitted_chars);
        $random_string = '1';
        for($i = 0; $i < $length; $i++) {
            $random_character = $permitted_chars[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }

        return $random_string;
    }
}
