<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentAgentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\DepartmentAgent::insert([
            'department_id' => 1,
            'user_id' => 2,
        ]);

        \App\Models\DepartmentAgent::insert([
            'department_id' => 1,
            'user_id' => 3,
        ]);
    }
}
