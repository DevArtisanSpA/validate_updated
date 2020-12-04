<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $areas = ["Empleado", "Empresa"];
        foreach($areas as $index => $area) {
            DB::table('areas')->insert([
                'id' => $index + 1,
                'name' => $area
            ]);
        }
    }
}
