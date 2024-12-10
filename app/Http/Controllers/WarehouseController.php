<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class WarehouseController extends Controller
{
    public function index(Request $request)
    {
        $warehouses = Warehouse::latest()->get();

        confirmDelete('Delete Data!', 'Are you sure you want to delete?');

        return view('werehouse.index', compact('warehouses'));
    }

    public function show(Warehouse $warehouse)
    {
        return view('werehouse.show', compact('warehouse'));
    }

    public function create(Request $request)
    {
        return view('werehouse.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:warehouses,code',
            'name' => 'required|string',
            'address' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request) {
                $warehouse = new Warehouse();
                $warehouse->code = $request->code;
                $warehouse->name = $request->name;
                $warehouse->address = $request->address;
                $warehouse->save();
            });

            Alert::success('Hore!', 'Warehouse created successfully');
            return redirect()->route('warehouses.index');
        } catch (\Throwable $th) {
            Alert::error('Oops!', $th->getMessage());
            return redirect()->route('warehouses.index');
        }
    }

    public function edit(Warehouse $warehouse)
    {
        return view('werehouse.edit', compact('warehouse'));
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:warehouses,code,' . $warehouse->id,
            'name' => 'required|string',
            'address' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request, $warehouse) {
                $warehouse->code = $request->code;
                $warehouse->name = $request->name;
                $warehouse->address = $request->address;
                $warehouse->save();
            });
            Alert::success('Hore!', 'Warehouse updated successfully!');
            return redirect()->route('warehouses.index');
        } catch (\Throwable $th) {
            Alert::error('Oops!', $th->getMessage());
            return redirect()->route('warehouses.index');
        }
    }

    public function destroy(Warehouse $warehouse)
    {
        try {
            DB::transaction(function () use ($warehouse) {
                $warehouse->delete();
            });
            Alert::success('Hore!', 'Warehouse deleted successfully!');
            return redirect()->route('warehouses.index');
        } catch (\Throwable $th) {
            Alert::error('Oops!', $th->getMessage());
            return redirect()->route('warehouses.index');
        }
    }
}

