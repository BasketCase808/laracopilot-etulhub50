<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BitcoinRpcService
{
    protected $rpcUrl;
    protected $rpcUser;
    protected $rpcPassword;
    protected $rpcPort;
    
    public function __construct()
    {
        $this->rpcUrl = env('BITCOIN_RPC_HOST', '127.0.0.1');
        $this->rpcPort = env('BITCOIN_RPC_PORT', '8332');
        $this->rpcUser = env('BITCOIN_RPC_USER', 'bitcoinrpc');
        $this->rpcPassword = env('BITCOIN_RPC_PASSWORD', '');
    }
    
    public function call($method, $params = [])
    {
        $url = "http://{$this->rpcUrl}:{$this->rpcPort}";
        
        $response = Http::withBasicAuth($this->rpcUser, $this->rpcPassword)
            ->timeout(30)
            ->post($url, [
                'jsonrpc' => '1.0',
                'id' => 'laracopilot',
                'method' => $method,
                'params' => $params
            ]);
        
        if (!$response->successful()) {
            throw new \Exception('Bitcoin RPC connection failed: ' . $response->body());
        }
        
        $data = $response->json();
        
        if (isset($data['error']) && $data['error'] !== null) {
            throw new \Exception('Bitcoin RPC error: ' . json_encode($data['error']));
        }
        
        return $data['result'];
    }
    
    public function testConnection()
    {
        try {
            $info = $this->call('getblockchaininfo');
            return [
                'connected' => true,
                'chain' => $info['chain'] ?? 'unknown',
                'blocks' => $info['blocks'] ?? 0
            ];
        } catch (\Exception $e) {
            return [
                'connected' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}