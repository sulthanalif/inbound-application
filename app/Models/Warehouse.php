<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $table = 'warehouses';

    protected $fillable = [
        'code',
        'name',
    ];

    public function goods()
    {
        return $this->hasMany(Goods::class);
    }
}