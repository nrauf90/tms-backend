<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TimeZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $timeZones = [
            ['time_zone' => 'Pacific/Kwajalein', 'GMT' => '(GMT -12:00) Eniwetok, Kwajalein'],
            ['time_zone' => 'Pacific/Samoa', 'GMT' => '(GMT -11:00) Midway Island, Samoa'],
            ['time_zone' => 'Pacific/Honolulu', 'GMT' => '(GMT -10:00) Hawaii'],
            ['time_zone' => 'America/Anchorage', 'GMT' => '(GMT -9:00) Alaska'],
            ['time_zone' => 'America/Los_Angeles', 'GMT' => '(GMT -8:00) Pacific Time (US & Canada) Los Angeles, Seattle'],
            ['time_zone' => 'America/Denver', 'GMT' => '(GMT -7:00) Mountain Time (US & Canada) Denver'],
            ['time_zone' => 'America/Chicago', 'GMT' => '(GMT -6:00) Central Time (US & Canada), Chicago, Mexico City'],
            ['time_zone' => 'America/New_York', 'GMT' => '(GMT -5:00) Eastern Time (US & Canada), New York, Bogota, Lima'],
            ['time_zone' => 'Atlantic/Bermuda', 'GMT' => '(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz'],
            ['time_zone' => 'Canada/Newfoundland', 'GMT' => '(GMT -3:30) Newfoundland'],
            ['time_zone' => 'Brazil/East', 'GMT' => '(GMT -3:00) Brazil, Buenos Aires, Georgetown'],
            ['time_zone' => 'Atlantic/Azores', 'GMT' => '(GMT -2:00) Mid-Atlantic'],
            ['time_zone' => 'Atlantic/Cape_Verde', 'GMT' => '(GMT -1:00 hour) Azores, Cape Verde Islands'],
            ['time_zone' => 'Europe/London', 'GMT' => '(GMT) Western Europe Time, London, Lisbon, Casablanca'],
            ['time_zone' => 'Europe/Brussels', 'GMT' => '(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris'],
            ['time_zone' => 'Europe/Helsinki', 'GMT' => '(GMT +2:00) Kaliningrad, South Africa'],
            ['time_zone' => 'Asia/Baghdad', 'GMT' => '(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg'],
            ['time_zone' => 'Asia/Tehran', 'GMT' => '(GMT +3:30) Tehran'],
            ['time_zone' => 'Asia/Baku', 'GMT' => '(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi'],
            ['time_zone' => 'Asia/Kabul', 'GMT' => '(GMT +4:30) Kabul'],
            ['time_zone' => 'Asia/Karachi', 'GMT' => '(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent'],
            ['time_zone' => 'Asia/Calcutta', 'GMT' => '(GMT +5:30) Bombay, Calcutta, Madras, New Delhi'],
            ['time_zone' => 'Asia/Dhaka', 'GMT' => '(GMT +6:00) Almaty, Dhaka, Colombo'],
            ['time_zone' => 'Asia/Bangkok', 'GMT' => '(GMT +7:00) Bangkok, Hanoi, Jakarta'],
            ['time_zone' => 'Asia/Hong_Kong', 'GMT' => '(GMT +8:00) Beijing, Perth, Singapore, Hong Kong'],
            ['time_zone' => 'Asia/Tokyo', 'GMT' => '(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk'],
            ['time_zone' => 'Australia/Adelaide', 'GMT' => '(GMT +9:30) Adelaide, Darwin'],
            ['time_zone' => 'Pacific/Guam', 'GMT' => '(GMT +10:00) Eastern Australia, Guam, Vladivostok'],
            ['time_zone' => 'Asia/Magadan', 'GMT' => '(GMT +11:00) Magadan, Solomon Islands, New Caledonia'],
            ['time_zone' => 'Pacific/Fiji', 'GMT' => '(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka']
        ];

        DB::table('timezones')->insert($timeZones);
    }
}
