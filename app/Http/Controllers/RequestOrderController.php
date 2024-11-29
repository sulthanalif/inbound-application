<?php

namespace App\Http\Controllers;

use App\Models\Goods;
use App\Models\Vendor;
use App\Models\Outbound;
use App\Models\OutboundItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class RequestOrderController extends Controller
{
    public function index()
    {
        $outbounds = Outbound::all();
        return view('request.index', compact('outbounds'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $data = json_decode($request->input('data'), true);

        try {
            DB::transaction(function () use ($request, $data) {
                //add outbound
                $outbound = new Outbound();
                $outbound->user_id = Auth::user()->id;
                $outbound->vendor_id = $request->vendor_id;
                $outbound->date = $request->date;
                $outbound->total_price = $request->total_price;
                $outbound->status = 'Pending';
                $outbound->save();

                foreach ($data as $item) {
                    //add outbound item
                    $outboundItem = new OutboundItem();
                    $outboundItem->outbound_id = $outbound->id;
                    $outboundItem->goods_id = $item['item_id'];
                    $outboundItem->qty = $item['qty'];
                    $outboundItem->sub_total = $item['subtotal'];
                    $outboundItem->save();

                    //update stock
                    $goods = Goods::find($item['item_id']);
                    $goods->qty = $goods->qty - $item['qty'];
                    $goods->save();
                }
            });

            Alert::success('Success', 'Request goods successfully');
            return redirect()->route('request-goods.index');
        } catch (\Throwable $th) {
            Alert::error('Error', $th->getMessage());
            return redirect()->route('request-goods.index');
        }
    }
}
