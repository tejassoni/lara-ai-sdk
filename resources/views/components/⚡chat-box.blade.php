<?php

use Livewire\Component;
use App\Ai\Agents\ChatAgent;

new class extends Component {
    public $userInput = '';
    public bool $isLoading = false;
    public array $chatMessages = []; // Array to store chat response messages

    // form submit here
    public function sendMessage()
    {
        // Validate the user input
        $this->validate([
            'userInput' => 'required|string|max:1000',
        ]);

        // Set loading state to true
        $this->isLoading = true;
        $userInput = $this->userInput;

        // User prompt
        $this->chatMessages[] = [
            'role' => 'user',
            'userInput' => $userInput,
            'response' => '',
            'timestamp' => now()->format('d-m-Y h:i:s A'),
            'isLoading' => $this->isLoading,
            'content' => '',
        ];

        try {
            $response = new ChatAgent()->prompt($userInput);
            // AI response
            $this->chatMessages[] = [
                'role' => 'assistant',
                'userInput' => $userInput,
                'response' => (string) $response,
                'timestamp' => now()->format('d-m-Y h:i:s A'),
                'isLoading' => $this->isLoading,
                'content' => $response->text ?? '',
            ];
        } catch (\Exception $e) {
            // Handle the exception and set an error message
            $this->chatMessages[] = [
                'role' => 'assistant',
                'userInput' => $userInput,
                'response' => 'An error occurred while processing your request. Please try again later.',
                'timestamp' => now()->toDateTimeString(),
                'isLoading' => false,
                'content' => '',
            ];
            // Clear the input field
            $this->userInput = '';
            // Set loading state to false
            $this->isLoading = false;
            return;
        }
        // Clear the input field
        $this->userInput = '';
        // Set loading state to false
        $this->isLoading = false;
        return;
    }

    // Clear chat messages
    public function clearChat()
    {
        $this->userInput = '';
        $this->chatMessages = [];
    }
};
?>

<div class="flex min-h-0 flex-1 flex-col overflow-hidden bg-[radial-gradient(circle_at_top,rgba(59,130,246,0.12),transparent_32%),linear-gradient(180deg,#111214_0%,#0b0c0f_100%)] text-zinc-100">
    <header class="flex shrink-0 items-center justify-between border-b border-white/10 bg-white/5 px-4 py-4 backdrop-blur">
        <div>
            <h1 class="text-lg font-semibold tracking-tight text-white">Chat</h1>
            <p class="mt-1 text-xs text-zinc-400">Ask questions, review answers, and keep the conversation going.</p>
        </div>

        <button wire:click="clearChat"
            class="inline-flex items-center rounded-full border border-red-500/30 bg-red-500/15 px-3 py-1.5 text-sm font-medium text-red-200 transition hover:border-red-400/50 hover:bg-red-500/25 hover:text-white">
            Clear Chat
        </button>
    </header>

    <div class="flex-1 overflow-y-auto px-4 py-6">
        <div class="mx-auto flex w-full max-w-4xl flex-col gap-4">
            @if (count($chatMessages) > 0)
                @foreach ($chatMessages as $message)
                    @php
                        $isUser = $message['role'] === 'user';
                    @endphp

                    <div class="flex {{ $isUser ? 'justify-end' : 'justify-start' }}">
                        <div class="flex max-w-[85%] gap-3 sm:max-w-[78%] {{ $isUser ? 'flex-row-reverse' : 'flex-row' }}">
                            <div class="mt-1 flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl text-xs font-semibold uppercase tracking-wide shadow-lg {{ $isUser ? 'bg-blue-500 text-white' : 'bg-emerald-500 text-white' }}">
                                {{ $isUser ? 'You' : 'AI' }}
                            </div>

                            <div class="space-y-2">
                                <div class="rounded-2xl border border-white/10 px-4 py-3 shadow-xl {{ $isUser ? 'bg-blue-500/15 text-blue-50' : 'bg-white/8 text-zinc-50' }}">
                                    <p class="whitespace-pre-wrap text-sm leading-6">{{ $isUser ? $message['userInput'] : $message['response'] }}</p>
                                </div>

                                <div class="text-xs text-zinc-500 {{ $isUser ? 'text-right' : 'text-left' }}">
                                    {{ $message['timestamp'] }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="flex min-h-[calc(100vh-14rem)] items-center justify-center">
                    <div class="w-full max-w-lg rounded-3xl border border-white/10 bg-white/5 p-8 text-center shadow-2xl backdrop-blur">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-linear-to-br from-blue-500 to-cyan-400 text-lg font-bold text-white shadow-lg shadow-blue-500/25">
                            AI
                        </div>
                        <h2 class="mt-5 text-2xl font-semibold tracking-tight text-white">Welcome to Lara AI SDK Chat</h2>
                        <p class="mt-3 text-sm leading-6 text-zinc-400">Start a conversation below. Messages will appear in a cleaner, more readable layout.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="shrink-0 border-t border-white/10 bg-zinc-950/90 px-4 py-4 backdrop-blur">
        <div class="mx-auto w-full max-w-4xl">
            <form wire:submit.prevent="sendMessage" class="flex flex-col gap-3 sm:flex-row sm:items-end">
                <div class="flex-1">
                    <label for="chat-input" class="mb-2 block text-xs font-medium uppercase tracking-[0.18em] text-zinc-500">Message</label>
                    <input id="chat-input" type="text" wire:model.defer="userInput" placeholder="Type your message..."
                        class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none transition placeholder:text-zinc-500 focus:border-blue-400/60 focus:bg-white/8 focus:ring-4 focus:ring-blue-500/10" />
                </div>

                <button type="submit" @disabled($isLoading)
                    class="inline-flex min-h-12 items-center justify-center rounded-2xl bg-linear-to-r from-blue-500 to-cyan-400 px-5 py-3 font-semibold text-white shadow-lg shadow-blue-500/20 transition hover:brightness-110 disabled:cursor-not-allowed disabled:opacity-60">
                    <span wire:loading.remove wire:target="sendMessage">Send</span>
                    <span wire:loading wire:target="sendMessage">Sending...</span>
                </button>
            </form>
        </div>
    </div>
</div>
