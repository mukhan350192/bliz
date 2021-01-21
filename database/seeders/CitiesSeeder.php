<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        City::create([
           'name' => 'Алматы',
           'country_id' => 1,
           'latitude' => '43.25667',
           'longitude' => '76.92861',
        ]);

        City::create([
            'name' => 'Нур-Султан',
            'country_id' => 1,
            'latitude' => '51.1801',
            'longitude' => '71.44598',
        ]);
        
        City::create([
            'name' => 'Кызылорда',
            'country_id' => 1,
            'latitude' => '44.838661',
            'longitude' => '65.514618',
        ]);

        City::create([
            'name' => 'Актобе',
            'country_id' => 1,
            'latitude' => '46.411129',
            'longitude' => '48.447681',
        ]);

        City::create([
            'name' => 'Атырау',
            'country_id' => 1,
            'latitude' => '47.117760',
            'longitude' => '51.914501',
        ]);

        City::create([
            'name' => 'Усть-Каменогорск',
            'country_id' => 1,
            'latitude' => '45.210972',
            'longitude' => '39.691151',
        ]);

        City::create([
            'name' => 'Тараз',
            'country_id' => 1,
            'latitude' => '42.901896',
            'longitude' => '71.368859',
        ]);

        City::create([
            'name' => 'Уральск',
            'country_id' => 1,
            'latitude' => ' 51.22626819960311',
            'longitude' => '51.383724912160524',
        ]);

        City::create([
            'name' => 'Караганды',
            'country_id' => 1,
            'latitude' => '49.80344849050705',
            'longitude' => '73.11000709008925',
        ]);

        City::create([
            'name' => 'Костанай',
            'country_id' => 1,
            'latitude' => '53.21972994471751',
            'longitude' => '63.634321225543374',
        ]);

        City::create([
            'name' => 'Актау',
            'country_id' => 1,
            'latitude' => '43.65790932669384',
            'longitude' => '51.201223640440375',
        ]);

        City::create([
            'name' => 'Павлодар',
            'country_id' => 1,
            'latitude' => '52.29919102717299',
            'longitude' => '76.9868933820466',
        ]);

        City::create([
            'name' => 'Павлодар',
            'country_id' => 1,
            'latitude' => '',
            'longitude' => '',
        ]);

        City::create([
            'name' => 'Петропавл',
            'country_id' => 1,
            'latitude' => '54.882958740795296',
            'longitude' => '69.13881729137083',
        ]);

        City::create([
            'name' => 'Туркестан',
            'country_id' => 1,
            'latitude' => '43.30430106973821',
            'longitude' => '68.23363722961736',
        ]);

        City::create([
            'name' => 'Шымкент',
            'country_id' => 1,
            'latitude' => '42.333309',
            'longitude' => '69.621811',
        ]);
    }
}
