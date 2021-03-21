<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    static $countries = [
        'Казахстан',
        'Россия',
        'Украина',
        'Армения',
        'Азербайджан',
        'Беларусь',
        'Кыргызстан',
        'Узбекистан'
    ];

    public function run()
    {
        Country::create([
            'name' => 'Казахстан',
            'short_code' => 'KZ',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        Country::create([
            'name' => 'Россия',
            'short_code' => 'RU',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        Country::create([
            'name' => 'Украина',
            'short_code' => 'UA',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        Country::create([
            'name' => 'Армения',
            'short_code' => 'AM',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        Country::create([
            'name' => 'Азербайджан',
            'short_code' => 'AZ',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        Country::create([
            'name' => 'Беларусь',
            'short_code' => 'BY',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        Country::create([
            'name' => 'Кыргызстан',
            'short_code' => 'KG',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        Country::create([
            'name' => 'Узбекистан',
            'short_code' => 'UZ',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
