<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = ["Fijo", "Eventual", "Mantenimiento"];
        foreach($types as $index => $type) {
            DB::table('service_types')->updateOrInsert([
                'id' => $index + 1,
                'name' => $type
            ]);
        }
    }
}
