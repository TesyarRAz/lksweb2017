<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
        	[
        		'username' => 'john.doe',
        		'password' => bcrypt('12345'),
        		'first_name' => 'John',
        		'last_name' => 'Doe'
        	],
        	[
        		'username' => 'richard.roe',
        		'password' => bcrypt('12345'),
        		'first_name' => 'Richard',
        		'last_name' => 'Roe'
        	],
        	[
        		'username' => 'jane.poe',
        		'password' => bcrypt('12345'),
        		'first_name' => 'Jane',
        		'last_name' => 'Poe'
        	],
        ]);
    }
}
