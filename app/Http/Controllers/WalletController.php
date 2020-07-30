<?php

namespace App\Http\Controllers;

use App\Wallet;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{

    public function create()
    {
        $usdCourse = Http::get('https://bitpay.com/api/rates/USD/');
        $user = Auth::user();

        if ($user->wallets_num >= 10){
            return \response()->json(['message' => 'You already have 10 wallets!'], Response::HTTP_NOT_ACCEPTABLE);
        }

        generateAddress:

        $data = [
            'address' => Wallet::generateAddress(),
            'balance' => 1
        ];

        $validator = Validator::make($data,[
            'address' => 'required|unique:wallets',
        ]);

        if ($validator->fails())
        {
            goto generateAddress;
        }

        $wallet = $user->wallets()->create($data);
        $user->wallets_num += 1;
        $user->save();

        return response()->json(['address' => $wallet->address,
            'BTC' => $wallet['balance'],
            'USD' => $wallet->balance*$usdCourse['rate']
        ], Response::HTTP_CREATED);
    }

    public function show(Request $request)
    {
        $wallet = Wallet::where('address', $request->address)->first();
        $usdCourse = Http::get('https://bitpay.com/api/rates/USD/');

        if (\auth()->user()->id == $wallet->user_id){

            return response()->json([
                'id' => $wallet->id,
                'BTC' => $wallet->balance,
                'USD' => $wallet->balance*$usdCourse['rate']
            ], Response::HTTP_OK);
        }

        return response()->json([
            'message' => 'Other user'
        ], Response::HTTP_FORBIDDEN);

    }

}
