<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table = 'areas';

    protected $fillable = [
        'code',
        'name',
        'address'
    ];

    public function outbound()
    {
        return $this->hasMany(Outbound::class);
    }
}
