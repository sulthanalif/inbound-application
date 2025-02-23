<?php

namespace App\Http\Controllers;

use Creagia\LaravelSignPad\Concerns\RequiresSignature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class SignatureController extends Controller
{
    use RequiresSignature;
    public function upload(Request $request)
    {
        $folderPath = public_path('uploadSignature/');

        $image_parts = explode(";base64,", $request->signed);

        $image_type_aux = explode("image/", $image_parts[0]);

        $image_type = $image_type_aux[1];

        $image_base64 = base64_decode($image_parts[1]);

        $file = $folderPath . uniqid() . '.'.$image_type;

        file_put_contents($file, $image_base64);

        return back()->with('success', 'success Full upload signature');
    }

    public function getSignature()
    {
        $this->getSignatureRoute();

        Alert::success('Success', 'Success Full Upload Signature');
        return back();
    }

    public function delete()
    {
        Auth::user()->deleteSignature();
        Alert::success('Success', 'Success Full Delete Signature');
        return back();
    }
}
