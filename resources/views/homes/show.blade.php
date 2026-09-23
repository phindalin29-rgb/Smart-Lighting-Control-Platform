<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $home->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('Description') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $home->description ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('Rooms') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $home->rooms->count() }}</dd>
                    </div>
                </dl>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Rooms</h3>
                @forelse ($home->rooms as $room)
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <div>
                            <p class="font-medium text-gray-800">{{ $room->name }}</p>
                            <p class="text-sm text-gray-500">{{ $room->description ?? '-' }}</p>
                        </div>
                        <a href="{{ route('rooms.show', $room) }}" class="text-blue-600 hover:text-blue-900">{{ __('View') }}</a>
                    </div>
                @empty
                    <p class="text-gray-500">{{ __('No rooms yet.') }}</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
