<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketHistory extends Model
{
    protected $table = 'ticket_histories';

    protected $fillable = [
        'ticket_id',
        'agent_id',
        'customer_id',
        'message'
    ];

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

      public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }


}
