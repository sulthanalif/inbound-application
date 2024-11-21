<?php

namespace App\Http\Controllers;

use App\Models\Outbound;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class OutboundController extends Controller
{
    public function index()
    {
        $outbounds = Outbound::latest()->paginate(10);



        return view('outbounds.index', compact('outbounds'));
    }

    public function show(Outbound $outbound)
    {
        // dd($outbound);
        // confirmDelete('Reject Data!', 'Are you sure you want to reject?');
        return view('outbounds.show', compact('outbound'));
    }

    public function changeStatus(Outbound $outbound, Request $request)
    {
        try {
            DB::transaction(function () use ($outbound, $request) {
                $outbound->status = $request->status;
                $outbound->save();
            });

            Alert::success('Success', 'Data '. $request->status);

            return back();
        } catch (\Throwable $th) {
            Alert::error('Error', $th->getMessage());
            return back();
        }
    }

}
