<?php

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
        $this->addManager();

        factory(App\User::class, SeederConfig::$dataCount['user'])->create();
        factory(App\Category::class, SeederConfig::$dataCount['category'])->create();
        factory(App\Product::class, SeederConfig::$dataCount['product'])->create();
        factory(App\Transaction::class, SeederConfig::$dataCount['transaction'])->create();
        factory(App\TransactionDetail::class, SeederConfig::$dataCount['transaction_detail'])->create();

        $this->attachUserWithProduct();
    }

    private function addManager($email = 'manager@mail.com', $password = '123qwe') {
        factory(App\User::class)->create([
            'username' => 'Manager'.rand(1, 10),
            'email' => $email,
            'password' => bcrypt($password),
            'role' => 'manager'
        ]);
    }

    private function attachUserWithProduct() {
        $product = App\Product::all();

        App\User::where('role', '<>', 'manager')->each(function($user) use($product){
            $numOfCartItem = rand(1,6);
            for ($i = 1; $i <= $numOfCartItem;) { 
                $productId = rand(1, SeederConfig::$dataCount['product']);

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
