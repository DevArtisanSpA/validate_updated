<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('users')->insert([
        'name' => 'admin',
        'email' => 'admin@admin.cl',
        'user_type_id' => 1,
        'email_verified_at' => now(),
        'password' => bcrypt('123456789'),
        'remember_token' => Str::random(10),
      ]);
    }
}
