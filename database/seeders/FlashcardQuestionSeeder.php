<?php

namespace Database\Seeders;

use App\Models\FlashcardQuestion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FlashcardQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FlashcardQuestion::factory(5)->create();
    }
}
