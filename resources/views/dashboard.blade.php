@php
    $maxChart = $chartData->max('count') ?: 1;
    $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
    $cssFile = '/build/' . ($manifest['resources/css/app.css']['file'] ?? 'assets/app.css');
    $jsFile = '/build/' . ($manifest['resources/js/app.js']['file'] ?? 'assets/app.js');
@endphp
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>SmartHome Dashboard</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="{{ $cssFile }}">
        <script src="{{ $jsFile }}" defer></script>
    </head>
    <body class="font-sans antialiased">

<div class="min-h-screen bg-gray-900 text-white">
    <aside class="fixed left-0 top-0 h-full w-64 bg-gray-800/80 backdrop-blur-md border-r border-white/10 z-50 flex flex-col">
        <div class="p-6 border-b border-white/10">
            <h1 class="text-xl font-bold tracking-wider">⚡ SmartHome</h1>
        </div>
        <nav class="flex-1 p-4 space-y-1">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-white/10 text-white font-medium">
                <span>🏠</span> Dashboard
            </a>
            <a href="{{ route('homes.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:bg-white/5 hover:text-white transition">
                <span>🏘️</span> Homes
            </a>
            <a href="{{ route('rooms.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:bg-white/5 hover:text-white transition">
                <span>🚪</span> Rooms
            </a>
            <a href="{{ route('devices.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:bg-white/5 hover:text-white transition">
                <span>💡</span> Devices
            </a>
            <a href="{{ route('schedules.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:bg-white/5 hover:text-white transition">
                <span>📅</span> Schedules
            </a>
            <a href="{{ route('automation.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:bg-white/5 hover:text-white transition">
                <span>⚙️</span> Automation
            </a>
            <a href="{{ route('reports.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:bg-white/5 hover:text-white transition">
                <span>📊</span> Reports
            </a>
            <a href="{{ route('ai.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:bg-white/5 hover:text-white transition">
                <span>🤖</span> AI Assistant
            </a>
        </nav>
        <div class="p-4 border-t border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center font-bold">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div>
                    <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-400">{{ auth()->user()->email }}</p>
                </div>
            </div>
        </div>
    </aside>

    <div class="ml-64">
        <header class="bg-gray-800/60 backdrop-blur-md border-b border-white/10 px-8 py-4 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold">Dashboard</h2>
                <p class="text-sm text-gray-400">Rooms & Devices Overview</p>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('reports.index') }}" class="px-4 py-2 bg-white/10 hover:bg-white/20 rounded-lg text-sm transition">📊 Reports</a>
                <a href="{{ route('ai.index') }}" class="px-4 py-2 bg-white/10 hover:bg-white/20 rounded-lg text-sm transition">🤖 AI</a>
            </div>
        </header>

        <main class="p-8" style="background: url('/images/smart-home-bg.png') center/cover no-repeat; min-height: 100vh;">
            <div class="absolute inset-0 bg-gray-900/85"></div>
            <div class="relative z-10">

                <div class="mb-8">
                    <h2 class="text-3xl font-bold">Hello, {{ auth()->user()->name }} 👋</h2>
                    <p class="text-gray-400 mt-1">How's your day going?</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

                    <div class="bg-white/10 backdrop-blur-lg border border-white/15 rounded-2xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold">⚡ Energy Usage</h3>
                            <span class="text-sm text-gray-400">Today</span>
                        </div>
                        <div class="flex items-end gap-2 mb-3">
                            <span class="text-4xl font-bold">{{ $energyUsage }}%</span>
                            <span class="text-lg text-gray-400 pb-1">
                                @if($energyUsage < 40) Low
                                @elseif($energyUsage < 70) Medium
                                @else High
                                @endif
                            </span>
                        </div>
                        <div class="w-full bg-gray-700/50 rounded-full h-4 mb-2">
                            <div class="h-4 rounded-full transition-all duration-1000
                                @if($energyUsage < 40) bg-green-400
                                @elseif($energyUsage < 70) bg-yellow-400
                                @else bg-red-400
                                @endif"
                                style="width: {{ $energyUsage }}%"></div>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500">
                            <span>0%</span>
                            <span>50%</span>
                            <span>100%</span>
                        </div>
                    </div>

                    <div class="bg-white/10 backdrop-blur-lg border border-white/15 rounded-2xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold">🛋️ {{ $homes->first()->name ?? 'Living Room' }}</h3>
                        </div>
