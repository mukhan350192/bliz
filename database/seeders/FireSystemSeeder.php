<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FireSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    static $addition = [
        'Спринклерная система автоматического пожаротушения',
        'Дренчерная система автоматического пожаротушения',
        'Внешние гидранты и внутренние пожарные краны',
        'Порошковая система автоматического пожаротушения'
    ];
    public function run()
    {
        foreach (self::$addition as $c){
            DB::table('storage_fire_system')->insert([
                'name' => $c,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
