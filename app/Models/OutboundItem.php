<?php

namespace App\Models;

use App\LogActivity;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\CausesActivity;

class OutboundItem extends Model
{
    use LogActivity, CausesActivity;

    // Opsi log
    protected $logName = 'outbound_items';

    // Atribut tambahan untuk di-ignore jika dibutuhkan
    // protected array $logAttributesToIgnore = ['password'];
    protected array $logAttributes = [
        'outbound_id',
        'goods_id',
        'qty',
        'sub_price',
    ];

    protected $table = 'outbound_items';

    protected $fillable = [
        'outbound_id',
        'goods_id',
        'qty',
        'sub_price',
    ];

    public function outbound()
    {
        return $this->belongsTo(Outbound::class);
    }

    public function goods()
    {
        return $this->belongsTo(Goods::class);
    }
}
