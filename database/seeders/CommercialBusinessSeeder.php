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
            'Minería',
            'Bienes raíces',
            'Construcción',
            'Metalurgia',
            'Telecomunicaciones',
            'Seguridad',
            'Electricidad',
            'Trasporte',
        ];
        foreach ($businesses as $index => $business) {
            try {
                DB::table('commercial_businesses')->updateOrInsert([
                    'id' => $index + 1,
                    'name' => $business,
                ]);
            } catch (\Throwable $th) {
                DB::table('commercial_businesses')->where('id', $index + 1)
                    ->update(['name' => $business]);
            }
        }
    }
}
