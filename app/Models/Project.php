<?php

namespace App\Models;

use App\LogActivity;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\CausesActivity;

class Project extends Model
{
    use LogActivity, CausesActivity;

    // Opsi log
    protected $logName = 'master_projects';

    // Atribut tambahan untuk di-ignore jika dibutuhkan
    // protected array $logAttributesToIgnore = ['password'];
    protected array $logAttributes = [
       'code',
        'name',
        'address',
        'status',
        'user_id',
        'start_date',
        'end_date',
    ];

    protected $table = 'projects';

    protected $fillable = [
        'code',
        'name',
        'address',
        'status',
        'user_id',
        'start_date',
        'end_date',
    ];

    public function inbounds()
    {
        return $this->hasMany(Inbound::class);
    }

    public function outbounds()
    {
        return $this->hasMany(Outbound::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
