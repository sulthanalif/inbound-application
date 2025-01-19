<?php

namespace App\Models;

use App\LogActivity;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\CausesActivity;

class InboundItem extends Model
{
    use LogActivity, CausesActivity;

    // Opsi log
    protected $logName = 'inbound_items';

    // Atribut tambahan untuk di-ignore jika dibutuhkan
    // protected array $logAttributesToIgnore = ['password'];
    protected array $logAttributes = [
        'inbound_id',
        'goods_id',
        'qty',
    ];

    protected $table = 'inbound_items';

    protected $fillable = [
        'inbound_id',
        'goods_id',
        'qty',
    ];

    public function inbound()
    {
        return $this->belongsTo(Inbound::class);
    }

    public function goods()
    {
        return $this->belongsTo(Goods::class);
    }
}
