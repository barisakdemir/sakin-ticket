<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketAnsweringAgent extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'user_id'
    ];
}
