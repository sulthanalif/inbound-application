<?php

namespace App\Http\Controllers;

use App\Models\Goods;
use App\Models\Inbound;
use App\Models\Project;
use App\Models\Outbound;
use App\Models\Warehouse;
use App\Models\InboundItem;
use App\Models\ProblemItem;
use App\Models\OutboundItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Serverces\GenerateCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class InboundController extends Controller
{
    public function index()
    {
        if (Auth::user()->roles[0]->name == 'Admin Engineer') {
            $inbounds = Inbound::with('project')
                ->whereHas('project', function ($query) {
                    $query->where('user_id', Auth::user()->id);
                })
                ->latest()
                ->get();
        } else {
            $inbounds = Inbound::latest()->get();
        }

        return view('inbounds.index', compact('inbounds'));
    }

    public function show(Inbound $inbound)
    {
        $warehouses = Warehouse::with('areas')->get();
        // dd($inbound->outbound->code);
        return view('inbounds.show', compact('inbound', 'warehouses'));
    }

    public function changeStatus(Inbound $inbound, Request $request)
    {
        try {
            DB::transaction(function () use ($inbound, $request) {
                $inbound->status = $request->status;
                $inbound->save();

                if ($request->status == 'Success' && $inbound->is_return == 0) {
                    foreach ($inbound->items as $item) {
                        $goods = Goods::find($item->goods_id);
                        $goods->qty += $item->qty;
                        $goods->save();
                    }
                }

                if ($request->status == 'Success' && $inbound->is_return == 1) {
                    foreach ($inbound->items as $item) {
                        $problemItem = new ProblemItem();
                        $problemItem->outbound_id = $inbound->outbound_id;
                        $problemItem->goods_id = $item->goods_id;
                        $problemItem->qty = $item->qty;
                        $problemItem->save();
                    }
                }
            });

            Alert::success('Success', 'Data ' . $request->status);

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
                $inbound_count = str_pad(Inbound::where('status', 'Approved')->count() + 1, 3, '0', STR_PAD_LEFT);
                $inbound_goods = 'SJ';
                $default = 'JSSZ1';
                $area = $inbound->outbound()->first()->deliveryArea->code;
                $romanNumerals = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
                $monthNumber = Carbon::parse(now())->month;
                $mounth = $romanNumerals[$monthNumber - 1];
                $year = date('Y');
                $number = $inbound_count .'/'. $inbound_goods .'-'. $default . $area. '-' . date('d') .'-'. $mounth .'-'. $year ;

                $inbound->number = $number;
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

    public function success(Request $request, Inbound $inbound)
    {
        try {
            DB::beginTransaction();
            $inbound->status = 'Success';
            $inbound->area_id = $request->area_id;
            $inbound->save();


            foreach ($inbound->items as $item) {
                $problemItem = new ProblemItem();
                $problemItem->outbound_id = $inbound->outbound_id;
                $problemItem->goods_id = $item->goods_id;
                $problemItem->qty = $item->qty;
                $problemItem->save();
            }

            DB::commit();
            Alert::success('Success', 'Data Success');
            return back();
        } catch (\Throwable $th) {
            DB::rollBack();
            Alert::error('Error', $th->getMessage());
            return back();
        }
    }

    public function order()
    {
        // $vtrendors = Vendor::all();
        $goods = Goods::all();
        if (Auth::user()->roles[0]->name == 'Admin Engineer') {
            $outbounds = Outbound::where('user_id', Auth::user()->id)->latest()->get();
        } else {
            $outbounds = Outbound::latest()->get();
        }
        $projects = Project::all();
        $generateCode = new GenerateCode();
        $inbound_code = $generateCode->make(Inbound::count(), 'IN');

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

                $outbound = Outbound::find($request->outbound_id);
                $outbound->order = 1;
                $outbound->save();



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

    public function resendItems(Request $request)
    {
        $inbound = Inbound::find($request->inbound_id);
        $generateCode = new GenerateCode();
        try {
            DB::transaction(function () use ($request, $inbound, $generateCode , &$outbound) {
                $outbound = Outbound::create([
                    'user_id' => $inbound->user->id,
                    'code' => $generateCode->make(Outbound::count(), 'OUT'),
                    'code_inbound' => $inbound->code,
                    'date' => $request->date,
                    'project_id' => $inbound->project_id,
                    'is_resend' => 1,
                ]);

                foreach ($inbound->items as $item) {
                    $outboundItem = new OutboundItem();
                    $outboundItem->outbound_id = $outbound->id;
                    $outboundItem->goods_id = $item->goods_id;
                    $outboundItem->qty = $item->qty;
                    $outboundItem->save();
                }
            });

            Alert::success('Hore!', 'Return Created Successfully');
            return redirect()->route('outbounds.show', $outbound);
        } catch (\Throwable $th) {
            Alert::error('Oops!', $th->getMessage());
            return redirect()->back();
        }
    }

    public function downloadInvoiceDelivery(Inbound $inbound)
    {
        $pdf = Pdf::loadView('inbounds.pdf.inbound', ['inbound' => $inbound]);

        return $pdf->stream('Surat Jalan - '.$inbound->code.'.pdf');
    }
}
