<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        $this->call([
            AdminSeed::class,
            CategorySeed::class,
            BrandSeed::class,
            ColorSeed::class,
            ProductSeed::class,
            CouponSeed::class,
            UserSeed::class,
            FavoriteSeed::class,
            ImageSeed::class,
            SlideSeed::class,
            PostSeed::class,
            TagSeed::class,
            ProductTagSeed::class,
            CartSeed::class,
            DetailCartSeed::class,
            OrderSeed::class,
            DetailOrderSeed::class,
            BillSeed::class,
        ]);
    }
}
