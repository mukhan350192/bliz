<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VentilationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    static $addition = [
        'Естественная',
        'Приточно-вытяжная',
        'Приточно-вытяжная с системой кондиционирования воздуха',
        'Принудительный приток, естественная вытяжка',
        'Принудительная вытяжка, естественный приток'
    ];
    public function run()
    {
        foreach (self::$addition as $c){
            DB::table('storage_ventilation')->insert([
                'name' => $c,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
