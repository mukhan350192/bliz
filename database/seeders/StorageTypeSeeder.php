<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StorageTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    static $type = [
        'Сухой склад (+14/+24)',
        'Холодильный склад (+2/+7)',
        'Морозильный склад (-18/-24)',
        'Мультитемпературный склад (+14/-24)',
        'Производственное помещение',
        'Земельный участок',
        'Неотапливаемый склад'
    ];
    public function run()
    {
        foreach (self::$type as $t){
            DB::table('storage_type')->insert([
                'name' => $t,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
