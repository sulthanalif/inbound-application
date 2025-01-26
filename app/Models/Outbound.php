<?php

namespace App\Models;

use App\LogActivity;
use App\Models\Note;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\CausesActivity;

class Outbound extends Model
{
    use LogActivity, CausesActivity;

    // Opsi log
    protected $logName = 'outbound';

    // Atribut tambahan untuk di-ignore jika dibutuhkan
    // protected array $logAttributesToIgnore = ['password'];
    protected array $logAttributes = [
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
        'order',
        'code_inbound',
        'total_price',
        'move_to',
        'move_from',
    ];

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
        'order',
        'code_inbound',
        'total_price',
        'move_to',
        'move_from',
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
