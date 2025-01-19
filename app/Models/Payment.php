<?php

namespace App\Models;

use App\LogActivity;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\CausesActivity;

class Payment extends Model
{
    use LogActivity, CausesActivity;

    // Opsi log
    protected $logName = 'payments';

    // Atribut tambahan untuk di-ignore jika dibutuhkan
    // protected array $logAttributesToIgnore = ['password'];
    protected array $logAttributes = [
        'outbound_id',
        'inbound_id',
        'code_payment',
        'date',
        // 'total_payment',
        'paid',
        'remaining',
        'payment_method',
        'bank',
        'image',
    ];

    protected $table = 'payments';

    protected $fillable = [
        'outbound_id',
        'inbound_id',
        'code_payment',
        'date',
        // 'total_payment',
        'paid',
        'remaining',
        'payment_method',
        'bank',
        'image',
    ];

    public function outbound()
    {
        return $this->belongsTo(Outbound::class);
    }

    public function inbound()
    {
        return $this->belongsTo(Inbound::class);
    }
}
