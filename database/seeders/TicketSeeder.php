<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Ticket::insert([
            'user_id' => 4,
            'department_id' => 1,
            'status' => 'active',
            'importance' => 1,
            'title' => 'Test Ticket Title',
        ]);
    }
}