@if($homes->isNotEmpty())
                                @foreach($homes->first()->rooms->take(1) as $room)
                                <div class="flex items-center justify-between bg-white/5 rounded-xl px-5 py-4 mb-3">
                                    <div>
                                        <p class="font-semibold">💡 {{ $room->devices->first()->name ?? 'No devices' }}</p>
                                        <p class="text-sm text-gray-400">{{ $room->name }}</p>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <span class="text-2xl font-bold">
                                            {{ $room->devices->first()?->current_state ? '💡 ON' : '⚫ OFF' }}
                                        </span>
                                        <span class="text-2xl">
                                            {{ $room->devices->first()?->current_state ? '23°' : '--°' }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <div class="bg-white/10 backdrop-blur-lg border border-white/15 rounded-2xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold">📶 Wi-Fi</h3>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-green-400 animate-pulse"></span>
                            <span class="text-xl font-semibold">Connected</span>
                        </div>
                        <div class="mt-3 text-sm text-gray-400">
                            <p>Signal: Strong</p>
                            <p>Latency: 12ms</p>
                        </div>
                    </div>

                    <div class="bg-white/10 backdrop-blur-lg border border-white/15 rounded-2xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold">💡 Lighting Brightness</h3>
                        </div>
                        <div class="text-center mb-4">
                            <span class="text-5xl font-bold">{{ $brightness }}%</span>
                        </div>
                        <div class="relative w-full h-3 bg-gray-700/50 rounded-full">
                            <div class="absolute left-0 top-0 h-3 bg-cyan-400 rounded-full transition-all duration-1000" style="width: {{ $brightness }}%"></div>
                            <div class="absolute top-1/2 w-6 h-6 bg-white rounded-full shadow-lg transform -translate-y-1/2 transition-all duration-1000" style="left: calc({{ $brightness }}% - 12px)"></div>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500 mt-2">
                            <span>0%</span>
                            <span>50%</span>
                            <span>100%</span>
                        </div>
                    </div>

                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    <div class="lg:col-span-2 bg-white/10 backdrop-blur-lg border border-white/15 rounded-2xl p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold">📊 Energy / Usage Chart</h3>
                            <span class="text-sm text-gray-400">This Week</span>
                        </div>
                        <div class="flex items-end gap-4 h-40">
                            @foreach($chartData as $item)
                                <div class="flex-1 flex flex-col items-center gap-2">
                                    <span class="text-xs text-gray-400">{{ $item['day'] }}</span>
                                    <div class="w-full bg-gray-700/30 rounded-t-lg relative" style="height: 120px;">
                                        <div class="absolute bottom-0 w-full bg-gradient-to-t from-cyan-600/80 to-cyan-400/80 rounded-t-lg transition-all duration-1000"
                                             style="height: {{ ($item['count'] / $maxChart * 100) }}%">
                                        </div>
                                    </div>
                                    <span class="text-xs font-semibold">{{ $item['count'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="bg-white/10 backdrop-blur-lg border border-white/15 rounded-2xl p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-xl">🤖</div>
                            <h3 class="text-lg font-semibold">AI Assistant</h3>
                        </div>
                        @if($lightsOn > 0)
                            <p class="text-sm text-gray-300 mb-4">"You have <strong class="text-cyan-400">{{ $lightsOn }}</strong> lights ON. Would you like to reduce energy usage?"</p>
                        @else
                            <p class="text-sm text-gray-300 mb-4">"All lights are off. Great energy saving!"</p>
                        @endif
                        <form id="aiForm" class="flex gap-2">
                            <input type="hidden" name="message" value="optimize">
                            <button type="submit" class="px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 rounded-lg text-sm font-medium transition">
                                Optimize Energy
                            </button>
                        </form>
                        <div id="aiResponse" class="mt-3 text-sm text-gray-400 hidden"></div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <div class="bg-white/10 backdrop-blur-lg border border-white/15 rounded-2xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold">💡 Smart Lighting</h3>
                            <a href="{{ route('devices.index') }}" class="text-cyan-400 hover:text-cyan-300 text-sm">View All →</a>
                        </div>
                        <div class="space-y-3">
                            @forelse($homes as $home)
                                @forelse($home->rooms as $room)
                                    @forelse($room->devices as $device)
                                        <div class="flex items-center justify-between bg-white/5 rounded-lg px-4 py-3">
                                            <div>
                                                <p class="font-medium">{{ $device->name }}</p>
                                                <p class="text-xs text-gray-400">{{ $room->name }}</p>
                                            </div>
                                            <form action="{{ route('devices.control', $device) }}" method="POST" class="inline">
                                                @csrf
                                                @if($device->current_state)
                                                    <button type="submit" name="action" value="off" class="px-3 py-1 bg-gray-500/50 hover:bg-gray-500/80 rounded text-sm transition">OFF</button>
                                                @else
                                                    <button type="submit" name="action" value="on" class="px-3 py-1 bg-cyan-500/80 hover:bg-cyan-500 rounded text-sm transition">ON</button>
                                                @endif
                                            </form>
                                        </div>
                                    @empty
                                        <p class="text-sm text-gray-500 italic">No devices in {{ $room->name }}</p>
                                    @endforelse
                                @empty
                                    <p class="text-sm text-gray-500 italic">No rooms in {{ $home->name }}</p>
                                @endforelse
                            @empty
                                <p class="text-gray-500">No homes yet.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="bg-white/10 backdrop-blur-lg border border-white/15 rounded-2xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold">⚡ Energy Monitoring</h3>
                            <a href="{{ route('reports.index') }}" class="text-cyan-400 hover:text-cyan-300 text-sm">Full Report →</a>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-center bg-white/5 rounded-xl p-4">
                                <p class="text-2xl font-bold">{{ $totalDevices }}</p>
                                <p class="text-xs text-gray-400 mt-1">Total Devices</p>
                            </div>
                            <div class="text-center bg-white/5 rounded-xl p-4">
                                <p class="text-2xl font-bold text-cyan-400">{{ $onlineDevices }}</p>
                                <p class="text-xs text-gray-400 mt-1">Online</p>
                            </div>
                            <div class="text-center bg-white/5 rounded-xl p-4">
                                <p class="text-2xl font-bold text-yellow-400">{{ $lightsOn }}</p>
                                <p class="text-xs text-gray-400 mt-1">Lights ON</p>
                            </div>
                        </div>
                        <div class="mt-4 grid grid-cols-2 gap-4">
                            <div class="text-center bg-white/5 rounded-xl p-3">
                                <p class="text-lg font-semibold">{{ $lightsOff }}</p>
                                <p class="text-xs text-gray-400">Lights OFF</p>
                            </div>
                            <div class="text-center bg-white/5 rounded-xl p-3">
                                <p class="text-lg font-semibold">{{ $schedules->count() }}</p>
                                <p class="text-xs text-gray-400">Active Schedules</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 bg-white/10 backdrop-blur-lg border border-white/15 rounded-2xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold">🏠 Rooms & Devices</h3>
                            <a href="{{ route('rooms.index') }}" class="text-cyan-400 hover:text-cyan-300 text-sm">Manage Rooms →</a>
                        </div>
                        @forelse($homes as $home)
                            <div class="mb-6">
                                <h4 class="text-md font-semibold text-gray-300 mb-3">🏠 {{ $home->name }}</h4>
                                @forelse($home->rooms as $room)
                                    <div class="border border-white/10 rounded-xl p-4 mb-3 bg-white/5">
                                        <div class="flex justify-between items-center mb-3">
                                            <h5 class="font-medium">🛋️ {{ $room->name }}</h5>
                                            <span class="text-xs text-gray-400">{{ $room->devices->count() }} device(s)</span>
                                        </div>
                                        @forelse($room->devices as $device)
                                            <div class="flex items-center justify-between bg-gray-800/50 rounded-lg px-4 py-3 mb-2">
                                                <div>
                                                    <p class="font-medium text-white">💡 {{ $device->name }}</p>
                                                    <p class="text-sm text-gray-500">{{ $device->type }} · {{ $device->device_uid }}</p>
                                                </div>
                                                <div class="flex items-center gap-3">
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $device->status ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                                                        {{ $device->status ? '● Online' : '● Offline' }}
                                                    </span>
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $device->current_state ? 'bg-cyan-500/20 text-cyan-400' : 'bg-gray-600/30 text-gray-400' }}">
                                                        {{ $device->current_state ? '💡 ON' : '⚫ OFF' }}
                                                    </span>
                                                    <form action="{{ route('devices.control', $device) }}" method="POST" class="inline">
                                                        @csrf
                                                        @if($device->current_state)
                                                            <button type="submit" name="action" value="off" class="bg-gray-500/50 hover:bg-gray-500 text-white text-sm font-bold py-1 px-3 rounded transition">
                                                                Turn OFF
                                                            </button>
                                                        @else
                                                            <button type="submit" name="action" value="on" class="bg-cyan-500 hover:bg-cyan-600 text-white text-sm font-bold py-1 px-3 rounded transition">
                                                                Turn ON
                                                            </button>
                                                        @endif
                                                    </form>
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-sm text-gray-500 italic">No devices in this room yet.</p>
                                        @endforelse
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500 italic">No rooms yet.</p>
                                @endforelse
                            </div>
                        @empty
                            <p class="text-gray-500">No homes yet. Start by creating your first home.</p>
                        @endforelse
                    </div>

                    <div class="bg-white/10 backdrop-blur-lg border border-white/15 rounded-2xl p-6">
                        <h3 class="text-lg font-semibold mb-4">🕐 Recent Activity</h3>
                        @forelse($recentActivity as $log)
                            <div class="flex items-start gap-3 mb-3 pb-3 border-b border-white/5">
                                <div class="w-2 h-2 rounded-full mt-2 {{ $log->action === 'on' ? 'bg-green-400' : ($log->action === 'off' ? 'bg-red-400' : 'bg-blue-400') }}">
                                </div>
                                <div>
                                    <p class="text-sm">{{ str_replace('_', ' ', $log->action) }} {{ $log->device?->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $log->created_at->format('M d, Y H:i') }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No activity yet.</p>
                        @endforelse
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

<script>
document.getElementById('aiForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = this.querySelector('button');
    const response = document.getElementById('aiResponse');
    btn.disabled = true;
    btn.textContent = 'Thinking...';
    response?.classList.remove('hidden');
    response.textContent = 'Analyzing your energy usage...';

    fetch("{{ route('ai.dashboard.chat') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
        },
        body: JSON.stringify({ message: 'optimize energy usage' })
    })
    .then(r => r.json())
    .then(data => {
        response.textContent = data.reply || 'Unable to get AI response.';
        btn.disabled = false;
        btn.textContent = 'Optimize Energy';
    })
    .catch(err => {
        response.textContent = 'AI service unavailable.';
        btn.disabled = false;
        btn.textContent = 'Optimize Energy';
    });
});
</script>
    </body>
</html>