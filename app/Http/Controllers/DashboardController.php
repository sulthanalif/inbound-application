<?php

namespace App\Http\Controllers;

use App\Models\Inbound;
use App\Models\Outbound;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $filter_month = $request->get('filter_month');
        $filter_year = $request->get('filter_year', now()->year);

        if (Auth::user()->roles[0]->name == 'Admin Engineer') {
            $outbounds_stas = Outbound::where('user_id', Auth::user()->id)
                ->whereYear('date', $filter_year);
            if ($filter_month) {
                $outbounds_stas->whereMonth('date', $filter_month);
            }
            $outbounds_stas = $outbounds_stas->get();

            $inbounds_stas = Inbound::where('user_id', Auth::user()->id)
                ->whereYear('date', $filter_year);
            if ($filter_month) {
                $inbounds_stas->whereMonth('date', $filter_month);
            }
            $inbounds_stas = $inbounds_stas->get();
        } else {
            $outbounds_stas = Outbound::whereYear('date', $filter_year);
            if ($filter_month) {
                $outbounds_stas->whereMonth('date', $filter_month);
            }
            $outbounds_stas = $outbounds_stas->get();

            $inbounds_stas = Inbound::whereYear('date', $filter_year);
            if ($filter_month) {
                $inbounds_stas->whereMonth('date', $filter_month);
            }
            $inbounds_stas = $inbounds_stas->get();
        }


        $stats = [
            'outbounds' => [
                'pending' => $outbounds_stas->where('status', 'Pending')->count(),
                'rejected' => $outbounds_stas->where('status', 'Rejected')->count(),
                'pickup' => $outbounds_stas->where('status', 'Approved')->count(),
                'delivery' => $outbounds_stas->where('status', 'Pickup')->count(),
                'accDeliv' => $outbounds_stas->where('status', 'Delivery')->count(),
                'accToDeliv' => $outbounds_stas->where('status', 'Approved to delivery')->count(),
                'success' => $outbounds_stas->where('status', 'Success')->count()
            ],
            'inbounds' => [
                'pending' => $inbounds_stas->where('status', 'Pending')->count(),
                'rejected' => $inbounds_stas->where('status', 'Rejected')->count(),
                'delivery' => $inbounds_stas->where('status', 'Approved')->count(),
                'goodsArrived' => $inbounds_stas->where('status', 'Delivery')->count(),
                'success' =>  $inbounds_stas->where('status', 'Success')->count()
            ]
        ];

        if (Auth::user()->roles[0]->name == 'Admin Engineer') {
            $query = Outbound::with('items')
                ->where('user_id', Auth::user()->id)
                ->latest()
                ->whereYear('date', $filter_year);

            if ($filter_month) {
                $query->whereMonth('date', $filter_month);
            }

            $outbounds = $query->get();

            $query_in = Inbound::with('items')
                ->where('user_id', Auth::user()->id)
                ->latest()
                ->whereYear('date', $filter_year);

            if ($filter_month) {
                $query_in->whereMonth('date', $filter_month);
            }

            $inbounds = $query_in->get();
        } else {
            $query = Outbound::with('items')
                ->latest()
                ->whereYear('date', $filter_year);

            if ($filter_month) {
                $query->whereMonth('date', $filter_month);
            }

            $outbounds = $query->get();

            $query_in = Inbound::with('items')
                ->latest()
                ->whereYear('date', $filter_year);

            if ($filter_month) {
                $query_in->whereMonth('date', $filter_month);
            }

            $inbounds = $query_in->get();
        }


        //chart_transaction_amount
        $chart_transaction_amount = [
            'outbound' => array_fill(0, 12, 0),
            'inbound' => array_fill(0, 12, 0),
            'return' => array_fill(0, 12, 0)
        ];

        foreach ($outbounds as $outbound) {
            if ($outbound->status == 'Success') {
                $month = (int) date('m', strtotime($outbound->date));
                $chart_transaction_amount['outbound'][$month - 1]++;
                if ($outbound->is_resend) {
                    $chart_transaction_amount['return'][$month - 1]++;
                }
            }
        }

        foreach ($inbounds as $inbound) {
            if ($inbound->status == 'Success') {
                $month = (int) date('m', strtotime($inbound->date));
                $chart_transaction_amount['inbound'][$month - 1]++;
            }
        }

        // dd($chart_transaction_amount);



        // dd($stats);
        //chart_data_type_items
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
        return view('dashboard', compact('outbounds', 'inbounds', 'chart_data_type_items', 'stats', 'chart_transaction_amount'));
    }
}
