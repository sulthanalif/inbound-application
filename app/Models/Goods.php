<?php

namespace App\Models;

use App\LogActivity;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\CausesActivity;

class Goods extends Model
{
    use LogActivity, CausesActivity;

    // Opsi log
    protected $logName = 'master_goods';

    // Atribut tambahan untuk di-ignore jika dibutuhkan
    // protected array $logAttributesToIgnore = ['password'];
    protected array $logAttributes = [
        'name',
        'code',
        'length',
        'width',
        'height',
        'weight',
        'description',
        'condition',
        'price',
        'qty',
        'type',
        'minimum_order',
        'unit_time',
        'capital',
        'unit_id',
        'vendor_id',
        'category_id',
        'area_id',
        'user_id',
    ];

    protected $table = 'goods';

    protected $fillable = [
        'name',
        'code',
        'length',
        'width',
        'height',
        'weight',
        'description',
        'condition',
        'price',
        'qty',
        'type',
        'minimum_order',
        'unit_time',
        'capital',
        'unit_id',
        'vendor_id',
        'category_id',
        'area_id',
        'user_id',
    ];

    public function warehouseName(): string
    {
        return $this->area->warehouse->name.' - '.$this->area->name. ' - '. $this->area->container. ' - '. $this->area->rack. ' - '. $this->area->number;
    }

    public function problemItems()
    {
        return $this->hasMany(ProblemItem::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function inbounditems()
    {
        return $this->hasMany(InboundItem::class);
    }

    public function outboundItems()
    {
        return $this->hasMany(OutboundItem::class);
    }
}
