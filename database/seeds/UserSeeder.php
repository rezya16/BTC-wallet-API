<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Wallet;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $admin = User::create([
            'name' => 'Paxful',
            'email' => 'paxful@mail.com',
            'password' => Hash::make('12345678'),

        ]);

        $admin->wallets()->create([
            'address' => Wallet::generateAddress(),
            'balance' => 0
        ]);
    }
}
