<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EquipmentSubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    static $category_first = [
        'Автовышки',
        'Автокраны',
        'Башенные краны',
        'Гусеничные краны',
        'Козловые, мостовые краны',
        'Консольные краны',
        'Лебедки, тали',
        'Манипуляторы',
        'Перегружатели металлолома, леса Подъемники',
        'Погрузчики для склада',
        'Трубоукладчики',
        'Кабелеукладчики',
    ];

    static $category_second = [
        'Бензовозы',
        'Прицепы и платформы',
        'Рефрижераторы',
        'Самосвалы',
        'Седельные тягачи / Тралы',
        'Цементовозы',
        'Эвакуаторы',
    ];

    static $category_third = [
        'Автогудронаторы, гудронаторы Асфальтоукладчики',
        'Бордюроукладчики, уширители обочин',
        'Виброплиты, виброуплотнители',
        'Гидромолоты',
        'Грейдеры, автогрейдеры',
        'Грохоты',
        'Катки, виброкатки',
        'Дорожные фрезы',
        'Заливщики швов',
        'Кохеры',
        'Машины для укладки тротуарной плитки Машины дорожной разметки Распределители вяжущего',
        'Ресайклеры, рециклеры',
        'Швонарезчики, нарезчики швов Щебнераспределители',
    ];

    static $category_fourth = [
        'Ассенизатор, илосос',
        'Водовозы',
        'Шредеры, измельчители Каналопромывочные машины Комбинированные дорожные машины Компакторы, уплотнители отходов Мусоровозы',
        'Подметально-уборочные машины Поливомоечные машины Снегоуборщики, снегоуборочные машины',
        'Тракторы',
    ];

    static $category_fifth = [
        'Бульдозеры',
        'Бурильно-крановые машины, ямобуры',
        'Буровые установки',
        'Земснаряды, землесосные снаряды Погрузчики',
        'Копры, сваебойные установки',
        'Скреперы',
        'Траншеекопатели, баровые машины',
        'Установки ГНБ (горизонтального бурения)',
        'Экскаваторы',
    ];

    static $category_sixth = [
        'Бытовки, блок-контейнеры',
        'Генераторы Компрессоры',
        'Леса строительные',
        'Шлифовальные машины',
        'Контейнеры грузовые',
        'Мотопомпы',
        'Окрасочное оборудование',
        'Осветительные мачты',
        'Пескоструйное оборудование',
        'Плиткорезы',
        'Растворонасосы',
        'Сварочное оборудование',
        'Строительные ограждения',
        'Строительный мусоропровод',
        'Тепловые пушки, фены, осушители',
        'Штукатурные, шпаклевочные станции',
    ];

    static $category_seventh = [
        'Арматурные станки',
        'Автобетоносмесители, автомиксеры',
        'Бетононасосы, автобетононасосы',
        'Бетонораздаточные стрелы',
        'Бетоносмесительные установки',
        'Бетоноукладчики',
        'Вибраторы для бетона глубинные',
        'Виброрейки для бетона',
        'Затирочные машины по бетону',
        'Опалубка',
        'Парогенераторы, станции прогрева бетона',
        'Растворосмесители',
        'Торкрет-установки, шприц-машины',
    ];

    static $category_eighth = [
        'Выездной Шиномонтаж',
        'Дробилки, дробильные установки Мойки высокого давления, пароочистители',
        'Мульчеры лесные, измельчители пней',
        'Навесное оборудование',
        'Поломоечные машины',
        'Промышленные пылесосы',
        'Ричтраки, штабелёры',
        'Рубительные машины',
        'Тележки',
        'Техника для разрушения, демонтажа зданий',
        'Трелевочные тракторы, скиддеры',
    ];


    public function run()
    {
        //first
        foreach (self::$category_first as $c){
            DB::table('equipment_sub_category')->insert([
                'name' => $c,
                'category_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        //second
        foreach (self::$category_second as $c){
            DB::table('equipment_sub_category')->insert([
                'name' => $c,
                'category_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        //third
        foreach (self::$category_third as $c){
            DB::table('equipment_sub_category')->insert([
                'name' => $c,
                'category_id' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        //fourth
        foreach (self::$category_fourth as $c){
            DB::table('equipment_sub_category')->insert([
                'name' => $c,
                'category_id' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        //fifth
        foreach (self::$category_fifth as $c){
            DB::table('equipment_sub_category')->insert([
                'name' => $c,
                'category_id' => 5,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        //sixth
        foreach (self::$category_sixth as $c){
            DB::table('equipment_sub_category')->insert([
                'name' => $c,
                'category_id' => 6,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        //seventh
        foreach (self::$category_seventh as $c){
            DB::table('equipment_sub_category')->insert([
                'name' => $c,
                'category_id' => 7,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        //eighth
        foreach (self::$category_eighth as $c){
            DB::table('equipment_sub_category')->insert([
                'name' => $c,
                'category_id' => 8,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
