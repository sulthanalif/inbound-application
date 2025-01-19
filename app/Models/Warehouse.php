<?php

namespace App\Models;

use App\LogActivity;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\CausesActivity;

class Warehouse extends Model
{
    use LogActivity, CausesActivity;

    // Opsi log
    protected $logName = 'master_warehouses';

    // Atribut tambahan untuk di-ignore jika dibutuhkan
    // protected array $logAttributesToIgnore = ['password'];
    protected array $logAttributes = [
       'code',
        'name',
        'address',
    ];

    protected $table = 'warehouses';

    protected $fillable = [
        'code',
        'name',
        'address',
    ];

    public function users()
    {
        return $this->hasMany(UserWarehouse::class);
    }


    public function areas()
    {
        return $this->hasMany(Area::class);
    }

    // public function goods()
    // {
    //     return $this->hasMany(Goods::class);
    // }
}
