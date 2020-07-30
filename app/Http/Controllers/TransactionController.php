<?php

namespace App\Http\Controllers;

use App\Transaction;
use App\Wallet;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

const PAXFULL_ID = 1;

class TransactionController extends Controller
{
    public function create(Request $request)
    {

        if ($request->amount <= 0) {
            return \response()->json([
                'message' => 'Amount less than zero'
            ], Response::HTTP_BAD_REQUEST);
        }

        $fromWallet = Wallet::where('address', $request->from_address)->first();
        $toWallet = Wallet::where('address', $request->to_address)->first();

        if (!$fromWallet || !$toWallet) {
            return \response()->json([
                'message' => 'Unknown wallet address'
            ], Response::HTTP_BAD_REQUEST);
        }
        if ($fromWallet->balance < $request->amount){
           return response()->json([
               'message' => 'Not enough money'
           ], Response::HTTP_BAD_REQUEST);
        }

        DB::transaction(function () use ($fromWallet,$toWallet,$request){
            $comission = $fromWallet->user_id == $toWallet->user_id ? 0 : $request->amount*0.15;

            if ($comission > 0) {
                $paxfulWallet = Wallet::where('id', PAXFULL_ID)->first();
                $paxfulWallet->balance += $comission;
                $paxfulWallet->save();
            }
            $fromWallet->balance -= $request->amount;
            $toWallet->balance += $request->amount - $comission;

            $fromWallet->save();
            $toWallet->save();

            Transaction::create([
                'from_address' => $fromWallet->address,
                'to_address' => $toWallet->address,
                'sum' => $request->amount
            ]);
        });


        return response()->json([
            'message' => 'Transaction created successfully'
        ], Response::HTTP_OK);
    }


    public function searchByUser ()
    {
        foreach (Auth::user()->wallets()->get() as $wallet){
            $transactions = Transaction::select('from_address', 'to_address', 'sum')
                ->where('from_address',$wallet->address)
                ->orWhere('to_address', $wallet->address)
                ->get();
        }

        if (!$transactions) {
            return \response()->json([
                'message' => 'User nasn\'t any transactions'
            ], Response::HTTP_BAD_REQUEST);
        }

        return \response()->json($transactions,Response::HTTP_OK);
    }

    public function searchByWallet (Request $request)
    {

        $wallet = Wallet::where('address', $request->address)->first();

        if (!$wallet) {
            return \response()->json([
                'message' => 'Unknown wallet address'
            ], Response::HTTP_BAD_REQUEST);
        }

        $transactions = Transaction::select('from_address', 'to_address', 'sum')
            ->where('from_address', $wallet->address)
            ->orWhere('to_address', $wallet->address)
            ->get();

        return \response()->json($transactions, Response::HTTP_OK);

    }
}
