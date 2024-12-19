<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inbound extends Model
{
    protected $table = 'inbounds';

    protected $fillable = [
        'code',
        'outbound_id',
        'vendor_id',
        'user_id',
        'area_id',
        'date',
        'project_id',
        'sender_name',
        'vehicle_number',
        'status',
        'is_return',
        'number',
        'code_outbound',
        'description',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function resend()
    {
        return $this->hasMany(Resend::class);
    }

    public function outbound()
    {
        return $this->belongsTo(Outbound::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function items()
    {
        return $this->hasMany(InboundItem::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
