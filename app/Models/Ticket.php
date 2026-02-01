<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
     use HasFactory;
    protected $table = 'tickets';

    protected $fillable = [
        'description',
        'reference_number',
        'status',
        'priority',
        'customer_id',
    ];
    

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            if (empty($ticket->reference_number)) {
                $ticket->reference_number = self::generateUniqueReference();
            }  
           
        });
    }

    
    public static function generateUniqueReference()
    {
        do {
            $reference = 'TKT-' . strtoupper(Str::random(10));
        } while (self::where('reference_number', $reference)->exists());

        return $reference;
    }

   
    public function replies()
    {
        return $this->hasMany(TicketHistory::class)->orderBy('created_at', 'asc');
    }

 
    public function latestReply()
    {
        return $this->hasOne(TicketHistory::class)->latest();
    }

   
    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }


    public function updateStatus($status)
    {
        $validStatuses = ['new', 'in_progress', 'resolved', 'closed'];
        if (in_array($status, $validStatuses)) {
            $this->update(['status' => $status]);
        }
    }

     public function getStatusColorAttribute()
    {
        $colors = [
            'new' => 'blue',
            'in_progress' => 'yellow',
            'resolved' => 'green',
            'closed' => 'gray'
        ];
        return $colors[$this->status] ?? 'gray';
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

}
