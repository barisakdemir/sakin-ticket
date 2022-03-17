<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\TicketMessage::insert([
            'ticket_id' => 1,
            'user_id' => 4,
            'message' => 'Test Ticket Message',
        ]);
    }
}
