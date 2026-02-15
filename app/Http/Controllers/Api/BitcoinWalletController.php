<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BitcoinRpcService;
use Illuminate\Http\Request;

class BitcoinWalletController extends Controller
{
    protected $bitcoinRpc;
    
    public function __construct(BitcoinRpcService $bitcoinRpc)
    {
        $this->bitcoinRpc = $bitcoinRpc;
    }
    
    public function getBalance(Request $request)
    {
        try {
            $balance = $this->bitcoinRpc->call('getbalance');
            
            return response()->json([
                'success' => true,
                'data' => [
                    'balance' => $balance,
                    'balance_btc' => number_format($balance, 8),
                    'client' => $request->input('api_client')->name
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to retrieve balance',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function generateAddress(Request $request)
    {
        $validated = $request->validate([
            'label' => 'nullable|string|max:255'
        ]);
        
        try {
            $address = $this->bitcoinRpc->call('getnewaddress', [
                $validated['label'] ?? ''
            ]);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'address' => $address,
                    'label' => $validated['label'] ?? null,
                    'client' => $request->input('api_client')->name
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to generate address',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function listAddresses(Request $request)
    {
        try {
            $addresses = $this->bitcoinRpc->call('listreceivedbyaddress', [0, true]);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'addresses' => $addresses,
                    'count' => count($addresses)
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to list addresses',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function validateAddress(Request $request)
    {
        $validated = $request->validate([
            'address' => 'required|string'
        ]);
        
        try {
            $validation = $this->bitcoinRpc->call('validateaddress', [
                $validated['address']
            ]);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'address' => $validated['address'],
                    'is_valid' => $validation['isvalid'] ?? false,
                    'details' => $validation
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to validate address',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function getWalletInfo(Request $request)
    {
        try {
            $walletInfo = $this->bitcoinRpc->call('getwalletinfo');
            
            return response()->json([
                'success' => true,
                'data' => $walletInfo
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to retrieve wallet info',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function getNetworkInfo(Request $request)
    {
        try {
            $networkInfo = $this->bitcoinRpc->call('getnetworkinfo');
            
            return response()->json([
                'success' => true,
                'data' => $networkInfo
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to retrieve network info',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}