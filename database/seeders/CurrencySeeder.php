<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    static $currency = [
            'тенге',
            'рубль',
            'гривен',
            'доллар',
            'евро',
    ];
    public function run()
    {
        foreach (self::$currency as $c){
            DB::table('currency')->insert([
                    'name' => $c,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
            ]);
        }
    }
}
