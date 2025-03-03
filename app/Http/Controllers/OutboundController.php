<?php

namespace App\Http\Controllers;

use DateTime;
use Carbon\Carbon;
use App\Models\Area;
use App\Models\Note;
// use App\GenerateCode;
use App\Models\Goods;
use App\Models\Payment;
use App\Models\Project;
use App\Models\Category;
use App\Models\Outbound;
use App\Models\DeliveryArea;
use App\Models\OutboundItem;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Serverces\GenerateCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class OutboundController extends Controller
{
    // use GenerateCode;
    public function index(Request $request)
    {
        // $search = $request->query('search');
        if (Auth::user()->roles[0]->name == 'Admin Engineer') {
            $outbounds = Outbound::with(['project', 'vendor', 'user'])
                ->whereHas('project', function ($query) {
                    $query->where('user_id', Auth::user()->id);
                })
                ->latest()
                ->get();
        } else {
            $outbounds = Outbound::with(['project', 'vendor', 'user'])
                ->latest()
                ->get();
        }

        return view('outbounds.index', compact('outbounds'));
    }

    public function show(Outbound $outbound)
    {
        // dd($outbound);
        // confirmDelete('Reject Data!', 'Are you sure you want to reject?');
        $goods = Goods::all();
        $warehouses = Warehouse::with('areas')->get();
        $deliveryAreas = DeliveryArea::all();
        $categories = Category::all();
        return view('outbounds.show', compact('outbound', 'deliveryAreas', 'goods', 'warehouses', 'categories'));
    }

    public function pickup(Request $request, Outbound $outbound)
    {
        try {
            DB::beginTransaction();
            $outbound->pickup_area_id = $request->pickup_area_id;
            $outbound->status = 'Pickup';
            $outbound->save();

            foreach ($outbound->items as $item) {
                $goods = Goods::find($item->goods_id);
                $goods->qty = $goods->qty - $item->qty;
                $goods->save();
            }

            DB::commit();
            Alert::success('Success', 'Data Picked Up');
            return back();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::channel('debug')->error("message: '{$th->getMessage()}',  file: '{$th->getFile()}',  line: {$th->getLine()}");
            return back();
        }
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
            Log::channel('debug')->error("message: '{$th->getMessage()}',  file: '{$th->getFile()}',  line: {$th->getLine()}");
            return back();
        }
    }

    public function changeStatus(Outbound $outbound, Request $request)
    {

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
                    $outbound->status = $request->status;
                    $outbound->save();

                    if ($request->status == 'Success') {
                        if ($outbound->is_resend) {
                            $isRentable = $outbound->items->pluck('goods.type')->contains('Rentable');
                            if ($isRentable) {
                                $outbound->project->statusProject->next = $isRentable;
                                $outbound->project->statusProject->end = false;
                            }else {
                                $outbound->project->statusProject->end = true;
                            }
                        } else {
                            $isRentable = $outbound->items->pluck('goods.type')->contains('Rentable');
                            if ($isRentable) {
                                $outbound->project->statusProject->next = $isRentable;
                                $outbound->project->statusProject->end = false;
                            }else {
                                $outbound->project->statusProject->end = true;
                            }
                        }

                        $outbound->project->statusProject->save();
                    }
                }
            });

            Alert::success('Success', 'Data ' . $request->status);

            return back();
        } catch (\Throwable $th) {
            Log::channel('debug')->error("message: '{$th->getMessage()}',  file: '{$th->getFile()}',  line: {$th->getLine()}");
            return back();
        }
    }

    public function request()
    {
        $generateCode = new GenerateCode();
        // $vendors = Vendor::all();
        $categories = Category::with(['goods.unit'])->get();
        $goods = Goods::all();
        $projects = Project::where('status', '!=', 'Finished')->get();
        // $code_outbound = 'OUT' . date('Ymd') . Outbound::count() . rand(1000, 9999);
        $code_outbound = $generateCode->make(Outbound::count(), 'OUT');
        return view('request.index', compact('goods', 'code_outbound', 'projects', 'categories'));
    }

    public function storeRequest(Request $request)
    {
        // dd($request->all());
        $data = json_decode($request->input('data'), true);

        if ($data == null) {
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

                $outbound->project->statusProject->end = false;
                $outbound->project->statusProject->save();

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
            Log::channel('debug')->error("message: '{$th->getMessage()}',  file: '{$th->getFile()}',  line: {$th->getLine()}");
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
            Log::channel('debug')->error("message: '{$th->getMessage()}',  file: '{$th->getFile()}',  line: {$th->getLine()}");
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
                $order_count = str_pad(Outbound::count(), 3, '0', STR_PAD_LEFT);
                $outbound_goods = 'DN';
                $default = 'JSSZ1';
                $area = DeliveryArea::find($request->deliveryArea)->code;
                // $mounth = DateTime::createFromFormat('!m', date('m'))->format('F');
                $romanNumerals = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
                $monthNumber = Carbon::parse(now())->month;
                $mounth = $romanNumerals[$monthNumber - 1];
                $year = date('Y');
                $number = $order_count . '/' . $outbound_goods . '-' . $default . $area . '-' . date('d') . '-' . $mounth . '-' . $year;

                $outbound->status = 'Approved to delivery';
                $outbound->number = $number;
                $outbound->delivery_area_id = $request->deliveryArea;
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
            Log::channel('debug')->error("message: '{$th->getMessage()}',  file: '{$th->getFile()}',  line: {$th->getLine()}");
            return back();
        }
    }

    public function downloadInvoiceDelivery(Outbound $outbound)
    {
        $pdf = Pdf::loadView('outbounds.pdf.outbound', ['outbound' => $outbound]);

        return $pdf->stream('Surat Jalan - ' . $outbound->code . '.pdf');
    }
}
