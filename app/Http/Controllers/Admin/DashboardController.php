<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiClient;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        
        // KPI Calculations
        $totalClients = ApiClient::count();
        $activeClients = ApiClient::where('active', true)->count();
        $inactiveClients = ApiClient::where('active', false)->count();
        $totalTransactions = Transaction::count();
        $pendingTransactions = Transaction::where('status', 'pending')->count();
        $confirmedTransactions = Transaction::where('status', 'confirmed')->count();
        $totalVolume = Transaction::where('status', 'confirmed')->sum('amount');
        
        // Recent Clients
        $recentClients = ApiClient::orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Recent Transactions
        $recentTransactions = Transaction::with('client')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        // Client Activity (Top 5 by transaction count)
        $topClients = ApiClient::withCount('transactions')
            ->orderBy('transactions_count', 'desc')
            ->take(5)
            ->get();
        
        return view('admin.dashboard', compact(
            'totalClients',
            'activeClients',
            'inactiveClients',
            'totalTransactions',
            'pendingTransactions',
            'confirmedTransactions',
            'totalVolume',
            'recentClients',
            'recentTransactions',
            'topClients'
        ));
    }
}