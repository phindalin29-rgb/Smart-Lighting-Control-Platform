<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $room->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('Home') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $room->home->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('Description') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $room->description ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('Devices') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $room->devices->count() }}</dd>
                    </div>
                </dl>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Devices</h3>
                @forelse ($room->devices as $device)
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <div>
                            <p class="font-medium text-gray-800">{{ $device->name }}</p>
                            <p class="text-sm text-gray-500">{{ $device->type }} · {{ $device->device_uid }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $device->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $device->status ? 'Online' : 'Offline' }}
                        </span>
                    </div>
                @empty
                    <p class="text-gray-500">{{ __('No devices yet.') }}</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
