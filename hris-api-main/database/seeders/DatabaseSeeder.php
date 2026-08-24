<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // 1. Seed permissions and assign to roles
            PermissionSeeder::class,
            RolePermissionSeeder::class,

            // 2. Seed specific users (Manager, HR, Finance, Employee)
            ManagerSeeder::class,
            EmployeeSeeder::class,
            HrSeeder::class,
            FinanceSeeder::class,

            // 3. Seed teams
            TeamSeeder::class,

            // 4. Seed attendance sample data
            AttendanceSeeder::class,
        ]);
    }
}
