<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($rule) ? 'Edit Rule' : 'Create Rule' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ isset($rule) ? route('automation.update', $rule) : route('automation.store') }}" method="POST">
                    @csrf
                    @if (isset($rule))
                        @method('PATCH')
                        <input type="hidden" name="_method" value="PATCH">
                    @endif

                    <div class="mb-4">
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input id="name" name="name" type="text" value="{{ old('name', $rule->name ?? '') }}" class="mt-1 block w-full" required autofocus />
                        @error('name')
                            <x-input-error :messages="$messages" class="mt-2" />
                        @enderror
                    </div>

                    <div class="mb-4">
                        <x-input-label for="device_id" :value="__('Device')" />
                        <select id="device_id" name="device_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">{{ __('Select Device') }}</option>
                            @foreach ($devices as $device)
                                <option value="{{ $device->id }}" {{ (old('device_id', $rule->device_id ?? '')) == $device->id ? 'selected' : '' }}>{{ $device->name }} ({{ $device->room->name }})</option>
                            @endforeach
                        </select>
                        @error('device_id')
                            <x-input-error :messages="$messages" class="mt-2" />
                        @enderror
                    </div>

                    <div class="mb-4">
                        <x-input-label for="condition_type" :value="__('Condition Type')" />
                        <select id="condition_type" name="condition_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="time" {{ (old('condition_type', $rule->condition_type ?? 'time')) === 'time' ? 'selected' : '' }}>{{ __('Time') }}</option>
                            <option value="device_state" {{ (old('condition_type', $rule->condition_type ?? 'time')) === 'device_state' ? 'selected' : '' }}>{{ __('Device State') }}</option>
                        </select>
                        @error('condition_type')
                            <x-input-error :messages="$messages" class="mt-2" />
                        @enderror
                    </div>

                    <div class="mb-4">
                        <x-input-label for="condition_value" :value="__('Condition Value')" />
                        <x-text-input id="condition_value" name="condition_value" type="text" value="{{ old('condition_value', $rule->condition_value ?? '') }}" class="mt-1 block w-full" />
                        <p class="mt-1 text-xs text-gray-500">{{ __('For time, use HH:MM. For device_state, use on or off.') }}</p>
                        @error('condition_value')
                            <x-input-error :messages="$messages" class="mt-2" />
                        @enderror
                    </div>

                    <div class="mb-4">
                        <x-input-label for="action" :value="__('Action')" />
                        <select id="action" name="action" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="on" {{ (old('action', $rule->action ?? 'on')) === 'on' ? 'selected' : '' }}>ON</option>
                            <option value="off" {{ (old('action', $rule->action ?? 'on')) === 'off' ? 'selected' : '' }}>OFF</option>
                        </select>
                        @error('action')
                            <x-input-error :messages="$messages" class="mt-2" />
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $rule->is_active ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500" />
                            <span class="ms-2 text-sm text-gray-700">{{ __('Active') }}</span>
                        </label>
                    </div>

                    <div class="flex justify-end">
                        <x-primary-button class="ms-3">{{ isset($rule) ? __('Update Rule') : __('Create Rule') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
