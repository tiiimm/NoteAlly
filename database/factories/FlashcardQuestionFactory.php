<?php

namespace Database\Factories;

use App\Models\Flashcard;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FlashcardQuestion>
 */
class FlashcardQuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'flashcard_id' => Flashcard::query()->inRandomOrder()->value('id'),
            'question' => Str::random(10),
            'answer' => Str::random(10),
        ];
    }
}
