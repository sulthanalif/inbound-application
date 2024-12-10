<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table = 'areas';

    protected $fillable = [
        'code',
        'name',
        'description',
        'container_id',
    ];

    public function container()
    {
        return $this->belongsTo(Container::class);
    }

    public function outbound()
    {
        return $this->hasMany(Outbound::class);
    }
}
