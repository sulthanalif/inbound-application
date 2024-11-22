<?php

namespace App\Http\Controllers;

use App\Models\Inbound;
use App\Models\Outbound;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $count_inbound = Inbound::count();
        $count_outbound = Outbound::count();

        return view('dashboard', compact('count_inbound', 'count_outbound'));
    }
}
