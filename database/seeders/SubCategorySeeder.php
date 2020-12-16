<?php

namespace Database\Seeders;

use App\Models\SubCategory;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        SubCategory::create([
            'name' => 'Авто',
            'category_id' => 1,
            'filter' => true,
        ]);
        SubCategory::create([
            'name' => 'Авиа',
            'category_id' => 1,
            'filter' => true,
        ]);
        SubCategory::create([
            'name' => 'ЖД',
            'category_id' => 1,
            'filter' => true,
        ]);
        SubCategory::create([
            'name' => 'Морской',
            'category_id' => 1,
            'filter' => true,
        ]);
        SubCategory::create([
            'name' => 'Мультимодальный',
            'category_id' => 1,
            'filter' => true,
        ]);


    }
}
