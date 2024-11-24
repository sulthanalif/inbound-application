<?php

namespace App\Models;

use App\Models\Note;
use Illuminate\Database\Eloquent\Model;

class Outbound extends Model
{
    protected $table = 'outbounds';

    protected $fillable = [
        'user_id',
        'code',
        'vendor_id',
        'date',
        'sender_name',
        'vehicle_number',
        'project_id',
        'area_id',
        'status',
        'number',
        'status_payment',
        'total_price',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function items()
    {
        return $this->hasMany(OutboundItem::class);
    }

    public function note()
    {
        return $this->hasOne(Note::class);
    }
}
