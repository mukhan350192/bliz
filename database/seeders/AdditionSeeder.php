<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    static $addition = [
        'Без догруза (отдельный транспорт)',
        'Консолидация (сборный груз)',
        'Срочно'
    ];
    public function run()
    {
        foreach (self::$addition as $c){
            DB::table('post_addition')->insert([
                'name' => $c,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
