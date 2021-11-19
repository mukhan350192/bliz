<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('equipment_category')->insertGetId([
            'name' => 'Подъемная техника',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('equipment_category')->insertGetId([
            'name' => 'Автомобили',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('equipment_category')->insertGetId([
            'name' => 'Дорожная техника',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('equipment_category')->insertGetId([
            'name' => 'Коммунальная техника',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('equipment_category')->insertGetId([
            'name' => 'Землеройная техника',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('equipment_category')->insertGetId([
            'name' => 'Строительное оборудование',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('equipment_category')->insertGetId([
            'name' => 'Бетонные работы',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('equipment_category')->insertGetId([
            'name' => 'Другое',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

    }
}
