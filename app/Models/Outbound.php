<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Outbound extends Model
{
    protected $table = 'outbounds';

    protected $fillable = [
        'user_id',
        'vendor_id',
        'date',
        'status',
        'total_price',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function items()
    {
        return $this->hasMany(OutboundItem::class);
    }
}
