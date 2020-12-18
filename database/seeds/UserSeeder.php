<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Seeds\SeederConfig;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $gender = ['male', 'female'];

        $this->addManager();

        for($i = 0; $i < SeederConfig::$dataCount['user']; $i++) {
            DB::table('users')->insert([
                'username' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => bcrypt('123qwe'),
                'address' => $faker->address,
                'gender' => $gender[rand(0,1)],
                'dob' => $faker->date(),
                'role' => 'user'
            ]);
        }
    }

    private function addManager($email = 'manager@mail.com', $password = 'password') {
        $faker = Faker::create();

        DB::table('users')->insert([
            'username' => 'Manager'.rand(1, 10),
            'email' => $email,
            'password' => bcrypt($password),
            'address' => $faker->streetAddress,
            'gender' => 'male',
            'dob' => $faker->date(),
            'role' => 'manager'
        ]);
    }
}
