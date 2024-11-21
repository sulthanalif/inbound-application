<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table = 'vendors';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
    ];

    public function inbounds()
    {
        return $this->hasMany(Inbound::class);
    }

    public function outbounds()
    {
        return $this->hasMany(Outbound::class);
    }
}
