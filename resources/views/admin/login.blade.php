<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Bitcoin RPC Gateway</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-slate-800 via-slate-900 to-slate-800 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md">
        <!-- Login Card -->
        <div class="bg-white rounded-lg shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 p-8 text-center">
                <div class="inline-block p-4 bg-white rounded-full mb-4">
                    <i class="fab fa-bitcoin text-orange-500 text-5xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-white">Bitcoin RPC Gateway</h1>
                <p class="text-orange-100 mt-2">Administrator Login</p>
            </div>
            
            <!-- Login Form -->
            <div class="p-8">
                @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                </div>
                @endif
                
                @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    @foreach($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
                @endif
                
                <!-- Test Credentials Display -->
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        <strong class="text-blue-800">Test Credentials</strong>
                    </div>
                    <div class="text-sm text-blue-700 font-mono mt-2">
                        <div class="mb-1"><strong>Email:</strong> admin@bitcoinrpc.local</div>
                        <div><strong>Password:</strong> qwerty123</div>
                    </div>
                </div>
                
                <form action="{{ route('admin.login') }}" method="POST">
                    @csrf
                    
                    <div class="mb-6">
                        <label for="email" class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-envelope mr-2 text-gray-500"></i>Email Address
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 @error('email') border-red-500 @enderror" 
                               placeholder="admin@bitcoinrpc.local" required autofocus>
                    </div>
                    
                    <div class="mb-6">
                        <label for="password" class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-lock mr-2 text-gray-500"></i>Password
                        </label>
                        <input type="password" id="password" name="password" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 @error('password') border-red-500 @enderror" 
                               placeholder="••••••••" required>
                    </div>
                    
                    <button type="submit" class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white font-bold py-3 px-4 rounded-lg hover:from-orange-600 hover:to-orange-700 transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login to Dashboard
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="text-center mt-8 text-gray-400">
            <p class="text-sm">© {{ date('Y') }} Bitcoin RPC Gateway. Secure API Management.</p>
            <p class="text-xs mt-2">Made with ❤️ by <a href="https://laracopilot.com/" target="_blank" class="hover:text-orange-400 transition-colors">LaraCopilot</a></p>
        </div>
    </div>
</body>
</html>
