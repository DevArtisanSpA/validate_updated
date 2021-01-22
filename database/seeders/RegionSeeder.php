<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $regions = [
            [15, 'Arica y Parinacota', 'XV'],
            [1, 'Tarapacá', 'I'],
            [2, 'Antofagasta', 'II'],
            [3, 'Atacama', 'III'],
            [4, 'Coquimbo', 'IV'],
            [5, 'Valparaiso', 'V'],
            [13, 'Metropolitana de Santiago', 'RM'],
            [6, 'Libertador General Bernardo O\'Higgins', 'VI'],
            [7, 'Maule', 'VII'],
            [16, 'Ñuble', 'XVI'],
            [8, 'Biobío', 'VIII'],
            [9, 'La Araucanía', 'IX'],
            [14, 'Los Ríos', 'XIV'],
            [10, 'Los Lagos', 'X'],
            [11, 'Aisén del General Carlos Ibáñez del Campo', 'XI'],
            [12, 'Magallanes y de la Antártica Chilena', 'XII']
        ];
        foreach ($regions as $region) {
            DB::table('regions')->updateOrInsert([
                'id' => $region[0],
                'name' => $region[1],
                'number_region' => $region[2]
            ]);
        }
    }
}
