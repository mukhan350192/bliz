<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    static $documents = [
        'CMR',
        'TIR',
        'T1',
        'T2',
        'EKMT',
        'Санпаспорт',
        'Санкнижка',
        'Тамож. свидельство',
        'Тамож. контроль',
    ];
    public function run()
    {
        foreach (self::$documents as $c){
            DB::table('post_document')->insert([
                'name' => $c,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
