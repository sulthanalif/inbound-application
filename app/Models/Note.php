<?php

namespace App\Models;

use App\LogActivity;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\CausesActivity;

class Note extends Model
{
    use LogActivity, CausesActivity;

    // Opsi log
    protected $logName = 'notes';

    // Atribut tambahan untuk di-ignore jika dibutuhkan
    // protected array $logAttributesToIgnore = ['password'];
    protected array $logAttributes = [
       'outbound_id',
        'inbound_id',
        'reject',
    ];

    protected $table = 'notes';

    protected $fillable = [
        'outbound_id',
        'inbound_id',
        'reject',
    ];
}
