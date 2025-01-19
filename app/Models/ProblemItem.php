<?php

namespace App\Models;

use App\LogActivity;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\CausesActivity;

class ProblemItem extends Model
{
    use LogActivity, CausesActivity;

    // Opsi log
    protected $logName = 'master_problem_items';

    // Atribut tambahan untuk di-ignore jika dibutuhkan
    // protected array $logAttributesToIgnore = ['password'];
    protected array $logAttributes = [
       'outbound_id',
        'goods_id',
        'qty',
        'worthy',
    ];

    protected $table = 'problem_items';

    protected $fillable = [
        'outbound_id',
        'goods_id',
        'qty',
        'worthy',
    ];

    public function goods()
    {
        return $this->belongsTo(Goods::class);
    }

    public function outbound()
    {
        return $this->belongsTo(Outbound::class);
    }
}
