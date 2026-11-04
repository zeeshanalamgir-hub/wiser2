<?php

Route::post('paypal', 'Paypal\ProcessController@ipn')->name('Paypal');
Route::get('paypal-sdk', 'PaypalSdk\ProcessController@ipn')->name('PaypalSdk');
Route::post('perfect-money', 'PerfectMoney\ProcessController@ipn')->name('PerfectMoney');
Route::post('stripe', 'Stripe\ProcessController@ipn')->name('Stripe');
Route::post('stripe-js', 'StripeJs\ProcessController@ipn')->name('StripeJs');
Route::post('stripe-v3', 'StripeV3\ProcessController@ipn')->name('StripeV3');
Route::post('skrill', 'Skrill\ProcessController@ipn')->name('Skrill');
Route::post('paytm', 'Paytm\ProcessController@ipn')->name('Paytm');
Route::post('payeer', 'Payeer\ProcessController@ipn')->name('Payeer');
Route::post('paystack', 'Paystack\ProcessController@ipn')->name('Paystack');
Route::post('voguepay', 'Voguepay\ProcessController@ipn')->name('Voguepay');
Route::get('flutterwave/{trx}/{type}', 'Flutterwave\ProcessController@ipn')->name('Flutterwave');
Route::post('razorpay', 'Razorpay\ProcessController@ipn')->name('Razorpay');
Route::post('instamojo', 'Instamojo\ProcessController@ipn')->name('Instamojo');
Route::get('blockchain', 'Blockchain\ProcessController@ipn')->name('Blockchain');
Route::get('blockio', 'Blockio\ProcessController@ipn')->name('Blockio');
Route::post('coinpayments', 'Coinpayments\ProcessController@ipn')->name('Coinpayments');
Route::post('coinpayments-fiat', 'Coinpayments_fiat\ProcessController@ipn')->name('CoinpaymentsFiat');
Route::post('coingate', 'Coingate\ProcessController@ipn')->name('Coingate');
Route::post('coinbase-commerce', 'CoinbaseCommerce\ProcessController@ipn')->name('CoinbaseCommerce');
Route::get('mollie', 'Mollie\ProcessController@ipn')->name('Mollie');
Route::post('cashmaal', 'Cashmaal\ProcessController@ipn')->name('Cashmaal');

?>
