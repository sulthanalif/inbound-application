<?php

namespace App\Models;

use App\LogActivity;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\CausesActivity;

class DeliveryArea extends Model
{
    use LogActivity, CausesActivity;

    // Opsi log
    protected $logName = 'master_delivery_areas';

    // Atribut tambahan untuk di-ignore jika dibutuhkan
    // protected array $logAttributesToIgnore = ['password'];
    protected array $logAttributes = [
        'code',
        'name',
        'address',
    ];

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
