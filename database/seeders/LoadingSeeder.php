<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoadingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    static $loading = [
        'Задняя',
        'Боковая',
        'Верхная',
        'Растентовка',
        'Обрешетка',
        'Пневмоход',
        'Гидроборт',
        'Пирамида',
        'Пломба',
        'Любая',
    ];
    public function run()
    {
        foreach (self::$loading as $c){
            DB::table('post_loading')->insert([
                'name' => $c,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}

