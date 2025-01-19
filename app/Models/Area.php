<?php

namespace App\Models;

use App\LogActivity;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\CausesActivity;

class Area extends Model
{
    use LogActivity, CausesActivity;

    // Opsi log
    protected $logName = 'master_areas';

    // Atribut tambahan untuk di-ignore jika dibutuhkan
    // protected array $logAttributesToIgnore = ['password'];
    protected array $logAttributes = [
        'code',
        'name',
        'container',
        'rack',
        'number',
    ];

    protected $table = 'areas';

    protected $fillable = [
        'code',
        'name',
        'container',
        'rack',
        'number',
        'warehouse_id',
    ];


    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function goods()
    {
        return $this->hasMany(Goods::class);
    }

    public function outbound()
    {
        return $this->hasMany(Outbound::class);
    }

    public function inbound()
    {
        return $this->hasMany(Inbound::class);
    }
}
