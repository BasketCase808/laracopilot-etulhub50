@extends('layouts.admin')

@section('page-title', 'API Clients')
@section('page-subtitle', 'Manage API client applications and credentials')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <p class="text-gray-600">Total: <strong>{{ $clients->total() }}</strong> clients</p>
    </div>
    <a href="{{ route('admin.clients.create') }}" class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-300 font-semibold shadow-md hover:shadow-lg transform hover:scale-105">
        <i class="fas fa-plus mr-2"></i>Add New Client
    </a>
</div>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <table class="min-w-full">
        <thead class="bg-gradient-to-r from-slate-700 to-slate-800 text-white">
            <tr>
                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Client Details</th>
                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">API Key</th>
                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Limits</th>
                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Transactions</th>
                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Status</th>
                <th class="px-6 py-4 text-right text-sm font-semibold uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($clients as $client)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold text-lg">
                            {{ substr($client->name, 0, 1) }}
                        </div>
                        <div class="ml-4">
                            <div class="font-semibold text-gray-900">{{ $client->name }}</div>
                            <div class="text-sm text-gray-500">{{ $client->email }}</div>
                            <div class="text-xs text-blue-600 hover:underline">
                                <a href="{{ $client->website }}" target="_blank">
                                    <i class="fas fa-external-link-alt mr-1"></i>{{ parse_url($client->website, PHP_URL_HOST) }}
                                </a>
                            </div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <code class="text-xs bg-gray-100 px-2 py-1 rounded font-mono">{{ substr($client->api_key, 0, 20) }}...</code>
                </td>
                <td class="px-6 py-4">
                    <div class="text-sm">
                        <div class="mb-1">
                            <i class="fas fa-coins text-orange-500 mr-1"></i>
                            <span class="font-semibold">Max:</span> {{ $client->max_transaction_amount }} BTC
                        </div>
                        @if($client->daily_transaction_limit)
                            <div>
                                <i class="fas fa-calendar-day text-purple-500 mr-1"></i>
                                <span class="font-semibold">Daily:</span> {{ $client->daily_transaction_limit }} BTC
                            </div>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4">
                    <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm font-semibold">
                        {{ $client->transactions_count }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <form action="{{ route('admin.clients.toggle', $client->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-3 py-1 rounded-full text-sm font-semibold transition-all duration-300 {{ $client->active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                            {{ $client->active ? 'Active' : 'Inactive' }}
                        </button>
                    </form>
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex justify-end space-x-2">
                        <a href="{{ route('admin.clients.permissions', $client->id) }}" class="text-green-600 hover:text-green-700 px-3 py-1 rounded hover:bg-green-50 transition-all" title="Permissions">
                            <i class="fas fa-shield-alt"></i>
                        </a>
                        
                        <a href="{{ route('admin.clients.edit', $client->id) }}" class="text-blue-600 hover:text-blue-700 px-3 py-1 rounded hover:bg-blue-50 transition-all" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        
                        <form action="{{ route('admin.clients.regenerate', $client->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-orange-600 hover:text-orange-700 px-3 py-1 rounded hover:bg-orange-50 transition-all" title="Regenerate Keys" onclick="return confirm('Regenerate API keys? This will invalidate the current keys.')">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </form>
                        
                        <form action="{{ route('admin.clients.destroy', $client->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-700 px-3 py-1 rounded hover:bg-red-50 transition-all" title="Delete" onclick="return confirm('Delete this client? All associated data will be removed.')">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                    <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
                    <p class="text-lg font-semibold">No API clients yet</p>
                    <p class="text-sm mt-2">Create your first client to get started</p>
                    <a href="{{ route('admin.clients.create') }}" class="inline-block mt-4 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-all">
                        <i class="fas fa-plus mr-2"></i>Add First Client
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($clients->hasPages())
<div class="mt-6">
    {{ $clients->links() }}
</div>
@endif
@endsection
