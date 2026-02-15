<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\BitcoinRpcService;
use App\Models\CommandLog;
use Illuminate\Http\Request;

class CommandExecutionController extends Controller
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
        
        $recentCommands = CommandLog::with('user')
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();
        
        $availableCommands = [
            'info' => [
                'getblockchaininfo' => 'Get blockchain information',
                'getnetworkinfo' => 'Get network information',
                'getwalletinfo' => 'Get wallet information',
                'getmininginfo' => 'Get mining information',
                'getpeerinfo' => 'Get peer information',
                'getmempoolinfo' => 'Get mempool information',
                'getnettotals' => 'Get network totals',
                'uptime' => 'Get daemon uptime'
            ],
            'wallet' => [
                'getbalance' => 'Get wallet balance',
                'getnewaddress' => 'Generate new address',
                'listunspent' => 'List unspent outputs',
                'listtransactions' => 'List recent transactions',
                'getaddressinfo' => 'Get address information'
            ],
            'blockchain' => [
                'getblockcount' => 'Get current block height',
                'getbestblockhash' => 'Get best block hash',
                'getdifficulty' => 'Get mining difficulty',
                'getblock' => 'Get block information (requires hash)'
            ]
        ];
        
        return view('admin.bitcoin.commands', compact('recentCommands', 'availableCommands'));
    }
    
    public function execute(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        
        $validated = $request->validate([
            'command' => 'required|string',
            'parameters' => 'nullable|string'
        ]);
        
        $command = $validated['command'];
        $parametersJson = $validated['parameters'] ?? '';
        
        // Parse parameters
        $parameters = [];
        if (!empty($parametersJson)) {
            $parameters = json_decode($parametersJson, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                // Try parsing as simple comma-separated values
                $parameters = array_map('trim', explode(',', $parametersJson));
            }
        }
        
        // Check for dangerous commands
        $dangerousCommands = [
            'stop',
            'sendtoaddress',
            'sendmany',
            'sendall',
            'walletpassphrase',
            'walletpassphrasechange',
            'encryptwallet',
            'backupwallet',
            'importprivkey',
            'dumpprivkey',
            'dumpwallet'
        ];
        
        if (in_array(strtolower($command), $dangerousCommands)) {
            CommandLog::create([
                'user_id' => session('admin_user_id'),
                'command' => $command,
                'parameters' => $parametersJson,
                'status' => 'blocked',
                'error_message' => 'Dangerous command blocked by system',
                'executed_at' => now()
            ]);
            
            return back()->withErrors([
                'command' => "Command '{$command}' is restricted for security reasons. Use API endpoints for financial operations."
            ]);
        }
        
        try {
            $result = $this->bitcoinRpc->call($command, $parameters);
            
            CommandLog::create([
                'user_id' => session('admin_user_id'),
                'command' => $command,
                'parameters' => $parametersJson,
                'result' => json_encode($result, JSON_PRETTY_PRINT),
                'status' => 'success',
                'executed_at' => now()
            ]);
            
            return back()->with('command_result', [
                'command' => $command,
                'parameters' => $parameters,
                'result' => $result,
                'status' => 'success'
            ]);
            
        } catch (\Exception $e) {
            CommandLog::create([
                'user_id' => session('admin_user_id'),
                'command' => $command,
                'parameters' => $parametersJson,
                'status' => 'error',
                'error_message' => $e->getMessage(),
                'executed_at' => now()
            ]);
            
            return back()->withErrors([
                'command' => 'Command execution failed: ' . $e->getMessage()
            ])->with('command_result', [
                'command' => $command,
                'parameters' => $parameters,
                'error' => $e->getMessage(),
                'status' => 'error'
            ]);
        }
    }
}