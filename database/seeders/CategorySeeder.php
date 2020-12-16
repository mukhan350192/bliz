<?php

namespace Database\Seeders;

use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        Category::create([
            'name' => 'Грузы',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        Category::create([
            'name' => 'Транспорт',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        Category::create([
            'name' => 'Аукцион',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        Category::create([
            'name' => 'Расчет растояний',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        Category::create([
            'name' => 'Проверка компаний',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);


    }
}
