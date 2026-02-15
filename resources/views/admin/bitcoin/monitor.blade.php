@extends('layouts.admin')

@section('page-title', 'Bitcoin Daemon Monitor')
@section('page-subtitle', 'Real-time Bitcoin Core node monitoring')

@section('content')
<!-- Connection Status -->
<div class="mb-6">
    @if($connectionStatus['connected'])
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded flex items-center justify-between">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-2xl mr-3"></i>
            <div>
                <strong class="text-lg">Connected to Bitcoin Core</strong>
                <p class="text-sm mt-1">RPC daemon is responding normally</p>
            </div>
        </div>
        <form action="{{ route('admin.bitcoin.refresh') }}" method="POST">
            @csrf
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition-all">
                <i class="fas fa-sync-alt mr-2"></i>Refresh
            </button>
        </form>
    </div>
    @else
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
        <div class="flex items-center mb-2">
            <i class="fas fa-exclamation-triangle text-2xl mr-3"></i>
            <strong class="text-lg">Connection Failed</strong>
        </div>
        <p class="text-sm mt-2">{{ $connectionStatus['error'] }}</p>
    </div>
    @endif
</div>

@if($connectionStatus['connected'])
<!-- Quick Stats -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-semibold uppercase">Block Height</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($blockchainInfo['blocks'] ?? 0) }}</p>
            </div>
            <div class="bg-blue-100 rounded-full p-4">
                <i class="fas fa-cubes text-blue-500 text-2xl"></i>
            </div>
        </div>
        <div class="mt-4 text-sm text-gray-600">
            Chain: <strong>{{ $blockchainInfo['chain'] ?? 'unknown' }}</strong>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-semibold uppercase">Connected Peers</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ count($peerInfo) }}</p>
            </div>
            <div class="bg-purple-100 rounded-full p-4">
                <i class="fas fa-network-wired text-purple-500 text-2xl"></i>
            </div>
        </div>
        <div class="mt-4 text-sm text-gray-600">
            Version: <strong>{{ $networkInfo['version'] ?? 'unknown' }}</strong>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-orange-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-semibold uppercase">Mempool</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($mempoolInfo['size'] ?? 0) }}</p>
            </div>
            <div class="bg-orange-100 rounded-full p-4">
                <i class="fas fa-layer-group text-orange-500 text-2xl"></i>
            </div>
        </div>
        <div class="mt-4 text-sm text-gray-600">
            Size: <strong>{{ number_format(($mempoolInfo['bytes'] ?? 0) / 1024 / 1024, 2) }} MB</strong>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-semibold uppercase">Wallet Balance</p>
                <p class="text-2xl font-bold text-gray-800 mt-2">{{ number_format($walletInfo['balance'] ?? 0, 8) }} BTC</p>
            </div>
            <div class="bg-green-100 rounded-full p-4">
                <i class="fab fa-bitcoin text-green-500 text-2xl"></i>
            </div>
        </div>
        <div class="mt-4 text-sm text-gray-600">
            Unconfirmed: <strong>{{ number_format($walletInfo['unconfirmed_balance'] ?? 0, 8) }} BTC</strong>
        </div>
    </div>
</div>

