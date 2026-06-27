<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div class="flex h-screen overflow-hidden bg-zinc-950">
    {{-- Side Bar Starts --}}
    <aside class="flex h-screen w-64 flex-shrink-0 flex-col bg-zinc-900 border-r border-zinc-800">
        <div class="flex flex-1 flex-col">
            <div class="flex items-center justify-center h-16 border-b border-zinc-800">
                <h1 class="text-lg font-semibold text-white">Lara AI SDK</h1>
            </div>
            <div class="flex-1 overflow-y-auto">
                <nav class="px-4 py-6 space-y-2">
                    <a href="{{ route('chat') }}" class="block px-4 py-2 text-white rounded hover:bg-zinc-800">Chat</a>
                    <a href="{{ route('home') }}" class="block px-4 py-2 text-white rounded hover:bg-zinc-800">Home</a>
                </nav>
            </div>
        </div>

        {{-- New chat button starts --}}
        <div class="mt-auto flex w-full items-center justify-center border-t border-zinc-800 p-4">
            <a href="{{ route('chat') }}" class="block px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">New Chat</a>
        </div>
        {{-- New chat button ends --}}

        {{-- Footer Status Starts --}}
        <div class="mt-auto flex w-full items-center justify-center border-t border-zinc-800 p-4">
            <p class="text-sm text-white">Powered by Lara AI SDK</p>
        </div>
        {{-- Footer Status Ends --}}

    </aside>
    {{-- Side Bar Ends --}}

    <livewire:chat-box />
</div>
