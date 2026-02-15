@extends('layouts.admin')

@section('page-title', 'Command Execution')
@section('page-subtitle', 'Execute Bitcoin RPC commands directly')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Command Execution Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-terminal mr-2 text-blue-500"></i>Execute Command
        </h3>
        
        <form action="{{ route('admin.bitcoin.execute') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label for="command" class="block text-gray-700 font-bold mb-2">
                    <i class="fas fa-code mr-2 text-gray-500"></i>Command *
                </label>
                <input type="text" id="command" name="command" value="{{ old('command') }}" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono" 
                       placeholder="getblockchaininfo" required>
                @error('command')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="parameters" class="block text-gray-700 font-bold mb-2">
                    <i class="fas fa-list mr-2 text-gray-500"></i>Parameters (JSON or comma-separated)
                </label>
                <textarea id="parameters" name="parameters" rows="3" 
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono" 
                          placeholder='["param1", "param2"] or param1, param2'>{{ old('parameters') }}</textarea>
                <p class="text-sm text-gray-600 mt-2">
                    <i class="fas fa-info-circle mr-1"></i>Leave empty for commands without parameters
                </p>
            </div>
            
            <div class="bg-yellow-50 border border-yellow-200 rounded p-3 mb-4">
                <p class="text-sm text-yellow-800">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <strong>Security:</strong> Dangerous commands (sendtoaddress, stop, dumpprivkey, etc.) are blocked for safety.
                </p>
            </div>
            
            <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white font-bold py-3 px-4 rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all">
                <i class="fas fa-play mr-2"></i>Execute Command
            </button>
        </form>
        
        <!-- Command Result -->
        @if(session('command_result'))
        <div class="mt-6">
            <h4 class="font-bold text-gray-800 mb-3">
                @if(session('command_result')['status'] === 'success')
                    <i class="fas fa-check-circle text-green-500 mr-2"></i>Result:
                @else
                    <i class="fas fa-times-circle text-red-500 mr-2"></i>Error:
                @endif
            </h4>
            <div class="bg-gray-900 text-green-400 p-4 rounded-lg overflow-x-auto font-mono text-sm">
                @if(session('command_result')['status'] === 'success')
                    <pre>{{ json_encode(session('command_result')['result'], JSON_PRETTY_PRINT) }}</pre>
                @else
                    <pre class="text-red-400">{{ session('command_result')['error'] }}</pre>
                @endif
            </div>
        </div>
        @endif
    </div>
    
    <!-- Available Commands Reference -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-book mr-2 text-purple-500"></i>Available Commands
        </h3>
        
        <div class="space-y-4">
            @foreach($availableCommands as $category => $commands)
            <div>
                <h4 class="font-bold text-gray-700 mb-2 capitalize">
                    <i class="fas fa-{{ $category === 'info' ? 'info-circle' : ($category === 'wallet' ? 'wallet' : 'cubes') }} mr-2 text-gray-500"></i>
                    {{ ucfirst($category) }}
                </h4>
                <div class="space-y-1">
                    @foreach($commands as $cmd => $description)
                    <div class="p-2 bg-gray-50 rounded hover:bg-gray-100 transition-colors cursor-pointer" onclick="document.getElementById('command').value='{{ $cmd }}'">
                        <code class="text-sm text-blue-600 font-semibold">{{ $cmd }}</code>
                        <p class="text-xs text-gray-600 mt-1">{{ $description }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Recent Command History -->
<div class="mt-6 bg-white rounded-lg shadow-md overflow-hidden">
    <div class="p-6 border-b">
        <h3 class="text-lg font-bold text-gray-800">
            <i class="fas fa-history mr-2 text-orange-500"></i>Recent Commands ({{ count($recentCommands) }})
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Command</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Parameters</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">User</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Executed</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($recentCommands as $log)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <code class="text-sm bg-blue-100 text-blue-800 px-2 py-1 rounded">{{ $log->command }}</code>
                    </td>
                    <td class="px-6 py-4">
                        @if($log->parameters)
                            <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ str($log->parameters)->limit(30) }}</code>
                        @else
                            <span class="text-gray-400 text-sm">None</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm">{{ $log->user->name ?? 'Unknown' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-xs font-semibold 
                            @if($log->status === 'success') bg-green-100 text-green-800
                            @elseif($log->status === 'blocked') bg-red-100 text-red-800
                            @else bg-orange-100 text-orange-800 @endif">
                            {{ ucfirst($log->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $log->executed_at->diffForHumans() }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                        <i class="fas fa-history text-4xl text-gray-300 mb-2"></i>
                        <p>No commands executed yet</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
