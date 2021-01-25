<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->truncateTablas();

        $this->call(UserTypeSeeder::class);
        $this->call(RegionSeeder::class);
        $this->call(CommuneSeeder::class);
        $this->call(AreaSeeder::class);
        $this->call(TemporalitySeeder::class);
        $this->call(JobTypeSeeder::class);
        $this->call(ServiceTypeSeeder::class);
        $this->call(DocumentTypeSeeder::class);
        $this->call(CommercialBusinessSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ValidationStateSeeder::class);
    }

    protected function truncateTablas()
    {
        $tables = [
            'areas',
            'branch_offices',
            'commercial_businesses',
            'communes',
            'companies',
            'document_types',
            'documents',
            'employees',
            'job_types',
            'regions',
            'service_types',
            'services',
            'temporalities',
            'user_types',
            'users',
            'validation_states'
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
