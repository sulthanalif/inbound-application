<?php

namespace App\Http\Controllers;

use App\Models\Goods;
use App\Models\Vendor;
use App\Models\Inbound;
use App\Models\Outbound;
use App\Models\InboundItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class ReturnController extends Controller
{
    public function index()
    {
        $outbounds = Outbound::where('status', 'success')->get();

        return view('return.index', compact('outbounds'));
    }

    public function store(Request $request)
    {
        dd($request->all());
        $data = json_decode($request->input('data'), true);

        try {
            DB::transaction(function () use ($request, $data) {
                $inbound = new Inbound();
                $inbound->vendor_id = $request->vendor_id;
                $inbound->user_id = Auth::user()->id;
                $inbound->date = $request->date;
                $inbound->sender_name = $request->sender_name;
                $inbound->vehicle_number = $request->vehicle_number;
                $inbound->description = $request->description;
                $inbound->save();

                foreach ($data as $item) {
                    $inboundItem = new InboundItem();
                    $inboundItem->inbound_id = $inbound->id;
                    $inboundItem->goods_id = $item['item_id'];
                    $inboundItem->qty = $item['qty'];
                    $inboundItem->save();
                }

                $goods = Goods::find($item['item_id']);
                $goods->qty += $item['qty'];
                $goods->save();
            });

            Alert::success('Hore!', 'Return Created Successfully');
            return back();
        } catch (\Throwable $th) {
            Alert::error('Oops!', $th->getMessage());
            return back();
        }
    }
}
