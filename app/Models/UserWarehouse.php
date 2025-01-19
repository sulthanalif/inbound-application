<?php

namespace App\Models;

// use App\LogActivity;
use Illuminate\Database\Eloquent\Model;
// use Spatie\Activitylog\Traits\CausesActivity;

class UserWarehouse extends Model
{

    protected $table = 'user_warehouses';

    protected $fillable = [
        'user_id',
        'warehouse_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}
