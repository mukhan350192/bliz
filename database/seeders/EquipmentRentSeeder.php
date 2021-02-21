<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EquipmentRentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    static $documents = [
        'за 1 час',
        'за км'
    ];
    public function run()
    {
        foreach (self::$documents as $c){
            DB::table('equipment_rent')->insert([
                'name' => $c,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
