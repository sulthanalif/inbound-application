<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';

    protected $fillable = [
        'outbound_id',
        'inbound_id',
        'code_payment',
        'date',
        // 'total_payment',
        'paid',
        'remaining',
        'payment_method',
        'bank',
        'image',
    ];

    public function outbound()
    {
        return $this->belongsTo(Outbound::class);
    }

    public function inbound()
    {
        return $this->belongsTo(Inbound::class);
    }
}
