<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table = 'areas';

    protected $fillable = [
        'code',
        'name',
        'container',
        'rack',
        'number',
        'warehouse_id',
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function goods()
    {
        return $this->hasMany(Goods::class);
    }

    public function outbound()
    {
        return $this->hasMany(Outbound::class);
    }
}
