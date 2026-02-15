<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\BitcoinRpcService;
use Illuminate\Http\Request;

class BitcoinMonitorController extends Controller
{
    protected $bitcoinRpc;
    
    public function __construct(BitcoinRpcService $bitcoinRpc)
    {
        $this->bitcoinRpc = $bitcoinRpc;
    }
    
    public function index(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        
        try {
            // Blockchain Info
            $blockchainInfo = $this->bitcoinRpc->call('getblockchaininfo');
            
            // Network Info
            $networkInfo = $this->bitcoinRpc->call('getnetworkinfo');
            
            // Wallet Info
            $walletInfo = $this->bitcoinRpc->call('getwalletinfo');
            
            // Mining Info
            $miningInfo = $this->bitcoinRpc->call('getmininginfo');
            
            // Peer Info
            $peerInfo = $this->bitcoinRpc->call('getpeerinfo');
            
            // Memory Pool Info
            $mempoolInfo = $this->bitcoinRpc->call('getmempoolinfo');
            
            // Network Totals
            $networkTotals = $this->bitcoinRpc->call('getnettotals');
            
            // Uptime
            $uptime = $this->bitcoinRpc->call('uptime');
            
            $connectionStatus = [
                'connected' => true,
                'error' => null
            ];
            
        } catch (\Exception $e) {
            $blockchainInfo = null;
            $networkInfo = null;
            $walletInfo = null;
            $miningInfo = null;
            $peerInfo = [];
            $mempoolInfo = null;
            $networkTotals = null;
            $uptime = null;
            
            $connectionStatus = [
                'connected' => false,
                'error' => $e->getMessage()
            ];
        }
        
        return view('admin.bitcoin.monitor', compact(
            'blockchainInfo',
            'networkInfo',
            'walletInfo',
            'miningInfo',
            'peerInfo',
            'mempoolInfo',
            'networkTotals',
            'uptime',
            'connectionStatus'
        ));
    }
    
    public function refresh(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        
        return redirect()->route('admin.bitcoin.monitor')
            ->with('success', 'Bitcoin daemon data refreshed');
    }
}