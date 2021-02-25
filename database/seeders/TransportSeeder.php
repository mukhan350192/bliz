<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\SubCategory;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    static $transport = [
        'Ж/Д Рефрижератор',
        'Авто Тент',
        'Авто Другое'
    ];
    public function run()
    {
        foreach (self::$transport as $t){
            DB::table('type_transport')->insertGetId([
                'name' => $t,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
