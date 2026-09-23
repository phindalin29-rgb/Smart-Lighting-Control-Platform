<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $automation->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('Device') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $automation->device->name ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('Condition') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $automation->condition_type }}: {{ $automation->condition_value ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('Action') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $automation->action === 'on' ? 'ON' : 'OFF' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('Active') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $automation->is_active ? 'Yes' : 'No' }}</dd>
                    </div>
                </dl>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('automation.edit', $automation) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">{{ __('Edit') }}</a>
            </div>
        </div>
    </div>
</x-app-layout>
