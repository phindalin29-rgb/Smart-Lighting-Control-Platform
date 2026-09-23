<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $device->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Device UID</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $device->device_uid }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Room</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $device->room->name ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Type</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $device->type }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $device->status ? 'Online' : 'Offline' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Current State</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $device->current_state ? 'ON' : 'OFF' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Last Seen</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $device->last_seen_at?->format('Y-m-d H:i:s') ?? 'Never' }}</dd>
                        </div>
                    </dl>

                    <div class="mt-6 flex gap-2">
                        <form action="{{ route('devices.turn-on', $device) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Turn ON
                            </button>
                        </form>
                        <form action="{{ route('devices.turn-off', $device) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Turn OFF
                            </button>
                        </form>
                        <a href="{{ route('devices.edit', $device) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Edit
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Activity History</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Source</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($device->states as $state)
                                    <tr>
                                        <td class="px-4 py-2">{{ $state->state ? 'ON' : 'OFF' }}</td>
                                        <td class="px-4 py-2">{{ $state->source }}</td>
                                        <td class="px-4 py-2">{{ $state->created_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-2 text-center text-gray-500">No activity yet</td>
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
