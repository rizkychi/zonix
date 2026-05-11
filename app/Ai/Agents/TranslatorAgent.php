<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Enums\Lab;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Promptable;
use Stringable;

#[Provider(Lab::Gemini)]
#[Model('gemini-2.0-flash')]
#[Temperature(0.2)]
#[Timeout(60)]
class TranslatorAgent implements Agent, Conversational, HasStructuredOutput, HasTools
{
    use Promptable;

    public function __construct(
        public readonly string $sourceLang,
        public readonly string $targetLang,
    ) {}

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return <<<TEXT
        You are a professional software UI/UX translator.
        Translate text values from "{$this->sourceLang}" to "{$this->targetLang}".
        Rules:
        - Preserve Laravel placeholders exactly as-is: :name, :count, :attribute, :seconds, etc.
        - Do NOT translate or modify the keys, only the values.
        - Return ALL keys provided in the schema output.
        - Use natural, concise language suitable for a web application interface.
        TEXT;
    }

    /**
     * Get the list of messages comprising the conversation so far.
     *
     * @return Message[]
     */
    public function messages(): iterable
    {
        return [];
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

    /**
     * Get the agent's structured output schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'translations' => $schema->array()
                ->items(
                    $schema->object(fn($s) => [
                        'key'   => $s->string()->required(),
                        'value' => $s->string()->required(),
                    ])
                )
                ->required(),
        ];
    }
}
