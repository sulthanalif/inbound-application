<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    protected $table = 'goods';

    protected $fillable = [
        'name',
        'sk',
        'length',
        'width',
        'height',
        'weight',
        'description',
        'condition',
        'price',
        'qty',
        'category_id',
        'warehouse_id',
        'user_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function inbounds()
    {
        return $this->hasMany(Inbound::class);
    }

    public function outboundItems()
    {
        return $this->hasMany(OutboundItem::class);
    }
}
