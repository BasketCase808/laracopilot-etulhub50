<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ApiClient;

class ApiClientAuthentication
{
    public function handle(Request $request, Closure $next)
    {
        $apiKey = $request->header('X-API-Key');
        $apiSecret = $request->header('X-API-Secret');
        
        if (!$apiKey || !$apiSecret) {
            return response()->json([
                'success' => false,
                'error' => 'Missing API credentials',
                'message' => 'X-API-Key and X-API-Secret headers are required'
            ], 401);
        }
        
        $client = ApiClient::where('api_key', $apiKey)
            ->where('api_secret', $apiSecret)
            ->where('active', true)
            ->first();
        
        if (!$client) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid API credentials'
            ], 401);
        }
        
        // Domain whitelist check
        if ($client->allowed_domains) {
            $origin = $request->header('Origin') ?? $request->header('Referer');
            
            if ($origin) {
                $domain = parse_url($origin, PHP_URL_HOST);
                
                if (!$client->isDomainAllowed($domain)) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Domain not whitelisted',
                        'message' => "Requests from '{$domain}' are not allowed for this API client"
                    ], 403);
                }
            }
        }
        
        // IP whitelist check
        if ($client->allowed_ips) {
            $clientIp = $request->ip();
            
            if (!$client->isIpAllowed($clientIp)) {
                return response()->json([
                    'success' => false,
                    'error' => 'IP address not whitelisted',
                    'message' => "Requests from '{$clientIp}' are not allowed for this API client"
                ], 403);
            }
        }
        
        // Attach client to request for use in controllers
        $request->merge(['api_client' => $client]);
        
        return $next($request);
    }
}