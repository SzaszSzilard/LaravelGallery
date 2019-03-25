<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      for ($i = 0; $i < 3; $i++ ) {
        DB::table('users')->insert([
          'username' => Str::random(10),
          'firstname' => Str::random(10),
          'lastname' => Str::random(10),
          'telephone' => Str::random(10),
          'email' => Str::random(10).'@gmail.com',
          'password' => bcrypt('secret'),
        ]);
      }
    }
}
