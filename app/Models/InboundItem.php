<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InboundItem extends Model
{
    protected $table = 'inbound_items';

    protected $fillable = [
        'inbound_id',
        'goods_id',
        'qty',
    ];

    public function inbound()
    {
        return $this->belongsTo(Inbound::class);
    }

    public function goods()
    {
        return $this->belongsTo(Goods::class);
    }
}
