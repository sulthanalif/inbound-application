<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Warehouse;
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
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'warehouse_id' => 'required|exists:warehouses,id',
            'code' => 'required|string|unique:areas,code',
            'name' => 'required|string',
            'container' => 'nullable|string',
            'rack' => 'nullable|string',
            'number' => 'nullable|string',
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
                $area->container = $request->container;
                $area->rack = $request->rack;
                $area->number = $request->number;
                $area->save();
            });

            Alert::success('Hore!', 'Area Created Successfully');
            return redirect()->route('warehouses.show', $request->warehouse_id);
        } catch (\Throwable $th) {
            Log::channel('debug')->error("message: '{$th->getMessage()}',  file: '{$th->getFile()}',  line: {$th->getLine()}");
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
            'code' => 'required|string|unique:areas,code,' . $area->id,
            'name' => 'required|string',
            'container' => 'nullable|string',
            'rack' => 'nullable|string',
            'number' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request, $area) {
                $area->code = $request->code;
                $area->name = $request->name;
                $area->container = $request->container;
                $area->rack = $request->rack;
                $area->number = $request->number;
                $area->save();
            });

            Alert::success('Hore', 'Area Updated Successfully');
            return redirect()->route('warehouses.show', $area->warehouse_id);
        } catch (\Throwable $th) {
            Log::channel('debug')->error("message: '{$th->getMessage()}',  file: '{$th->getFile()}',  line: {$th->getLine()}");
            return redirect()->route('warehouses.show', $area->warehouse_id);
        }
    }

    public function destroy(Area $area)
    {
        try {
            DB::transaction(function () use ($area) {
                $area->delete();
            });

            Alert::success('Hore!', 'Area Deletion Successfully');
            return redirect()->route('warehouses.show', $area->warehouse_id);
        } catch (\Throwable $th) {
            Log::channel('debug')->error("message: '{$th->getMessage()}',  file: '{$th->getFile()}',  line: {$th->getLine()}");
            return redirect()->route('warehouses.show', $area->warehouse_id);
        }
    }
}

