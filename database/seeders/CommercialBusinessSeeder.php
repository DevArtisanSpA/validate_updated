<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommercialBusinessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $businesses = [
            'Sin datos',
            'Industriales',
            'Comerciales',
            'Servicios',
            'Mineria',
            'Vienes raices',
            'Contruccion',
            'Metalurgia',
            'Telecomunicaciones',
            'Seguridad',
            'Electricidad',
            'Trasporte',
        ];
        foreach ($businesses as $index => $business) {
            DB::table('commercial_businesses')->insert([
                'id' => $index + 1,
                'name' => $business,
            ]);
        }
    }
}
