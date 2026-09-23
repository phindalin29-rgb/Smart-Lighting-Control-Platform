<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($schedule) ? 'Edit Schedule' : 'Create Schedule' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ isset($schedule) ? route('schedules.update', $schedule) : route('schedules.store') }}" method="POST">
                    @csrf
                    @if (isset($schedule))
                        @method('PATCH')
                        <input type="hidden" name="_method" value="PATCH">
                    @endif

                    <div class="mb-4">
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input id="name" name="name" type="text" value="{{ old('name', $schedule->name ?? '') }}" class="mt-1 block w-full" />
                        @error('name')
                            <x-input-error :messages="$messages" class="mt-2" />
                        @enderror
                    </div>

                    <div class="mb-4">
                        <x-input-label for="device_id" :value="__('Device')" />
                        <select id="device_id" name="device_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">{{ __('Select Device') }}</option>
                            @foreach ($devices as $device)
                                <option value="{{ $device->id }}" {{ (old('device_id', $schedule->device_id ?? '')) == $device->id ? 'selected' : '' }}>{{ $device->name }} ({{ $device->room->name }})</option>
                            @endforeach
                        </select>
                        @error('device_id')
                            <x-input-error :messages="$messages" class="mt-2" />
                        @enderror
                    </div>

                    <div class="mb-4">
                        <x-input-label for="action" :value="__('Action')" />
                        <select id="action" name="action" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="on" {{ (old('action', $schedule->action ?? 'on')) === 'on' ? 'selected' : '' }}>ON</option>
                            <option value="off" {{ (old('action', $schedule->action ?? 'on')) === 'off' ? 'selected' : '' }}>OFF</option>
                        </select>
                        @error('action')
                            <x-input-error :messages="$messages" class="mt-2" />
                        @enderror
                    </div>

                    <div class="mb-4">
                        <x-input-label for="scheduled_time" :value="__('Time')" />
                        <input id="scheduled_time" name="scheduled_time" type="time" value="{{ old('scheduled_time', $schedule->scheduled_time?->format('H:i') ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required />
                        @error('scheduled_time')
                            <x-input-error :messages="$messages" class="mt-2" />
                        @enderror
                    </div>

                    <div class="mb-4">
                        <x-input-label for="repeat_type" :value="__('Repeat')" />
                        <select id="repeat_type" name="repeat_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="once" {{ (old('repeat_type', $schedule->repeat_type ?? 'once')) === 'once' ? 'selected' : '' }}>Once</option>
                            <option value="daily" {{ (old('repeat_type', $schedule->repeat_type ?? 'once')) === 'daily' ? 'selected' : '' }}>Daily</option>
                            <option value="weekly" {{ (old('repeat_type', $schedule->repeat_type ?? 'once')) === 'weekly' ? 'selected' : '' }}>Weekly</option>
                        </select>
                        @error('repeat_type')
                            <x-input-error :messages="$messages" class="mt-2" />
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $schedule->is_active ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500" />
                            <span class="ms-2 text-sm text-gray-700">{{ __('Active') }}</span>
                        </label>
                    </div>

                    <div class="flex justify-end">
                        <x-primary-button class="ms-3">{{ isset($schedule) ? __('Update Schedule') : __('Create Schedule') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
