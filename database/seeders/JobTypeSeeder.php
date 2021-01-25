<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Seeder;

class JobTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            'Auxiliar de Aseo',
            'Auxiliar de Cocina',
            'Conductor ',
            'Escolta',
            'Guardia',
            'Ingeniería y Montaje',
            'Maestro Encargado',
            'Mantención',
            'Montaje',
            'Promotores',
            'Secretarias',
            'Servicios Generales',
            'Electricista',
            'Intervencionista',
            'Operario',
            'Otro'
        ];
        foreach ($types as $index =>$type) {
            DB::table('job_types')->updateOrInsert([
                'id' => $index + 1,
                'name' => $type,
            ]);
        }
    }
}
