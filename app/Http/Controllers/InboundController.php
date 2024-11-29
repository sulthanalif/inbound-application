<?php

namespace App\Http\Controllers;

use App\Models\Goods;
use App\Models\Inbound;
use App\Models\Project;
use App\Models\Outbound;
use App\Models\InboundItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class InboundController extends Controller
{
    public function index()
    {
        $inbounds = Inbound::latest()->paginate(10);

        return view('inbounds.index', compact('inbounds'));
    }

    public function show(Inbound $inbound)
    {
        return view('inbounds.show', compact('inbound'));
    }

    public function changeStatus(Inbound $inbound, Request $request)
    {
        try {
            DB::transaction(function () use ($inbound, $request) {
                $inbound->status = $request->status;
                $inbound->save();
            });

            Alert::success('Success', 'Data '. $request->status);

            return back();
        } catch (\Throwable $th) {
            Alert::error('Error', $th->getMessage());
            return back();
        }
    }

    public function delivery(Inbound $inbound, Request $request)
    {
        $validator = Validator::make($request->all(), [
           'sender_name' => 'required|string',
           'vehicle_number' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($inbound, $request) {
                $inbound->sender_name = $request->sender_name;
                $inbound->vehicle_number = $request->vehicle_number;
                $inbound->status = 'Delivery';
                $inbound->save();
            });

            Alert::success('Success', 'Data Delivery');
            return back();
        } catch (\Throwable $th) {
            Alert::error('Error', $th->getMessage());
            return back();
        }
    }

    public function order()
    {
        // $vendors = Vendor::all();
        $goods = Goods::all();
        $outbounds = Outbound::all();
        $projects = Project::all();
        $inbound_code = 'IN'.date('Ymd').Inbound::count().rand(1000, 9999);

        // dd($inbound_code);
        return view('order.index', compact('goods', 'outbounds', 'inbound_code', 'projects'));
    }

    public function storeOrder(Request $request)
    {
        // dd($request->all());
        $data = json_decode($request->input('data'), true);

        // $validator = Validator::make($data, [
        //     'qty' => 'max'.
        // ]);

        try {
            DB::transaction(function () use ($request, $data) {
                $inbound = new Inbound();
                $inbound->vendor_id = $request->vendor_id;
                $inbound->code = $request->code;
                $inbound->user_id = Auth::user()->id;
                $inbound->is_return = 1;
                $inbound->project_id = Outbound::find($request->outbound_id)->project_id;
                $inbound->outbound_id = $request->outbound_id;
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

                // $goods = Goods::find($item['item_id']);
                // $goods->qty += $item['qty'];
                // $goods->save();
            });

            Alert::success('Hore!', 'Return Created Successfully');
            return redirect()->route('inbounds.index');
        } catch (\Throwable $th) {
            Alert::error('Oops!', $th->getMessage());
            return redirect()->route('inbounds.index');
        }
    }

    public function return()
    {
        $inbounds = Inbound::all();

        return view('return.index', compact('inbounds'));
    }
}
