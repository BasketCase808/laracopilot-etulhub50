<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BitcoinWalletController;
use App\Http\Controllers\Api\BitcoinTransactionController;
use App\Http\Controllers\Api\ClientAuthController;

// Client Authentication
Route::post('/auth/register', [ClientAuthController::class, 'register']);
Route::post('/auth/login', [ClientAuthController::class, 'login']);

// Bitcoin Wallet Operations (Protected)
Route::middleware('api.client')->group(function () {
    // Wallet Management
    Route::get('/wallet/balance', [BitcoinWalletController::class, 'getBalance']);
    Route::post('/wallet/address/new', [BitcoinWalletController::class, 'generateAddress']);
    Route::get('/wallet/addresses', [BitcoinWalletController::class, 'listAddresses']);
    Route::post('/wallet/address/validate', [BitcoinWalletController::class, 'validateAddress']);
    
    // Transaction Operations
    Route::post('/transaction/send', [BitcoinTransactionController::class, 'sendBitcoin']);
    Route::get('/transaction/{txid}', [BitcoinTransactionController::class, 'getTransaction']);
    Route::get('/transactions', [BitcoinTransactionController::class, 'listTransactions']);
    Route::get('/transaction/{txid}/confirmations', [BitcoinTransactionController::class, 'getConfirmations']);
    
    // Wallet Info
    Route::get('/wallet/info', [BitcoinWalletController::class, 'getWalletInfo']);
    Route::get('/network/info', [BitcoinWalletController::class, 'getNetworkInfo']);
});