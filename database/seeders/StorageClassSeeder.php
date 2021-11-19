<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StorageClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    static $class = [
        'A',
        'A+',
        'B',
        'C',
        'D'
    ];


    public function run()
    {
        foreach (self::$class as $c) {
            DB::table('storage_class')->insert([
                'name' => $c,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
