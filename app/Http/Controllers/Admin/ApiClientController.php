<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiClient;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiClientController extends Controller
{
    public function index(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        
        $clients = ApiClient::withCount('transactions')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('admin.clients.index', compact('clients'));
    }
    
    public function create(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        
        return view('admin.clients.create');
    }
    
    public function store(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:api_clients,email',
            'website' => 'required|url',
            'description' => 'nullable|string',
            'allowed_domains' => 'nullable|string',
            'allowed_ips' => 'nullable|string',
            'active' => 'nullable|boolean'
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
            'allowed_domains' => $validated['allowed_domains'] ?? null,
            'allowed_ips' => $validated['allowed_ips'] ?? null,
            'active' => $validated['active'] ?? true
        ]);
        
        return redirect()->route('admin.clients.index')
            ->with('success', 'API client created successfully')
            ->with('api_credentials', [
                'api_key' => $apiKey,
                'api_secret' => $apiSecret,
                'client_name' => $client->name
            ]);
    }
    
    public function edit(Request $request, $id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        
        $client = ApiClient::findOrFail($id);
        
        return view('admin.clients.edit', compact('client'));
    }
    
    public function update(Request $request, $id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        
        $client = ApiClient::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:api_clients,email,' . $id,
            'website' => 'required|url',
            'description' => 'nullable|string',
            'allowed_domains' => 'nullable|string',
            'allowed_ips' => 'nullable|string',
            'active' => 'nullable|boolean'
        ]);
        
        $client->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'website' => $validated['website'],
            'description' => $validated['description'] ?? null,
            'allowed_domains' => $validated['allowed_domains'] ?? null,
            'allowed_ips' => $validated['allowed_ips'] ?? null,
            'active' => $validated['active'] ?? true
        ]);
        
        return redirect()->route('admin.clients.index')
            ->with('success', 'API client updated successfully');
    }
    
    public function destroy(Request $request, $id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        
        $client = ApiClient::findOrFail($id);
        $client->delete();
        
        return redirect()->route('admin.clients.index')
            ->with('success', 'API client deleted successfully');
    }
    
    public function regenerateKeys(Request $request, $id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        
        $client = ApiClient::findOrFail($id);
        
        $apiKey = 'btc_' . Str::random(32);
        $apiSecret = Str::random(64);
        
        $client->update([
            'api_key' => $apiKey,
            'api_secret' => $apiSecret
        ]);
        
        return redirect()->route('admin.clients.index')
            ->with('success', 'API keys regenerated successfully')
            ->with('api_credentials', [
                'api_key' => $apiKey,
                'api_secret' => $apiSecret,
                'client_name' => $client->name
            ]);
    }
    
    public function toggleStatus(Request $request, $id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        
        $client = ApiClient::findOrFail($id);
        $client->update([
            'active' => !$client->active
        ]);
        
        $status = $client->active ? 'activated' : 'deactivated';
        
        return redirect()->route('admin.clients.index')
            ->with('success', "API client {$status} successfully");
    }
}