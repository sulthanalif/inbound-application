<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProblemItem extends Model
{
    protected $table = 'problem_items';

    protected $fillable = [
        'outbound_id',
        'goods_id',
        'qty',
        'worthy',
    ];

    public function goods()
    {
        return $this->belongsTo(Goods::class);
    }

    public function outbound()
    {
        return $this->belongsTo(Outbound::class);
    }
}
