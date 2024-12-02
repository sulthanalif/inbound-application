<?php

namespace App\Http\Controllers;

use DateTime;
use Carbon\Carbon;
use App\Models\Area;
use App\Models\Note;
use App\Models\Goods;
use App\Models\Payment;
use App\Models\Project;
use App\Models\Category;
use App\Models\Outbound;
use App\Models\OutboundItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class OutboundController extends Controller
{
    public function index(Request $request)
    {
        // $search = $request->query('search');
        $outbounds = Outbound::with(['project', 'vendor', 'user'])
            ->latest()
            ->get();

        return view('outbounds.index', compact('outbounds'));
    }

    public function show(Outbound $outbound)
    {
        // dd($outbound);
        // confirmDelete('Reject Data!', 'Are you sure you want to reject?');
        $goods = Goods::all();
        $areas = Area::all();
        $categories = Category::all();
        return view('outbounds.show', compact('outbound', 'goods', 'areas', 'categories'));
    }

    public function delivery(Request $request, Outbound $outbound)
    {
        try {
            DB::transaction(function () use ($outbound, $request) {
                $outbound->status = 'Delivery';
                $outbound->sender_name = $request->sender_name;
                $outbound->vehicle_number = $request->vehicle_number;
                $outbound->save();
            });
            Alert::success('Success', 'Data Delivered');
            return back();
        } catch (\Throwable $th) {
            Alert::error('Error', $th->getMessage());
            return back();
        }
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

                    if ($status == 'Rejected') {
                        $note = $outbound->note ?? new Note();
                        $note->outbound_id = $outbound->id;
                        $note->reject = $request->reject;
                        $note->save();
                    }
                } else {
                    if ($request->status == 'Pickup') {
                        foreach ($outbound->items as $item) {
                            $goods = Goods::find($item->goods_id);
                            $goods->qty = $goods->qty - $item->qty;
                            $goods->save();
                        }
                    }

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
        $categories = Category::with(['goods.unit'])->get();
        $goods = Goods::all();
        $projects = Project::all();
        $code_outbound = 'OUT' . date('Ymd') . Outbound::count() . rand(1000, 9999);
        return view('request.index', compact('goods', 'code_outbound', 'projects', 'categories'));
    }

    public function storeRequest(Request $request)
    {
        // dd($request->all());
        $data = json_decode($request->input('data'), true);

        if($data == null){
            Alert::warning('Warning', 'Data cannot be empty');
            return redirect()->back();
        }

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
                $outbound->payment = $request->payment;
                $outbound->save();

                //add payment
                $payment = new Payment();
                $payment->code_payment = 'PAY' . date('Ymd') . Outbound::count() . rand(1000, 9999);
                $payment->outbound_id = $outbound->id;
                // $payment->total_payment = $request->total_price;
                $payment->save();

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

    public function editItems(Outbound $outbound)
    {
        $categories = Category::with(['goods.unit'])->get();
        return view('outbounds.edit', compact('outbound', 'categories'));
    }

    public function updateRequest(Request $request, Outbound $outbound)
    {
        // dd($outbound);
        $data = json_decode($request->input('data'), true);

        try {
            DB::transaction(function () use ($request, $data, $outbound) {
                //update outbound
                $outbound->update([
                   'total_price' => array_sum(array_column($data, 'subtotal'))
                ]);

                //delete all outbound items
                $outbound->items()->delete();

                //input data item
                foreach ($data as $item) {
                    $outboundItem = new OutboundItem();
                    $outboundItem->outbound_id = $outbound->id;
                    $outboundItem->goods_id = $item['item_id'];
                    $outboundItem->qty = $item['qty'];
                    $outboundItem->sub_total = $item['subtotal'];
                    $outboundItem->save();
                }
            });

            Alert::success('Success', 'Update request successfully');
            return redirect()->route('outbounds.show', $outbound);
        } catch (\Throwable $th) {
            Alert::error('Error', $th->getMessage());
            return back();
        }
    }

    public function approveDelivery(Request $request, Outbound $outbound)
    {
        // $pdf = Pdf::loadView('outbounds.pdf.outbound', ['outbound' => $outbound]);
        // if (Storage::disk('public')->put('pdf/Outbound_OUT2024112215861.pdf', $pdf->output())) {
        //     return response()->json('OKK');
        // }
        // dd($request->all());
        try {
            DB::transaction(function () use ($outbound, $request) {
                $order_count = str_pad(Outbound::where('status', 'Approved')->count() + 1, 3, '0', STR_PAD_LEFT);
                $outbound_goods = 'DN';
                $default = 'JSSZ1';
                $area = Area::find($request->area_id)->code;
                // $mounth = DateTime::createFromFormat('!m', date('m'))->format('F');
                $romanNumerals = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
                $monthNumber = Carbon::parse(now())->month;
                $mounth = $romanNumerals[$monthNumber - 1];
                $year = date('Y');
                $number = $order_count .'/'. $outbound_goods .'-'. $default . $area .'-'. $mounth .'-'. $year ;

                $outbound->status = 'Approved to delivery';
                $outbound->number = $number;
                $outbound->area_id = $request->area_id;
                $outbound->save();
            });

            $pdf = Pdf::loadView('outbounds.pdf.outbound', ['outbound' => $outbound]);
            // if (Storage::disk('public')->put('pdf/Outbound_'.$outbound->code.'.pdf', $pdf->output())) {
            //     Alert::success('Success', 'Data Delivered');
            //     return back();
            // }
            // Alert::error('Error', 'Something went wrong');
            Alert::success('Success', 'Data Delivered');
            return back();
        } catch (\Throwable $th) {
            Alert::error('Error', $th->getMessage());
            return back();
        }
    }

    public function downloadInvoiceDelivery(Outbound $outbound)
    {
        $pdf = Pdf::loadView('outbounds.pdf.outbound', ['outbound' => $outbound]);

        return $pdf->stream('Surat Jalan - '.$outbound->code.'.pdf');
    }
}
