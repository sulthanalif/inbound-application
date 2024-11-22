<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = [
        'name',
        'symbol',
        'description',
    ];

    public function goods()
    {
        return $this->hasMany(Goods::class);
    }
}
