<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Activitylog\Models\Activity;

// use function Laravel\Prompts\confirm;

class LogsActivityController extends Controller
{
    public function index(Request $request)
    {
        confirmDelete('Delete all logs?', 'Are you sure you want to delete all logs?');
        $logs = Activity::with('causer')->latest()->get();
        return view('logs-activity.index', compact('logs'));
    }

    public function delete()
    {
        DB::table('activity_log')->delete();

        Alert::success('Hore!', 'All logs deleted successfully!');
        return redirect()->route('logs.index');
    }
}
