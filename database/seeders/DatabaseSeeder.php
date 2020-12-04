<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserTypeSeeder::class);
        $this->call(RegionSeeder::class);
        $this->call(CommuneSeeder::class);
        $this->call(AreaSeeder::class);
        $this->call(TemporalitySeeder::class);
        $this->call(DocumentTypeSeeder::class);
        $this->call(CommercialBusinessSeeder::class);
        $this->call(JobTypeSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ValidationStateSeeder::class);
        $this->call(ServiceTypeSeeder::class);
    }
}
