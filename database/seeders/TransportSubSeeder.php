<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransportSubSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */


    public function run()
    {
        DB::table('type_sub_transport')->insertGetId([
            'name' => 'Тент',
            'category_id' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('type_sub_transport')->insertGetId([
            'name' => 'Изотерм',
            'category_id' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('type_sub_transport')->insertGetId([
            'name' => 'Цельномет.',
            'category_id' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('type_sub_transport')->insertGetId([
            'name' => 'Рефрижератор',
            'category_id' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('type_sub_transport')->insertGetId([
            'name' => 'Автобус грузопас.',
            'category_id' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('type_sub_transport')->insertGetId([
            'name' => 'Автобус люкс',
            'category_id' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('type_sub_transport')->insertGetId([
            'name' => 'Автовоз',
            'category_id' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('type_sub_transport')->insertGetId([
            'name' => 'Бензовоз',
            'category_id' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('type_sub_transport')->insertGetId([
            'name' => 'Контейнеровоз',
            'category_id' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('type_sub_transport')->insertGetId([
            'name' => 'Открытая',
            'category_id' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('type_sub_transport')->insertGetId([
            'name' => 'Самосвал',
            'category_id' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('type_sub_transport')->insertGetId([
            'name' => 'Трал',
            'category_id' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('type_sub_transport')->insertGetId([
            'name' => 'Крытый вагон',
            'category_id' => 2,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('type_sub_transport')->insertGetId([
            'name' => 'Полувагон',
            'category_id' => 2,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('type_sub_transport')->insertGetId([
            'name' => 'Платформа',
            'category_id' => 2,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('type_sub_transport')->insertGetId([
            'name' => 'Цистерна',
            'category_id' => 2,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('type_sub_transport')->insertGetId([
            'name' => 'Рефрижератор',
            'category_id' => 2,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('type_sub_transport')->insertGetId([
            'name' => 'Хоппер',
            'category_id' => 2,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('type_sub_transport')->insertGetId([
            'name' => 'Думпкар',
            'category_id' => 2,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('type_sub_transport')->insertGetId([
            'name' => 'Фитинговая платформа',
            'category_id' => 2,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('type_sub_transport')->insertGetId([
            'name' => 'Вагон бункерного типа',
            'category_id' => 2,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('type_sub_transport')->insertGetId([
            'name' => 'Транспортер',
            'category_id' => 2,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('type_sub_transport')->insertGetId([
            'name' => 'Автомобилевоз',
            'category_id' => 2,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('type_sub_transport')->insertGetId([
            'name' => 'Вагон-кенгуру',
            'category_id' => 2,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('type_sub_transport')->insertGetId([
            'name' => 'Вагон-изотермический',
            'category_id' => 2,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('type_sub_transport')->insertGetId([
            'name' => 'Вагон-Ледник',
            'category_id' => 2,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('type_sub_transport')->insertGetId([
            'name' => 'Вагон-Термос',
            'category_id' => 2,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('type_sub_transport')->insertGetId([
            'name' => 'ЦМГВ',
            'category_id' => 2,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('type_sub_transport')->insertGetId([
            'name' => 'Простые перевозки',
            'category_id' => 3,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('type_sub_transport')->insertGetId([
            'name' => 'Попутные перевозки',
            'category_id' => 3,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('type_sub_transport')->insertGetId([
            'name' => 'Челночные перевозки',
            'category_id' => 3,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('type_sub_transport')->insertGetId([
            'name' => 'Сборные перевозки',
            'category_id' => 3,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);


        DB::table('type_sub_transport')->insertGetId([
            'name' => 'Контейнерные перевозки',
            'category_id' => 4,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('type_sub_transport')->insertGetId([
            'name' => 'Морской фрахт',
            'category_id' => 4,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('type_sub_transport')->insertGetId([
            'name' => 'Навалочные грузы',
            'category_id' => 4,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('type_sub_transport')->insertGetId([
            'name' => 'Ро-ро перевозки',
            'category_id' => 4,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('type_sub_transport')->insertGetId([
            'name' => 'Перевозки «дверь-дверь»',
            'category_id' => 4,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);




    }
}
