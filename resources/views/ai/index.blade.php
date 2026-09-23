<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('AI Assistant') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if (session('ai_result'))
                <div class="mb-6 bg-blue-50 border border-blue-200 text-blue-900 px-4 py-4 rounded-lg">
                    <p class="font-semibold">{{ session('ai_result.reply') }}</p>
                    <p class="text-sm mt-2 opacity-80">Intent: {{ session('ai_result.intent') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <p class="text-gray-600 mb-4">
                        Try commands like “បើកភ្លើងបន្ទប់ទទួលភ្ញៀវ”, “បិទភ្លើងទាំងអស់”, or “every day at 18:00 turn on the bedroom light”.
                    </p>

                    <form method="POST" action="{{ route('ai.chat') }}">
                        @csrf

                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700">Command</label>
                            <textarea
                                name="message"
                                id="message"
                                rows="4"
                                required
                                maxlength="1000"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Type a command...">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-4 flex justify-end">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Send Command
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Supported commands</h3>
                    <ul class="list-disc list-inside space-y-1 text-sm text-gray-600">
                        <li>Turn one named device on or off.</li>
                        <li>Turn all owned devices on or off.</li>
                        <li>Ask which lights are currently on.</li>
                        <li>Create a daily schedule with a time and device name.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
