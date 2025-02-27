<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WeekDaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $weekdays = [
            ['days' => 'Monday'],
            ['days' => 'Tuesday'],
            ['days' => 'Wednesday'],
            ['days' => 'Thursday'],
            ['days' => 'Friday'],
            ['days' => 'Saturday'],
            ['days' => 'Sunday']
        ];

        DB::table('week_days')->insert($weekdays);
    }
}
