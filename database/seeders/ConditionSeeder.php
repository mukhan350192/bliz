<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    static $condition = [
        'Рога',
        'Съемн. Стойки',
        'Жесткий борт',
        'Деревянный пол',
        'Jumbo',
        'Мега',
    ];
    public function run()
    {
        foreach (self::$condition as $c){
            DB::table('post_condition')->insert([
                'name' => $c,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
