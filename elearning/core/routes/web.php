<?php

use Illuminate\Support\Facades\Route;

Route::get('/clear', function(){
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});


Route::get('cron', 'CronController@cron')->name('cron');




Route::get('app/deposit/confirm/{hash}', 'Gateway\PaymentController@appDepositConfirm')->name('deposit.app.confirm');
Route::get('donation/payment/confirm', 'Gateway\PaymentController@donationDepositConfirm')->name('donation.payment.confirm');
Route::get('donation/payment/manual', 'Gateway\PaymentController@donationManualDepositConfirm')->name('donation.payment.manual.confirm');
Route::post('donation/payment/manual', 'Gateway\PaymentController@donationManualDepositUpdate')->name('donation.payment.manual.update');

Route::get('/seed-gateways', function () {
    // 1. Create Donations Table
    if (!\Illuminate\Support\Facades\Schema::hasTable('donations')) {
        \Illuminate\Support\Facades\Schema::create('donations', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->decimal('amount', 28, 8)->default(0);
            $table->string('full_name')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->text('message')->nullable();
            $table->tinyInteger('payment_status')->default(0)->comment('0: Pending, 1: Success, 2: Rejected');
            $table->string('trx')->nullable();
            $table->timestamps();
        });
    }

    // 2. Add donation_id to Deposits Table
    if (\Illuminate\Support\Facades\Schema::hasTable('deposits')) {
        \Illuminate\Support\Facades\Schema::table('deposits', function (\Illuminate\Database\Schema\Blueprint $table) {
            if (!\Illuminate\Support\Facades\Schema::hasColumn('deposits', 'donation_id')) {
                $table->unsignedBigInteger('donation_id')->default(0);
            }
            // 3. Make user_id nullable in Deposits Table for guest donations
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });
    }

    // Basic Gateway Seeder
    $gateways = [
        [
            'code' => 101,
            'name' => 'PayPal',
            'alias' => 'PayPal',
            'image' => 'paypal.png',
            'status' => 1,
            'description' => 'Pay with PayPal',
            'currencies' => [
                'USD' => [
                    'min_amount' => 1,
                    'max_amount' => 10000,
                    'percent_charge' => 2.5,
                    'fixed_charge' => 0.5,
                    'rate' => 1,
                    'symbol' => '$'
                ]
            ]
        ],
        [
            'code' => 102,
            'name' => 'Stripe',
            'alias' => 'Stripe',
            'image' => 'stripe.png',
            'status' => 1,
            'description' => 'Pay with Stripe',
            'currencies' => [
                'USD' => [
                    'min_amount' => 1,
                    'max_amount' => 10000,
                    'percent_charge' => 1.5,
                    'fixed_charge' => 0.3,
                    'rate' => 1,
                    'symbol' => '$'
                ]
            ]
        ]
    ];

    foreach ($gateways as $gatewayData) {
        $gateway = \App\Models\Gateway::firstOrNew(['code' => $gatewayData['code']]);
        $gateway->name = $gatewayData['name'];
        $gateway->alias = $gatewayData['alias'];
        $gateway->image = $gatewayData['image'];
        $gateway->status = $gatewayData['status'];
        $gateway->description = $gatewayData['description'];
        $gateway->parameters = json_encode([]); 
        $gateway->save();

        foreach ($gatewayData['currencies'] as $currency => $currencyData) {
            $gatewayCurrency = \App\Models\GatewayCurrency::firstOrNew([
                'method_code' => $gatewayData['code'],
                'currency' => $currency
            ]);
            $gatewayCurrency->name = $gatewayData['name'];
            $gatewayCurrency->min_amount = $currencyData['min_amount'];
            $gatewayCurrency->max_amount = $currencyData['max_amount'];
            $gatewayCurrency->percent_charge = $currencyData['percent_charge'];
            $gatewayCurrency->fixed_charge = $currencyData['fixed_charge'];
            $gatewayCurrency->rate = $currencyData['rate'];
            $gatewayCurrency->symbol = $currencyData['symbol'];
            $gatewayCurrency->method_code = $gatewayData['code'];
            $gatewayCurrency->currency = $currency;
            $gatewayCurrency->save();
        }
    }
    return "Gateways Seeded Successfully: " . \App\Models\Gateway::count();
});



Route::controller('SiteController')->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('placeholder-image/{size}', 'placeholderImage')->withoutMiddleware('maintenance')->name('placeholder.image');
 
});

