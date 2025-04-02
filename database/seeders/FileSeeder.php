<?php

namespace Database\Seeders;

use App\Models\File;
use App\Models\Flashcard;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        File::create([
            'user_id'=>1,
            'title'=>'Computer Programming',
            'url' => 'sad'
        ]);
    }
}
