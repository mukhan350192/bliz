<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(AdditionSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(CitiesSeeder::class);
        $this->call(CompanySeeder::class);
        $this->call(ConditionSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(CurrencySeeder::class);
        $this->call(DocumentSeeder::class);
        $this->call(EquipmentSeeder::class);
        $this->call(LoadingSeeder::class);
        $this->call(OrderSeeder::class);
        $this->call(PaymentSeeder::class);
        $this->call(PostStatus::class);
        $this->call(RentSeeder::class);
        $this->call(SubCategorySeeder::class);
        $this->call(FireSystemSeeder::class);
        $this->call(VentilationSeeder::class);
        $this->call(EquipmentRentSeeder::class);
        $this->call(TransportSeeder::class);
        $this->call(TypeEquipmentSeeder::class);
        //$this->call(SubCategorySeeder::class);
    }
}
