@extends('layouts.admin')

@section('page-title', 'Edit Client')
@section('page-subtitle', 'Update client information and whitelist settings')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-lg shadow-md p-8">
        <form action="{{ route('admin.clients.update', $client->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-6">
                <label for="name" class="block text-gray-700 font-bold mb-2">
                    <i class="fas fa-building mr-2 text-blue-500"></i>Client Name *
                </label>
                <input type="text" id="name" name="name" value="{{ old('name', $client->name) }}" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror" 
                       required>
                @error('name')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="mb-6">
                <label for="email" class="block text-gray-700 font-bold mb-2">
                    <i class="fas fa-envelope mr-2 text-blue-500"></i>Email Address *
                </label>
                <input type="email" id="email" name="email" value="{{ old('email', $client->email) }}" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror" 
                       required>
                @error('email')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="mb-6">
                <label for="website" class="block text-gray-700 font-bold mb-2">
                    <i class="fas fa-globe mr-2 text-blue-500"></i>Website URL *
                </label>
                <input type="url" id="website" name="website" value="{{ old('website', $client->website) }}" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('website') border-red-500 @enderror" 
                       required>
                @error('website')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="mb-6">
                <label for="description" class="block text-gray-700 font-bold mb-2">
                    <i class="fas fa-info-circle mr-2 text-blue-500"></i>Description
                </label>
                <textarea id="description" name="description" rows="3" 
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $client->description) }}</textarea>
            </div>
            
            <!-- Current API Keys Display -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                <h4 class="font-bold text-gray-800 mb-3">
                    <i class="fas fa-key mr-2 text-orange-500"></i>Current API Credentials
                </h4>
                <div class="space-y-2">
                    <div>
                        <label class="text-sm text-gray-600">API Key:</label>
                        <code class="block bg-white px-3 py-2 rounded border text-sm font-mono">{{ $client->api_key }}</code>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">API Secret:</label>
                        <code class="block bg-white px-3 py-2 rounded border text-sm font-mono">{{ str_repeat('•', 32) }} (hidden)</code>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mt-3">
                    <i class="fas fa-info-circle mr-1"></i>Use the "Regenerate Keys" button on the clients list to create new credentials.
                </p>
            </div>
            
            <!-- Whitelist Section -->
            <div class="border-t border-gray-200 pt-6 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fas fa-shield-alt mr-2 text-green-500"></i>Security Whitelist
                </h3>
                
                <div class="mb-6">
                    <label for="allowed_domains" class="block text-gray-700 font-bold mb-2">
                        <i class="fas fa-globe mr-2 text-green-500"></i>Allowed Domains
                    </label>
                    <input type="text" id="allowed_domains" name="allowed_domains" value="{{ old('allowed_domains', $client->allowed_domains) }}" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" 
                           placeholder="example.com, shop.example.com (comma-separated)">
                    <p class="text-sm text-gray-600 mt-2">
                        <i class="fas fa-info-circle mr-1"></i>Leave empty to allow all domains. Separate multiple domains with commas.
                    </p>
                </div>
                
                <div class="mb-6">
                    <label for="allowed_ips" class="block text-gray-700 font-bold mb-2">
                        <i class="fas fa-network-wired mr-2 text-green-500"></i>Allowed IP Addresses
                    </label>
                    <input type="text" id="allowed_ips" name="allowed_ips" value="{{ old('allowed_ips', $client->allowed_ips) }}" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" 
                           placeholder="192.168.1.1, 10.0.0.0/24 (comma-separated)">
                    <p class="text-sm text-gray-600 mt-2">
                        <i class="fas fa-info-circle mr-1"></i>Leave empty to allow all IPs. Supports CIDR notation. Separate multiple IPs with commas.
                    </p>
                </div>
            </div>
            
            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="active" value="1" {{ old('active', $client->active) ? 'checked' : '' }} class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <span class="ml-3 text-gray-700 font-semibold">Active (Client can use API)</span>
                </label>
            </div>
            
            <div class="flex justify-end space-x-4 pt-6 border-t">
                <a href="{{ route('admin.clients.index') }}" class="px-6 py-3 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-all font-semibold">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all font-semibold shadow-md hover:shadow-lg transform hover:scale-105">
                    <i class="fas fa-save mr-2"></i>Update Client
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
