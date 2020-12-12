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

    }
}
