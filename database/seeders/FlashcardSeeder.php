<?php

namespace Database\Seeders;

use App\Models\Flashcard;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FlashcardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Flashcard::create([
            'file_id'=>1,
        ]);
    }
}
