<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Seeds\SeederConfig;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        for($i = 0; $i < SeederConfig::$dataCount['transaction']; $i++) {
            DB::table('transactions')->insert([
                'user_id' => rand(2, SeederConfig::$dataCount['user'] + 1),
                'date' => $faker->date()
            ]);
        }
    }
}
