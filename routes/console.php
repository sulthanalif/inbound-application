<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('activitylog:clean', function () {
    DB::table('activity_log')->delete();
    $this->line('Activity log cleaned');
});
