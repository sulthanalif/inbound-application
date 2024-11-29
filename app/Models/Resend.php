<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resend extends Model
{
    protected $table = 'resends';

    protected $fillable = [
        'code',
        'date',
        'user_id',
        'inbound_id',
        'sender_name',
        'vehicle_number',
        'project_id',
        'area_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function inbound()
    {
        return $this->belongsTo(Inbound::class);
    }
}
