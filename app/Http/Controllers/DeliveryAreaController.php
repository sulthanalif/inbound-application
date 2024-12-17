<?php

namespace App\Http\Controllers;

use App\Models\DeliveryArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class DeliveryAreaController extends Controller
{
    public function index()
    {
        $deliveryAreas = DeliveryArea::all();

        confirmDelete('Delete Data!', 'Are you sure you want to delete?');

        return view('delivery-area.index', compact('deliveryAreas'));
    }

    public function create()
    {
        return view('delivery-area.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:delivery_areas,code',
            'name' => 'required|string',
            'address' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();
            $deliveryArea = new DeliveryArea();
            $deliveryArea->code = $request->code;
            $deliveryArea->name = $request->name;
            $deliveryArea->address = $request->address;
            $deliveryArea->save();

            DB::commit();
            Alert::success('Hore!', 'Delivery Area created successfully');
            return redirect()->route('delivery-areas.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            Alert::error('Oops!', $th->getMessage());
            return redirect()->back();
        }
    }

    public function edit(DeliveryArea $deliveryArea)
    {
        return view('delivery-area.edit', compact('deliveryArea'));
    }

    public function update(Request $request, DeliveryArea $deliveryArea)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:delivery_areas,code,' . $deliveryArea->id,
            'name' => 'required|string',
            'address' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();
            $deliveryArea->update([
                'code' => $request->code,
                'name' => $request->name,
                'address' => $request->address,
            ]);
            DB::commit();
            Alert::success('Hore!', 'Delivery Area updated successfully');
            return redirect()->route('delivery-areas.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            Alert::error('Oops!', $th->getMessage());
            return redirect()->back();
        }
    }

    public function destroy(DeliveryArea $deliveryArea)
    {
        try {
            DB::beginTransaction();
            $deliveryArea->delete();
            DB::commit();
            Alert::success('Hore!', 'Delivery Area deleted successfully');
            return redirect()->route('delivery-areas.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            Alert::error('Oops!', $th->getMessage());
            return redirect()->back();
        }
    }
}
