<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ValidationStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = ['Sin documento', 'Enviando (sin revisar)', 'Aprobado', 'Rechazado'];
        foreach ($types as $index => $type) {
            DB::table('validation_states')->insert([
                'id' => $index + 1,
                'name' => $type
            ]);
        }
    }
}
