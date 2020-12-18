<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Seeds\SeederConfig;

class TransactionDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 0; $i < SeederConfig::$dataCount['transaction_detail']; $i++) {
            DB::table('transaction_details')->insert([
                'transaction_id' => rand(1, SeederConfig::$dataCount['transaction']),
                'product_id' => rand(1, SeederConfig::$dataCount['product']),
                'quantity' => rand(1, 10)
            ]);
        }
    }
}
