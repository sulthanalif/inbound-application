<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Warehouse;
// use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class AreaController extends Controller
{
    public function index()
    {
        $areas = Area::latest()->get();

        confirmDelete('Delete Data!', 'Are you sure you want to delete?');
        return view('areas.index', compact('areas'));
    }

    public function create(Warehouse $warehouse)
    {
        return view('areas.create', compact('warehouse'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'warehouse_id' => 'required',
            'code' => 'required|string|unique:areas,code',
            'name' => 'required|string',
            'description' => 'required|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request) {
                $area = new Area();
                $area->warehouse_id = $request->warehouse_id;
                $area->code = $request->code;
                $area->name = $request->name;
                $area->description = $request->description;
                $area->save();
            });

            Alert::success('Hore!', 'Area Created Successfully');
            return redirect()->route('warehouses.show', $request->warehouse_id);
        } catch (\Throwable $th) {
            Alert::error('Oops!', $th->getMessage());
            return redirect()->route('warehouses.show', $request->warehouse_id);
        }
    }

    public function edit(Area $area)
    {
        return view('areas.edit', compact('area'));
    }

    public function update(Request $request, Area $area)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|unique:areas,code,'. $area->id,
            'name' => 'required|string',
            'address' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request, $area) {
                $area->code = $request->code;
                $area->name = $request->name;
                $area->address = $request->address;
                $area->save();
            });

            Alert::success('Hore', 'Area Updated Successfully');
            return redirect()->route('areas.index');
        } catch (\Throwable $th) {
            Alert::error('Oops!', $th->getMessage());
            return redirect()->route('areas.index');
        }
    }

    public function destroy(Area $area)
    {
        try {
            DB::transaction(function () use ($area) {
                $area->delete();
            });

            Alert::success('Hore!', 'Area Deletion Successfully');
            return redirect()->route('areas.index');
        } catch (\Throwable $th) {
            Alert::error('Oops!', $th->getMessage());
            return redirect()->route('areas.index');
        }
    }
}
