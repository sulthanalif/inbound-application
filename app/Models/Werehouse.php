<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Werehouse extends Model
{
    protected $table = 'werehouses';

    protected $fillable = [
        'code',
        'name',
    ];

    public function goods()
    {
        return $this->hasMany(Goods::class);
    }
}
