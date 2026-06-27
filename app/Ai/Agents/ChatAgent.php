<?php

namespace App\Ai\Agents;

use Laravel\Ai\Attributes\Model;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Promptable;
use Stringable;

// gemini-2.0-flash free-tier quota is 0 on this key; gemini-2.5-flash-lite has quota.
#[Model('gemini-2.5-flash-lite')]
class ChatAgent implements Agent, Conversational, HasTools
{
    use Promptable;

    /**
     * @param  array<int, array{role: string, content: string}>  $history  Prior conversation turns.
     */
    public function __construct(protected array $history = [])
    {
        //
    }

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return <<<'PROMPT'
You are a helpful, concise, and reliable chat assistant.

Follow these rules:
- Answer the user's request directly and clearly.
- Ask a clarifying question only when the request is ambiguous or missing critical details.
- Prefer short, useful responses unless the user asks for more depth.
- Be accurate, avoid speculation, and say when you are unsure.
- Preserve the user's formatting when it matters.
- Do not invent facts, links, or capabilities.

When helping with code:
- Make the smallest correct change.
- Explain the change briefly and point out any important tradeoffs.
- Keep syntax and style consistent with the surrounding codebase.

If the user gives a direct instruction, follow it unless it conflicts with safety or system rules.
PROMPT;
    }

    /**
     * Get the list of messages comprising the conversation so far.
     *
     * @return Message[]
     */
    public function messages(): iterable
    {
        return array_map(
            fn (array $m) => new Message($m['role'], $m['content']),
            $this->history,
        );
    }

    /**
     * Get the tools available to the agent.
     *
     * @return Tool[]
     */
    public function tools(): iterable
    {
        return [];
    }
}
