<?php

namespace App\Models;

use App\LogActivity;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\CausesActivity;

class Unit extends Model
{
    use LogActivity, CausesActivity;

    // Opsi log
    protected $logName = 'master_units';

    // Atribut tambahan untuk di-ignore jika dibutuhkan
    // protected array $logAttributesToIgnore = ['password'];
    protected array $logAttributes = [
       'name',
        'symbol',
        'description',
    ];

    protected $fillable = [
        'name',
        'symbol',
        'description',
    ];

    public function goods()
    {
        return $this->hasMany(Goods::class);
    }
}
