@extends('layouts.admin')

@section('page-title', 'Dashboard')
@section('page-subtitle', 'Bitcoin RPC Gateway Overview')

@section('content')
<!-- KPI Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Clients -->
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500 hover:shadow-lg transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-semibold uppercase">Total Clients</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalClients }}</p>
            </div>
            <div class="bg-blue-100 rounded-full p-4">
                <i class="fas fa-users text-blue-500 text-2xl"></i>
            </div>
        </div>
        <div class="mt-4 text-sm text-gray-600">
            <span class="text-green-600 font-semibold">{{ $activeClients }} active</span> / 
            <span class="text-red-600">{{ $inactiveClients }} inactive</span>
        </div>
    </div>
    
    <!-- Total Transactions -->
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500 hover:shadow-lg transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-semibold uppercase">Total Transactions</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalTransactions }}</p>
            </div>
            <div class="bg-purple-100 rounded-full p-4">
                <i class="fas fa-exchange-alt text-purple-500 text-2xl"></i>
            </div>
        </div>
        <div class="mt-4 text-sm text-gray-600">
            <span class="text-green-600 font-semibold">{{ $confirmedTransactions }} confirmed</span>
        </div>
    </div>
    
    <!-- Pending Transactions -->
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-orange-500 hover:shadow-lg transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-semibold uppercase">Pending</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $pendingTransactions }}</p>
            </div>
            <div class="bg-orange-100 rounded-full p-4">
                <i class="fas fa-clock text-orange-500 text-2xl"></i>
            </div>
        </div>
        <div class="mt-4 text-sm text-gray-600">
            Awaiting confirmation
        </div>
    </div>
    
    <!-- Total Volume -->
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500 hover:shadow-lg transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-semibold uppercase">Total Volume</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($totalVolume, 4) }} BTC</p>
            </div>
            <div class="bg-green-100 rounded-full p-4">
                <i class="fab fa-bitcoin text-green-500 text-2xl"></i>
            </div>
        </div>
        <div class="mt-4 text-sm text-gray-600">
            Confirmed transactions only
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Recent Clients -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800"><i class="fas fa-users mr-2 text-blue-500"></i>Recent Clients</h3>
            <a href="{{ route('admin.clients.index') }}" class="text-sm text-blue-600 hover:text-blue-700">View All →</a>
        </div>
        <div class="space-y-3">
            @forelse($recentClients as $client)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded hover:bg-gray-100 transition-colors">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold">
                        {{ substr($client->name, 0, 1) }}
                    </div>
                    <div class="ml-3">
                        <p class="font-semibold text-gray-800">{{ $client->name }}</p>
                        <p class="text-xs text-gray-500">{{ $client->email }}</p>
                    </div>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $client->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $client->active ? 'Active' : 'Inactive' }}
                </span>
            </div>
            @empty
            <p class="text-gray-500 text-center py-4">No clients yet</p>
            @endforelse
        </div>
    </div>
    
    <!-- Top Clients by Activity -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800"><i class="fas fa-chart-bar mr-2 text-purple-500"></i>Top Clients by Activity</h3>
        </div>
        <div class="space-y-3">
            @forelse($topClients as $client)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded hover:bg-gray-100 transition-colors">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-purple-500 to-purple-600 flex items-center justify-center text-white font-bold">
                        {{ substr($client->name, 0, 1) }}
                    </div>
                    <div class="ml-3">
                        <p class="font-semibold text-gray-800">{{ $client->name }}</p>
                        <p class="text-xs text-gray-500">{{ $client->transactions_count }} transactions</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-sm font-bold text-purple-600">{{ $client->transactions_count }}</div>
                </div>
            </div>
            @empty
            <p class="text-gray-500 text-center py-4">No activity yet</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Recent Transactions -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="p-6 border-b">
        <h3 class="text-lg font-bold text-gray-800"><i class="fas fa-history mr-2 text-orange-500"></i>Recent Transactions</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Transaction ID</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Client</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($recentTransactions as $tx)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ substr($tx->txid, 0, 16) }}...</code>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $tx->client->name ?? 'Unknown' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-semibold text-gray-900">{{ number_format($tx->amount, 8) }} BTC</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 rounded text-xs font-semibold {{ $tx->type === 'send' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                            <i class="fas fa-{{ $tx->type === 'send' ? 'arrow-up' : 'arrow-down' }} mr-1"></i>
                            {{ ucfirst($tx->type) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 rounded text-xs font-semibold 
                            @if($tx->status === 'confirmed') bg-green-100 text-green-800
                            @elseif($tx->status === 'pending') bg-orange-100 text-orange-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst($tx->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $tx->created_at->format('M d, Y H:i') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        <i class="fas fa-inbox text-4xl text-gray-300 mb-2"></i>
                        <p>No transactions yet</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
