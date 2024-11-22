<?php

namespace App\Http\Controllers;

use DateTime;
use Carbon\Carbon;
use App\Models\Note;
use App\Models\Goods;
use App\Models\Project;
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

    public function approveDelivery(Request $request, Outbound $outbound)
    {
        // $pdf = Pdf::loadView('outbounds.pdf.outbound', ['outbound' => $outbound]);
        // if (Storage::disk('public')->put('pdf/Outbound_OUT2024112215861.pdf', $pdf->output())) {
        //     return response()->json('OKK');
        // }
        try {
            DB::transaction(function () use ($outbound, $request) {
                $order_count = str_pad(Outbound::where('status', 'Approved')->count() + 1, 3, '0', STR_PAD_LEFT);
                $outbound_goods = 'DN';
                $default = 'JSSZ1';
                $area = $request->area;
                // $mounth = DateTime::createFromFormat('!m', date('m'))->format('F');
                $romanNumerals = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
                $monthNumber = Carbon::parse(now())->month;
                $mounth = $romanNumerals[$monthNumber - 1];
                $year = date('Y');
                $number = $order_count .'/'. $outbound_goods .'-'. $default . $area .'-'. $mounth .'-'. $year ;

                $outbound->status = 'Approved to delivery';
                $outbound->number = $number;
                $outbound->save();
            });

            $pdf = Pdf::loadView('outbounds.pdf.outbound', ['outbound' => $outbound]);
            if (Storage::disk('public')->put('pdf/Outbound_'.$outbound->code.'.pdf', $pdf->output())) {
                Alert::success('Success', 'Data Delivered');
                return back();
            }
            Alert::error('Error', 'Something went wrong');
            return back();
        } catch (\Throwable $th) {
            Alert::error('Error', $th->getMessage());
            return back();
        }
    }

    public function downloadInvoiceDelivery(Outbound $outbound)
    {
        $filePath = 'pdf/Outbound_' . $outbound->code . '.pdf';
        if (Storage::disk('public')->exists($filePath)) {
            $file = Storage::disk('public')->get($filePath);
            return response($file, 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="Outbound_' . $outbound->code . '.pdf"');
        } else {
            return response()->json(['message' => 'File tidak ditemukan'], 404);
        }
    }
}
