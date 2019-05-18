<?php

use Illuminate\Database\Seeder;

use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run App\User entity seeder
     *
     * @return void
     */
    public function run()
    {
        factory(User::class, 2)->create();
    }
}
