<?php

namespace App\Models;

use App\LogActivity;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\CausesActivity;

class Vendor extends Model
{
    use LogActivity, CausesActivity;

    // Opsi log
    protected $logName = 'master_vendors';

    // Atribut tambahan untuk di-ignore jika dibutuhkan
    // protected array $logAttributesToIgnore = ['password'];
    protected array $logAttributes = [
       'name',
        'email',
        'phone',
        'address',
    ];

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

    public function goods()
    {
        return $this->hasMany(Goods::class);
    }
}
