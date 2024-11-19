<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inbound extends Model
{
    protected $table = 'inbounds';

    protected $fillable = [
        'goods_id',
        'vendor_id',
        'vehicle_number',
        'qty',
        'status',
        'description',
    ];

    public function goods()
    {
        return $this->belongsTo(Goods::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
