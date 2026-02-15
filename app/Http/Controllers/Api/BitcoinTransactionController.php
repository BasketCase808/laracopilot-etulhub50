<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BitcoinRpcService;
use App\Models\Transaction;
use Illuminate\Http\Request;

class BitcoinTransactionController extends Controller
{
    protected $bitcoinRpc;
    
    public function __construct(BitcoinRpcService $bitcoinRpc)
    {
        $this->bitcoinRpc = $bitcoinRpc;
    }
    
    public function sendBitcoin(Request $request)
    {
        $validated = $request->validate([
            'address' => 'required|string',
            'amount' => 'required|numeric|min:0.00000001',
            'comment' => 'nullable|string',
            'subtract_fee' => 'nullable|boolean'
        ]);
        
        try {
            // Validate address first
            $addressValidation = $this->bitcoinRpc->call('validateaddress', [$validated['address']]);
            
            if (!$addressValidation['isvalid']) {
                return response()->json([
                    'success' => false,
                    'error' => 'Invalid Bitcoin address'
                ], 422);
            }
            
            // Send transaction
            $txid = $this->bitcoinRpc->call('sendtoaddress', [
                $validated['address'],
                $validated['amount'],
                $validated['comment'] ?? '',
                '',
                $validated['subtract_fee'] ?? false
            ]);
            
            // Log transaction
            $transaction = Transaction::create([
                'api_client_id' => $request->input('api_client')->id,
                'txid' => $txid,
                'address' => $validated['address'],
                'amount' => $validated['amount'],
                'type' => 'send',
                'status' => 'pending',
                'comment' => $validated['comment'] ?? null
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Bitcoin sent successfully',
                'data' => [
                    'txid' => $txid,
                    'address' => $validated['address'],
                    'amount' => $validated['amount'],
                    'amount_btc' => number_format($validated['amount'], 8),
                    'status' => 'pending',
                    'transaction_id' => $transaction->id
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to send Bitcoin',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function getTransaction(Request $request, $txid)
    {
        try {
            $transaction = $this->bitcoinRpc->call('gettransaction', [$txid]);
            
            return response()->json([
                'success' => true,
                'data' => $transaction
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Transaction not found',
                'message' => $e->getMessage()
            ], 404);
        }
    }
    
    public function listTransactions(Request $request)
    {
        $validated = $request->validate([
            'count' => 'nullable|integer|min:1|max:100',
            'skip' => 'nullable|integer|min:0'
        ]);
        
        try {
            $transactions = $this->bitcoinRpc->call('listtransactions', [
                '*',
                $validated['count'] ?? 10,
                $validated['skip'] ?? 0
            ]);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'transactions' => $transactions,
                    'count' => count($transactions)
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to list transactions',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function getConfirmations(Request $request, $txid)
    {
        try {
            $transaction = $this->bitcoinRpc->call('gettransaction', [$txid]);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'txid' => $txid,
                    'confirmations' => $transaction['confirmations'] ?? 0,
                    'confirmed' => ($transaction['confirmations'] ?? 0) >= 6,
                    'status' => ($transaction['confirmations'] ?? 0) >= 6 ? 'confirmed' : 'pending'
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to get confirmations',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}