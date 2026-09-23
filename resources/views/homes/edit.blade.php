<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ 'Edit Home' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('homes.update', $home) }}" method="POST">
                    @csrf
                    <input type="hidden" name="_method" value="PATCH">

                    <div class="mb-4">
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input id="name" name="name" type="text" value="{{ old('name', $home->name) }}" class="mt-1 block w-full" required autofocus />
                        @error('name')
                            <x-input-error :messages="$messages" class="mt-2" />
                        @enderror
                    </div>

                    <div class="mb-4">
                        <x-input-label for="description" :value="__('Description')" />
                        <textarea id="description" name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $home->description ?? '') }}</textarea>
                        @error('description')
                            <x-input-error :messages="$messages" class="mt-2" />
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <x-primary-button class="ms-3">{{ __('Update Home') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
