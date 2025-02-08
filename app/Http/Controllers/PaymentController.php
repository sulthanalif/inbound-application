<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Outbound;
// use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function downloadImagePayment(Payment $payment)
    {
        // dd($payment);
        //cari image payment
        $filePath = 'images/payments/' . $payment->image;
        if (Storage::disk('public')->exists($filePath)) {
            $file = Storage::disk('public')->get($filePath);
            return response($file, 200)
                ->header('Content-Type', 'image/jpeg')
                ->header('Content-Disposition', 'inline; filename="' . $payment->image . '"');
        } else {
            return response()->json(['message' => 'Image not found'], 404);
        }
    }

    public function paymentOutbound(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'outbound_id' => 'required',
            'paid' => 'sometimes|nullable|numeric|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors(),
                'code' => 400,
            ]);
        }


        $outbound = Outbound::find($request->outbound_id);

        try {
            DB::transaction(function () use ($request, $outbound) {
                if ($outbound->payment == 'Full Payment') {
                    $payment = Payment::where('outbound_id', $outbound->id)->latest()->first();

                    $imageName = 'payment_' . $payment->code_payment . '.' . $request->file('image')->getClientOriginalExtension();
                    Storage::disk('public')->put('images/payments/' . $imageName, $request->file('image')->getContent());

                    $payment = Payment::where('outbound_id', $outbound->id)->first();
                    $payment->paid = $outbound->total_price;
                    $payment->remaining = 0;
                    $payment->image = $imageName;
                    $payment->date = now();
                    $payment->payment_method = $request->payment_method;
                    $payment->bank = $request->bank ?? null;
                    $payment->save();

                    $outbound->status_payment = 'Paid';
                    $outbound->save();
                } else {
                    $payment = Payment::where('outbound_id', $outbound->id)->latest()->first();
                    if ($payment->date == null) {
                        //jika pembayaran pertama
                        $imageName = 'payment_' . $payment->code_payment . '.' . $request->file('image')->getClientOriginalExtension();
                        Storage::disk('public')->put('images/payments/' . $imageName, $request->file('image')->getContent());

                        $payment = Payment::where('outbound_id', $outbound->id)->first();
                        $payment->paid = $request->paid;
                        $payment->remaining = $outbound->total_price - $request->paid;
                        $payment->image = $imageName;
                        $payment->date = now();
                        $payment->payment_method = $request->payment_method;
                        $payment->bank = $request->bank ?? null;
                        $payment->save();

                        $outbound->status_payment = 'Partially Paid';
                        $outbound->save();
                    } else {
                        //jika pembayaran selanjutnya
                        $imageName = 'payment_' . $payment->code_payment . '.' . $request->file('image')->getClientOriginalExtension();
                        Storage::disk('public')->put('images/payments/' . $imageName, $request->file('image')->getContent());

                        $new_payment = new Payment();
                        $new_payment->code_payment = 'PAY' . date('Ymd') . Outbound::count() . rand(1000, 9999);
                        $new_payment->outbound_id = $outbound->id;
                        $new_payment->total_payment = $outbound->total_price;
                        $new_payment->paid = $request->paid;
                        $new_payment->remaining = $payment->remaining - $request->paid;
                        $new_payment->image = $imageName;
                        $new_payment->payment_method = $request->payment_method;
                        $new_payment->bank = $request->bank ?? null;
                        $new_payment->date = now();
                        $new_payment->save();

                        if ($new_payment->remaining == 0) {
                            $outbound->status_payment = 'Paid';
                            $outbound->save();
                        }
                    }
                }
            });

            Alert::success('Hore!', 'Payment successfully');
            return redirect()->route('outbounds.show', $outbound);
        } catch (\Throwable $th) {
            Log::channel('debug')->error("message: '{$th->getMessage()}',  file: '{$th->getFile()}',  line: {$th->getLine()}");
            return redirect()->route('outbounds.show', $outbound);
        }
    }
}
