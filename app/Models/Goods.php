<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    protected $table = 'goods';

    protected $fillable = [
        'name',
        'code',
        'length',
        'width',
        'height',
        'weight',
        'description',
        'condition',
        'price',
        'qty',
        'type',
        'minimum_order',
        'unit_time',
        'capital',
        'unit_id',
        'vendor_id',
        'category_id',
        'warehouse_id',
        'user_id',
    ];

    public function problemItems()
    {
        return $this->hasMany(ProblemItem::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

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

    public function inbounditems()
    {
        return $this->hasMany(InboundItem::class);
    }

    public function outboundItems()
    {
        return $this->hasMany(OutboundItem::class);
    }
}
