<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages = [
            ['language_name' => 'Arabic', 'language_code' => 'AR', 'status' => 0],
            ['language_name' => 'Urdu', 'language_code' => 'UR', 'status' => 0],
            ['language_name' => 'English (UK)', 'language_code' => 'EN (UK)', 'status' => 1],
            ['language_name' => 'French', 'language_code' => 'fr', 'status' => 0],
            ['language_name' => 'Turkish', 'language_code' => 'tr', 'status' => 0],
            ['language_name' => 'Italian', 'language_code' => 'it', 'status' => 0],
        ];

        DB::table('languages')->insert($languages);
    }
}
