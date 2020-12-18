<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Seeds\SeederConfig;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        for($i = 0; $i < SeederConfig::$dataCount['product']; $i++) {
            DB::table('products')->insert([
                'category_id' => rand(1, SeederConfig::$dataCount['category']),
                'name' => $faker->word(),
                'price' => $faker->randomNumber(4),
                'description' => $faker->paragraph(),
                'image' => '\storage\products\\'.$faker->image(SeederConfig::$storagePath.'\products', 640, 480, null, false)
            ]);
        }
    }
}
