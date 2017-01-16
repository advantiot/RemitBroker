<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        //
        //Seed the users table
        //
        DB::table('users')->insert([
            'name' => str_random(16),
            'email' => str_random(8).'@gmail.com',
            'password' => bcrypt('secret'),
            'remitter_id' => '922196',
            'status' => 1
        ]);
    }
}