<!-- Detailed Information Tabs -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Blockchain Info -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-link mr-2 text-blue-500"></i>Blockchain Information
        </h3>
        <div class="space-y-3">
            <div class="flex justify-between py-2 border-b">
                <span class="text-gray-600">Chain:</span>
                <span class="font-semibold">{{ $blockchainInfo['chain'] ?? 'N/A' }}</span>
            </div>
            <div class="flex justify-between py-2 border-b">
                <span class="text-gray-600">Blocks:</span>
                <span class="font-semibold">{{ number_format($blockchainInfo['blocks'] ?? 0) }}</span>
            </div>
            <div class="flex justify-between py-2 border-b">
                <span class="text-gray-600">Headers:</span>
                <span class="font-semibold">{{ number_format($blockchainInfo['headers'] ?? 0) }}</span>
            </div>
            <div class="flex justify-between py-2 border-b">
                <span class="text-gray-600">Best Block Hash:</span>
                <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ substr($blockchainInfo['bestblockhash'] ?? '', 0, 16) }}...</code>
            </div>
            <div class="flex justify-between py-2 border-b">
                <span class="text-gray-600">Difficulty:</span>
                <span class="font-semibold">{{ number_format($blockchainInfo['difficulty'] ?? 0, 2) }}</span>
            </div>
            <div class="flex justify-between py-2 border-b">
                <span class="text-gray-600">Verification Progress:</span>
                <span class="font-semibold">{{ number_format(($blockchainInfo['verificationprogress'] ?? 0) * 100, 2) }}%</span>
            </div>
            <div class="flex justify-between py-2">
                <span class="text-gray-600">Size on Disk:</span>
                <span class="font-semibold">{{ number_format(($blockchainInfo['size_on_disk'] ?? 0) / 1024 / 1024 / 1024, 2) }} GB</span>
            </div>
        </div>
    </div>
    
    <!-- Network Info -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-globe mr-2 text-purple-500"></i>Network Information
        </h3>
        <div class="space-y-3">
            <div class="flex justify-between py-2 border-b">
                <span class="text-gray-600">Version:</span>
                <span class="font-semibold">{{ $networkInfo['version'] ?? 'N/A' }}</span>
            </div>
            <div class="flex justify-between py-2 border-b">
                <span class="text-gray-600">Subversion:</span>
                <span class="font-semibold text-sm">{{ $networkInfo['subversion'] ?? 'N/A' }}</span>
            </div>
            <div class="flex justify-between py-2 border-b">
                <span class="text-gray-600">Protocol Version:</span>
                <span class="font-semibold">{{ $networkInfo['protocolversion'] ?? 'N/A' }}</span>
            </div>
            <div class="flex justify-between py-2 border-b">
                <span class="text-gray-600">Connections:</span>
                <span class="font-semibold">{{ $networkInfo['connections'] ?? 0 }}</span>
            </div>
            <div class="flex justify-between py-2 border-b">
                <span class="text-gray-600">Upload:</span>
                <span class="font-semibold">{{ number_format(($networkTotals['totalbytessent'] ?? 0) / 1024 / 1024, 2) }} MB</span>
            </div>
            <div class="flex justify-between py-2 border-b">
                <span class="text-gray-600">Download:</span>
                <span class="font-semibold">{{ number_format(($networkTotals['totalbytesrecv'] ?? 0) / 1024 / 1024, 2) }} MB</span>
            </div>
            <div class="flex justify-between py-2">
                <span class="text-gray-600">Uptime:</span>
                <span class="font-semibold">{{ gmdate('H:i:s', $uptime ?? 0) }}</span>
            </div>
        </div>
    </div>
    
    <!-- Wallet Info -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-wallet mr-2 text-green-500"></i>Wallet Information
        </h3>
        <div class="space-y-3">
            <div class="flex justify-between py-2 border-b">
                <span class="text-gray-600">Wallet Name:</span>
                <span class="font-semibold">{{ $walletInfo['walletname'] ?? 'N/A' }}</span>
            </div>
            <div class="flex justify-between py-2 border-b">
                <span class="text-gray-600">Balance:</span>
                <span class="font-semibold text-green-600">{{ number_format($walletInfo['balance'] ?? 0, 8) }} BTC</span>
            </div>
            <div class="flex justify-between py-2 border-b">
                <span class="text-gray-600">Unconfirmed:</span>
                <span class="font-semibold text-orange-600">{{ number_format($walletInfo['unconfirmed_balance'] ?? 0, 8) }} BTC</span>
            </div>
            <div class="flex justify-between py-2 border-b">
                <span class="text-gray-600">Immature:</span>
                <span class="font-semibold">{{ number_format($walletInfo['immature_balance'] ?? 0, 8) }} BTC</span>
            </div>
            <div class="flex justify-between py-2 border-b">
                <span class="text-gray-600">Transaction Count:</span>
                <span class="font-semibold">{{ number_format($walletInfo['txcount'] ?? 0) }}</span>
            </div>
            <div class="flex justify-between py-2">
                <span class="text-gray-600">Keypools:</span>
                <span class="font-semibold">{{ number_format($walletInfo['keypoolsize'] ?? 0) }}</span>
            </div>
        </div>
    </div>
    
    <!-- Peer Connections -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-users mr-2 text-blue-500"></i>Connected Peers ({{ count($peerInfo) }})
        </h3>
        <div class="max-h-80 overflow-y-auto">
            @forelse($peerInfo as $peer)
            <div class="p-3 bg-gray-50 rounded mb-2 hover:bg-gray-100 transition-colors">
                <div class="flex items-center justify-between mb-1">
                    <span class="font-semibold text-sm">{{ $peer['addr'] ?? 'Unknown' }}</span>
                    <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded">{{ $peer['version'] ?? 'N/A' }}</span>
                </div>
                <div class="text-xs text-gray-600">
                    <span>Ping: {{ isset($peer['pingtime']) ? round($peer['pingtime'] * 1000) . 'ms' : 'N/A' }}</span>
                    <span class="mx-2">•</span>
                    <span>{{ $peer['inbound'] ? 'Inbound' : 'Outbound' }}</span>
                </div>
            </div>
            @empty
            <p class="text-gray-500 text-center py-4">No peers connected</p>
            @endforelse
        </div>
    </div>
</div>
@endif
@endsection
