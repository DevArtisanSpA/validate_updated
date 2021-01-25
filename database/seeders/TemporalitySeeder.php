<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TemporalitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $temporalities = ["Base", "Mensual"];
        foreach($temporalities as $index => $temporality) {
            DB::table('temporalities')->updateOrInsert([
                'id' => $index + 1,
                'name' => $temporality
            ]);
        }
    }
}
