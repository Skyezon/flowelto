<?php

use App\TransactionDetail;
use Illuminate\Database\Seeder;
use Seeds\SeederConfig;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            TransactionSeeder::class,
            TransactionDetailSeeder::class
        ]);

        $this->attachUserWithProduct();
    }

    /**
     * Menghasilkan data untuk tabel cart
     */
    private function attachUserWithProduct() {
        $product = App\Product::all(); // mengambil semua product yang sudah dibuat

        //untuk setiap akun user yang bukan merupakan manager, akan dimasukkan data cart
        //dari 1 - 6 jenis product yand dirandom dari database
        App\User::where('role', '<>', 'manager')->each(function($user) use($product){
            $numOfCartItem = rand(1,6);
            for ($i = 1; $i <= $numOfCartItem;) {
                $productId = rand(1, SeederConfig::$dataCount['product']);

                //jika product yang sama belum ada di dalam cart user, maka product tersebut akan dimasukkan ke database
                if($user->products()->where([
                    ['user_id', $user->id], ['product_id', $productId]
                ])->first() == null) {
                    $user->products()->attach($product[$productId-1], ['quantity' => rand(1,10)]);
                    $i++;
                }
            }
        });
    }
}
