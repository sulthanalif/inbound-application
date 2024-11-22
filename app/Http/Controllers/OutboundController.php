<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Goods;
use App\Models\Outbound;
use App\Models\OutboundItem;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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
        $goods = Goods::all();
        return view('outbounds.show', compact('outbound', 'goods'));
    }

    public function changeStatus(Outbound $outbound, Request $request)
    {
        // dd($request->all());
        try {
            DB::transaction(function () use ($outbound, $request) {
                if ($request->status == 'Approved') {
                    $status = $request->is_approved == 1 ? 'Approved' : 'Rejected';
                    $outbound->status = $status;
                    $outbound->save();

                    foreach ($outbound->items as $item) {
                        $goods = Goods::find($item->goods_id);
                        $goods->qty = $goods->qty - $item->qty;
                        $goods->save();
                    }

                    if ($status == 'Rejected') {
                        $note = $outbound->note ?? new Note();
                        $note->outbound_id = $outbound->id;
                        $note->reject = $request->reject;
                        $note->save();
                    }
                } else {
                    $outbound->status = $request->status;
                    $outbound->save();
                }
            });

            Alert::success('Success', 'Data ' . $request->status);

            return back();
        } catch (\Throwable $th) {
            Alert::error('Error', $th->getMessage());
            return back();
        }
    }

    public function request()
    {
        // $vendors = Vendor::all();
        $goods = Goods::all();
        $projects = Project::all();
        $code_outbound = 'OUT' . date('Ymd') . Outbound::count() . rand(1000, 9999);
        return view('request.index', compact('goods', 'code_outbound', 'projects'));
    }

    public function storeRequest(Request $request)
    {
        // dd($request->all());
        $data = json_decode($request->input('data'), true);

        try {
            DB::transaction(function () use ($request, $data) {
                //add outbound
                $outbound = new Outbound();
                $outbound->user_id = Auth::user()->id;
                $outbound->vendor_id = $request->vendor_id;
                $outbound->project_id = $request->project_id;
                $outbound->code = $request->code_outbound;
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


                }
            });

            Alert::success('Success', 'Request goods successfully');
            return redirect()->route('outbounds.index');
        } catch (\Throwable $th) {
            Alert::error('Error', $th->getMessage());
            return redirect()->route('outbounds.index');
        }
    }

    public function updateRequest(Request $request, Outbound $outbound)
    {
        // dd($request->all());
        $data = json_decode($request->input('data'), true);

        try {
            DB::transaction(function () use ($request, $data, $outbound) {
                //update outbound
                $outbound->update([
                   'total_price' => $request->total_price
                ]);

                //hapus semua data outboud item
                OutboundItem::where('outbound_id', $outbound->id)->delete();

                //input data item
                foreach ($data as $item) {
                    $outboundItem = new OutboundItem();
                    $outboundItem->outbound_id = $outbound->id;
                    $outboundItem->goods_id = $item['id'];
                    $outboundItem->qty = $item['qty'];
                    $outboundItem->sub_total = $item['subtotal'];
                    $outboundItem->save();
                }
            });

            Alert::success('Success', 'Update request successfully');
            return back();
        } catch (\Throwable $th) {
            Alert::error('Error', $th->getMessage());
            return back();
        }
    }
}
