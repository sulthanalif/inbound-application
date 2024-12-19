<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryArea extends Model
{
    protected $table = 'delivery_areas';

    protected $fillable = [
        'code',
        'name',
        'address',
    ];

    public function outbounds()
    {
        return $this->hasMany(Outbound::class);
    }

}
