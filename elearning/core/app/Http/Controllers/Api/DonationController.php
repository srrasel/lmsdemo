<?php

namespace App\Http\Controllers\Api;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Donation;
use App\Models\GatewayCurrency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DonationController extends Controller
{
    public function donations()
    {
        $donations = Donation::where('user_id', auth()->id())->orderBy('id', 'desc')->paginate(getPaginate());
        $notify[] = 'Donations retrieved successfully';
        return responseSuccess('donations', $notify, [
            'donations' => $donations
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|gt:0',
            'method_code' => 'required',
            'currency' => 'required',
            'full_name' => 'nullable|string',
            'email' => 'nullable|email',
            'mobile' => 'nullable|string',
            'message' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return responseError('validation_error', $validator->errors());
        }

        $user = auth()->guard('sanctum')->user();
        $gate = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', Status::ENABLE);
        })->where('method_code', $request->method_code)->where('currency', $request->currency)->first();

        if (!$gate) {
            $notify[] = 'Invalid gateway';
            return responseError('invalid_gateway', $notify);
        }

        if ($gate->min_amount > $request->amount || $gate->max_amount < $request->amount) {
            $notify[] = 'Please follow deposit limit';
            return responseError('deposit_limit', $notify);
        }

        $charge = $gate->fixed_charge + ($request->amount * $gate->percent_charge / 100);
        $payable = $request->amount + $charge;
        $finalAmount = $payable * $gate->rate;

        // Create Donation
        $donation = new Donation();
        $donation->user_id = $user ? $user->id : null;
        $donation->amount = $request->amount;
        $donation->full_name = $request->full_name ?? ($user ? $user->fullname : '');
        $donation->email = $request->email ?? ($user ? $user->email : '');
        $donation->mobile = $request->mobile ?? ($user ? $user->mobile : '');
        $donation->message = $request->message;
        $donation->payment_status = 0; // Pending
        $donation->trx = getTrx();
        $donation->save();

        // Create Deposit
        $data = new Deposit();
        $data->user_id = $user ? $user->id : null; 
        $data->donation_id = $donation->id;
        $data->method_code = $gate->method_code;
        $data->method_currency = strtoupper($gate->currency);
        $data->amount = $request->amount;
        $data->charge = $charge;
        $data->rate = $gate->rate;
        $data->final_amount = $finalAmount;
        $data->btc_amount = 0;
        $data->btc_wallet = "";
        $data->trx = $donation->trx;
        $data->save(); // Save first to get ID

        $data->success_url = route('deposit.app.confirm', ['hash' => encrypt($data->id)]);
        $data->failed_url = route('home');
        $data->save(); // Update with success URL

        $notify[] = 'Donation initiated successfully';
        return responseSuccess('donation_initiated', $notify, [
            'donation' => $donation,
            'deposit' => $data,
            'redirect_url' => route('deposit.app.confirm', ['hash' => encrypt($data->id)]),
        ]);
    }
}
