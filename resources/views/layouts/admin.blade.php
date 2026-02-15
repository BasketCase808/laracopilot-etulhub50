<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bitcoin RPC Gateway - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-gradient-to-b from-slate-800 to-slate-900 text-white flex-shrink-0">
            <div class="p-6">
                <h1 class="text-2xl font-bold flex items-center">
                    <i class="fab fa-bitcoin text-orange-500 mr-2"></i>
                    BTC Gateway
                </h1>
                <p class="text-gray-400 text-sm mt-1">Admin Panel</p>
            </div>
            
            <nav class="mt-6">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-slate-700 hover:text-white transition-all duration-300 {{ request()->routeIs('admin.dashboard') ? 'bg-slate-700 text-white border-l-4 border-orange-500' : '' }}">
                    <i class="fas fa-chart-line w-5"></i>
                    <span class="ml-3">Dashboard</span>
                </a>
                
                <a href="{{ route('admin.bitcoin.monitor') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-slate-700 hover:text-white transition-all duration-300 {{ request()->routeIs('admin.bitcoin.monitor') ? 'bg-slate-700 text-white border-l-4 border-orange-500' : '' }}">
                    <i class="fas fa-server w-5"></i>
                    <span class="ml-3">BTC Monitor</span>
                </a>
                
                <a href="{{ route('admin.bitcoin.commands') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-slate-700 hover:text-white transition-all duration-300 {{ request()->routeIs('admin.bitcoin.commands') ? 'bg-slate-700 text-white border-l-4 border-orange-500' : '' }}">
                    <i class="fas fa-terminal w-5"></i>
                    <span class="ml-3">Commands</span>
                </a>
                
                <a href="{{ route('admin.clients.index') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-slate-700 hover:text-white transition-all duration-300 {{ request()->routeIs('admin.clients.*') ? 'bg-slate-700 text-white border-l-4 border-orange-500' : '' }}">
                    <i class="fas fa-users w-5"></i>
                    <span class="ml-3">API Clients</span>
                </a>
            </nav>
            
            <div class="absolute bottom-0 w-64 p-6 border-t border-slate-700">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 rounded-full bg-orange-500 flex items-center justify-center text-white font-bold">
                        {{ substr(session('admin_user_name', 'A'), 0, 1) }}
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-semibold">{{ session('admin_user_name', 'Admin') }}</p>
                        <p class="text-xs text-gray-400">Administrator</p>
                    </div>
                </div>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 rounded text-sm transition-all duration-300">
                        <i class="fas fa-sign-out-alt mr-2"></i>
                        Logout
                    </button>
                </form>
            </div>
        </aside>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between px-8 py-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                        <p class="text-gray-600 text-sm mt-1">@yield('page-subtitle', 'Bitcoin RPC Gateway Management')</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <p class="text-sm text-gray-600">{{ now()->format('l, F j, Y') }}</p>
                            <p class="text-xs text-gray-500">{{ now()->format('g:i A') }}</p>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Content Area -->
            <main class="flex-1 overflow-y-auto bg-gray-100 p-8">
                @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded mb-6 animate-fade-in">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-3"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
                @endif
                
                @if(session('api_credentials'))
                <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-800 p-4 rounded mb-6">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-key mr-3"></i>
                        <strong>API Credentials for {{ session('api_credentials')['client_name'] }}</strong>
                    </div>
                    <div class="mt-3 bg-white p-4 rounded font-mono text-sm">
                        <div class="mb-2">
                            <strong>API Key:</strong><br>
                            <code class="bg-gray-100 px-2 py-1 rounded">{{ session('api_credentials')['api_key'] }}</code>
                        </div>
                        <div>
                            <strong>API Secret:</strong><br>
                            <code class="bg-gray-100 px-2 py-1 rounded">{{ session('api_credentials')['api_secret'] }}</code>
                        </div>
                    </div>
                    <p class="text-sm mt-3"><i class="fas fa-exclamation-triangle mr-2"></i>Save these credentials securely. The API secret cannot be retrieved again.</p>
                </div>
                @endif
                
                @if($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded mb-6">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-exclamation-circle mr-3"></i>
                        <strong>Validation Errors:</strong>
                    </div>
                    <ul class="list-disc list-inside mt-2">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
