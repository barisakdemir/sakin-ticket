<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Department::insert([
            'name' => 'Sales'
        ]);
        \App\Models\Department::insert([
            'name' => 'Support'
        ]);
    }
}
