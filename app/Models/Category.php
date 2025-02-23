<?php

namespace App\Models;

use App\LogActivity;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\CausesActivity;

class Category extends Model
{
    use LogActivity, CausesActivity;

    // Opsi log
    protected $logName = 'master_categories';

    // Atribut tambahan untuk di-ignore jika dibutuhkan
    // protected array $logAttributesToIgnore = ['password'];
    protected array $logAttributes = [
        'name',
    ];

    protected $table = 'categories';

    protected $fillable = [
        'name',
    ];

    public function goods()
    {
        return $this->hasMany(Goods::class);
    }
}
