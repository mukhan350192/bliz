<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\SubCategory;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeEquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    static $transport = [
        'Бульдозеры',
    ];
    public function run()
    {
        foreach (self::$transport as $t){
            DB::table('type_equipment')->insertGetId([
                'name' => $t,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
