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
        'delivery_area_id',
        'pickup_area_id',
        'status',
        'number',
        'payment',
        'status_payment',
        'is_return',
        'is_resend',
        'code_inbound',
        'total_price',
    ];

    public function problemItems()
    {
        return $this->hasMany(ProblemItem::class);
    }

    public function inbound()
    {
        return $this->hasOne(Inbound::class);
    }

    public function pickupArea()
    {
        return $this->belongsTo(Area::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function deliveryArea()
    {
        return $this->belongsTo(DeliveryArea::class);
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
