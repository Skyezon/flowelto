<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Seeds\SeederConfig;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        for($i = 0; $i < SeederConfig::$dataCount['category']; $i++) {
            DB::table('categories')->insert([
                'name' => $faker->word(),
                'image' => '\storage\categories\\'.$faker->image(SeederConfig::$storagePath.'\categories', 640, 480, null, false)
            ]);
        }
    }
}
