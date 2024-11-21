<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutboundItem extends Model
{
    protected $table = 'outbound_items';

    protected $fillable = [
        'outbound_id',
        'goods_id',
        'qty',
        'sub_price',
    ];

    public function outbound()
    {
        return $this->belongsTo(Outbound::class);
    }

    public function goods()
    {
        return $this->belongsTo(Goods::class);
    }
}
