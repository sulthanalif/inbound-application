<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class VendorController extends Controller
{
    public function index()
    {
        $vendors = Vendor::latest()->get();

        confirmDelete('Delete Data!', 'Are you sure you want to delete?');

        return view('vendors.index', compact('vendors'));
    }

    public function create()
    {
        return view('vendors.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
           'name' => 'required|unique:vendors,name',
           'email' => 'required|email|unique:vendors,email',
           'phone' => 'required|numeric',
           'address' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request) {
               $vendor = new Vendor();
               $vendor->name = $request->name;
               $vendor->email = $request->email;
               $vendor->phone = $request->phone;
               $vendor->address = $request->address;
               $vendor->save();
            });

            Alert::success('Hore!', 'Vendor Created Successfully');
            return redirect()->route('vendors.index');
        } catch (\Throwable $th) {
            Log::channel('debug')->error("message: '{$th->getMessage()}',  file: '{$th->getFile()}',  line: {$th->getLine()}");
            return redirect()->back();
        }
    }

    public function edit(Vendor $vendor)
    {
        return view('vendors.edit', compact('vendor'));
    }

    public function update(Request $request, Vendor $vendor)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:vendors,name,' . $vendor->id,
            'email' => 'required|email|unique:vendors,email,' . $vendor->id,
            'phone' => 'required|numeric',
            'address' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request, $vendor) {
                $vendor->name = $request->name;
                $vendor->email = $request->email;
                $vendor->phone = $request->phone;
                $vendor->address = $request->address;
                $vendor->save();
            });

            Alert::success('Hore!', 'Vendor Updated Successfully');
            return redirect()->route('vendors.index');
        } catch (\Throwable $th) {
            Log::channel('debug')->error("message: '{$th->getMessage()}',  file: '{$th->getFile()}',  line: {$th->getLine()}");
            return redirect()->back();
        }
    }

    public function destroy(Vendor $vendor)
    {
        try {
            DB::transaction(function () use ($vendor) {
                $vendor->delete();
            });

            Alert::success('Hore!', 'Vendor Deleted Successfully');
            return redirect()->route('vendors.index');
        } catch (\Throwable $th) {
            Log::channel('debug')->error("message: '{$th->getMessage()}',  file: '{$th->getFile()}',  line: {$th->getLine()}");
            return redirect()->route('vendors.index');
        }
    }
}
