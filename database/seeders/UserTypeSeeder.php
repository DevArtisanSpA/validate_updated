<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = ['Administrador', 'Usuario'];
        foreach ($types as $index => $type) {
            DB::table('user_types')->insert([
                'id' => $index + 1,
                'name' => $type
            ]);
        }
    }
}
