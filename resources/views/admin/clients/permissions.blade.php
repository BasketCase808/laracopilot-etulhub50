@extends('layouts.admin')

@section('page-title', 'Client Permissions')
@section('page-subtitle', 'Configure API access restrictions and limits')

@section('content')
<div class="max-w-4xl">
    <div class="bg-white rounded-lg shadow-md p-8">
        <div class="mb-6 pb-6 border-b">
            <div class="flex items-center">
                <div class="w-16 h-16 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold text-2xl mr-4">
                    {{ substr($client->name, 0, 1) }}
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $client->name }}</h3>
                    <p class="text-gray-600">{{ $client->email }}</p>
                    <p class="text-sm text-gray-500 mt-1">API Key: <code class="bg-gray-100 px-2 py-1 rounded text-xs">{{ substr($client->api_key, 0, 20) }}...</code></p>
                </div>
            </div>
        </div>
        
        <form action="{{ route('admin.clients.permissions.update', $client->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Transaction Limits -->
            <div class="mb-8">
                <h4 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fas fa-coins mr-2 text-orange-500"></i>Transaction Limits
                </h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="max_transaction_amount" class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-exclamation-triangle mr-2 text-red-500"></i>Max Transaction Amount (BTC) *
                        </label>
                        <input type="number" step="0.00000001" id="max_transaction_amount" name="max_transaction_amount" 
                               value="{{ old('max_transaction_amount', $client->max_transaction_amount) }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500" 
                               required>
                        <p class="text-sm text-gray-600 mt-2">
                            <i class="fas fa-info-circle mr-1"></i>Maximum BTC per transaction (e.g., 0.1 BTC = ~$4,000)
                        </p>
                    </div>
                    
                    <div>
                        <label for="daily_transaction_limit" class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-calendar-day mr-2 text-purple-500"></i>Daily Transaction Limit (BTC)
                        </label>
                        <input type="number" step="0.00000001" id="daily_transaction_limit" name="daily_transaction_limit" 
                               value="{{ old('daily_transaction_limit', $client->daily_transaction_limit) }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <p class="text-sm text-gray-600 mt-2">
                            <i class="fas fa-info-circle mr-1"></i>Total BTC limit per day (leave empty for no limit)
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- API Permissions -->
            <div class="mb-8">
                <h4 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fas fa-shield-alt mr-2 text-green-500"></i>API Permissions
                </h4>
                
                <div class="space-y-3">
                    <label class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer">
                        <input type="checkbox" name="can_send_transactions" value="1" 
                               {{ old('can_send_transactions', $client->can_send_transactions) ? 'checked' : '' }} 
                               class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500">
                        <div class="ml-3">
                            <span class="font-semibold text-gray-800">Can Send Transactions</span>
                            <p class="text-sm text-gray-600">Allow client to send Bitcoin via API</p>
                        </div>
                    </label>
                    
                    <label class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer">
                        <input type="checkbox" name="can_generate_addresses" value="1" 
                               {{ old('can_generate_addresses', $client->can_generate_addresses) ? 'checked' : '' }} 
                               class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <div class="ml-3">
                            <span class="font-semibold text-gray-800">Can Generate Addresses</span>
                            <p class="text-sm text-gray-600">Allow client to create new Bitcoin addresses</p>
                        </div>
                    </label>
                    
                    <label class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer">
                        <input type="checkbox" name="can_view_balance" value="1" 
                               {{ old('can_view_balance', $client->can_view_balance) ? 'checked' : '' }} 
                               class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                        <div class="ml-3">
                            <span class="font-semibold text-gray-800">Can View Balance</span>
                            <p class="text-sm text-gray-600">Allow client to check wallet balance</p>
                        </div>
                    </label>
                    
                    <label class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer">
                        <input type="checkbox" name="can_list_transactions" value="1" 
                               {{ old('can_list_transactions', $client->can_list_transactions) ? 'checked' : '' }} 
                               class="w-5 h-5 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
                        <div class="ml-3">
                            <span class="font-semibold text-gray-800">Can List Transactions</span>
                            <p class="text-sm text-gray-600">Allow client to view transaction history</p>
                        </div>
                    </label>
                    
                    <label class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer">
                        <input type="checkbox" name="can_validate_addresses" value="1" 
                               {{ old('can_validate_addresses', $client->can_validate_addresses) ? 'checked' : '' }} 
                               class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <div class="ml-3">
                            <span class="font-semibold text-gray-800">Can Validate Addresses</span>
                            <p class="text-sm text-gray-600">Allow client to validate Bitcoin addresses</p>
                        </div>
                    </label>
                </div>
            </div>
            
            <!-- Blocked Commands -->
            <div class="mb-8">
                <h4 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fas fa-ban mr-2 text-red-500"></i>Blocked Commands (Advanced)
                </h4>
                
                <div>
                    <label for="blocked_commands" class="block text-gray-700 font-bold mb-2">
                        Additional Blocked Commands
                    </label>
                    <input type="text" id="blocked_commands" name="blocked_commands" 
                           value="{{ old('blocked_commands', $client->blocked_commands) }}" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" 
                           placeholder="sendall, dumpprivkey (comma-separated)">
                    <p class="text-sm text-gray-600 mt-2">
                        <i class="fas fa-info-circle mr-1"></i>Comma-separated list of additional RPC commands to block for this client
                    </p>
                </div>
            </div>
            
            <div class="flex justify-end space-x-4 pt-6 border-t">
                <a href="{{ route('admin.clients.index') }}" class="px-6 py-3 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-all font-semibold">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition-all font-semibold shadow-md hover:shadow-lg">
                    <i class="fas fa-save mr-2"></i>Save Permissions
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
