<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Container extends Model
{
    protected $table = 'containers';

    protected $fillable = [
        'code',
        'name',
        'description',
        'warehouse_id',
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function areas()
    {
        return $this->hasMany(Area::class);
    }
}
