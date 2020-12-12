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
        'Армения',
        'Беларусь',
        'Кыргызстан',
        'Узбекистан'
    ];
    public function run()
    {
        foreach (self::$countries as $country){
            Country::create([
                'name' => $country,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
