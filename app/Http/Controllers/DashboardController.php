<?php

namespace App\Http\Controllers;

use App\Models\Inbound;
use App\Models\Outbound;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $outbounds_stas = Outbound::all();
        $inbounds_stas = Inbound::all();

        $stats = [
            'outbounds' => [
                'pending' => $outbounds_stas->where('status', 'Pending')->count(),
                'rejected' => $outbounds_stas->where('status', 'Rejected')->count(),
                'pickup' => $outbounds_stas->where('status', 'Accepted')->count(),
                'delivery' => $outbounds_stas->where('status', 'Pickup')->count(),
                'accDeliv' => $outbounds_stas->where('status', 'Delivery')->count(),
                'success' => $outbounds_stas->where('status', 'Success')->count()
            ],
            'inbounds' => [
                'pending' => $inbounds_stas->where('status', 'Pending')->count(),
                'rejected' => $inbounds_stas->where('status', 'Rejected')->count(),
                'success' =>  $inbounds_stas->where('status', 'Success')->count()
            ]
        ];

        // dd($stats);
        //chart_data_type_items
        $filter_month = $request->get('filter_month');
        $filter_year = $request->get('filter_year', now()->year);

        $query = Outbound::with('items')
            ->whereYear('date', $filter_year);

        if ($filter_month) {
            $query->whereMonth('date', $filter_month);
        }

        $outbounds = $query->get();

        $chart_data_type_items = [
            'Consumable' => 0,
            'Rentable' => 0,
        ];

        foreach ($outbounds as $outbound) {
            foreach ($outbound->items as $item) {
                if ($item->goods->type == 'Consumable') {
                    $chart_data_type_items['Consumable']++;
                } else {
                    $chart_data_type_items['Rentable']++;
                }
            }
        }


        // dd($chart_data_type_items);
        return view('dashboard', compact('chart_data_type_items', 'stats'));
    }
}
