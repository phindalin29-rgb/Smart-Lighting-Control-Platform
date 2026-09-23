<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">Total Devices</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalDevices }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">Lights ON</p>
                    <p class="text-3xl font-bold text-yellow-600 mt-1">{{ $lightsOn }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">Lights OFF</p>
                    <p class="text-3xl font-bold text-gray-400 mt-1">{{ $lightsOff }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">Online</p>
                    <p class="text-3xl font-bold text-green-600 mt-1">{{ $onlineDevices }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">📊 Weekly Activity</h3>
                    <div class="flex items-end gap-4 h-40">
                        @foreach($chartData as $item)
                            <div class="flex-1 flex flex-col items-center gap-2">
                                <span class="text-xs text-gray-500">{{ $item['day'] }}</span>
                                <div class="w-full bg-gray-200 rounded-t-lg relative" style="height: 100px;">
                                    <div class="absolute bottom-0 w-full bg-blue-500 rounded-t-lg transition-all duration-500"
                                         style="height: {{ ($item['count'] / max($chartData->max('count'), 1) * 100) }}%"></div>
                                </div>
                                <span class="text-xs font-semibold">{{ $item['count'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">📅 Schedules Overview</h3>
                    @forelse($schedules->take(5) as $schedule)
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <div>
                                <p class="text-sm font-medium">{{ $schedule->name ?? $schedule->device->name }}</p>
                                <p class="text-xs text-gray-500">{{ $schedule->device->room->name ?? '' }} · {{ $schedule->scheduled_time }}</p>
                            </div>
                            <span class="text-xs px-2 py-1 rounded-full {{ $schedule->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-800' }}">
                                {{ $schedule->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No schedules.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Recent Activity</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Device</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Source</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($activityLogs as $log)
                                    <tr>
                                        <td class="px-4 py-2">{{ str_replace('_', ' ', $log->action) }}</td>
                                        <td class="px-4 py-2">{{ $log->device?->name ?? 'System' }}</td>
                                        <td class="px-4 py-2">{{ $log->source ?? 'manual' }}</td>
                                        <td class="px-4 py-2">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-2 text-center text-gray-500">No activity.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
