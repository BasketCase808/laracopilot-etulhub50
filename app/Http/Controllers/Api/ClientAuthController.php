<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApiClient;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ClientAuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:api_clients,email',
            'website' => 'required|url',
            'description' => 'nullable|string'
        ]);
        
        $apiKey = 'btc_' . Str::random(32);
        $apiSecret = Str::random(64);
        
        $client = ApiClient::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'website' => $validated['website'],
            'description' => $validated['description'] ?? null,
            'api_key' => $apiKey,
            'api_secret' => $apiSecret,
            'active' => true
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'API client registered successfully',
            'data' => [
                'client_id' => $client->id,
                'api_key' => $apiKey,
                'api_secret' => $apiSecret,
                'name' => $client->name,
                'created_at' => $client->created_at
            ],
            'warning' => 'Store your API credentials securely. The API secret will not be shown again.'
        ], 201);
    }
    
    public function login(Request $request)
    {
        $validated = $request->validate([
            'api_key' => 'required|string',
            'api_secret' => 'required|string'
        ]);
        
        $client = ApiClient::where('api_key', $validated['api_key'])
            ->where('api_secret', $validated['api_secret'])
            ->where('active', true)
            ->first();
        
        if (!$client) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid credentials'
            ], 401);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Authentication successful',
            'data' => [
                'client_id' => $client->id,
                'name' => $client->name,
                'email' => $client->email,
                'active' => $client->active
            ]
        ]);
    }
}