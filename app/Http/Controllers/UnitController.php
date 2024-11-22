<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::latest()->paginate(10);

        confirmDelete('Delete Data!', 'Are you sure you want to delete?');

        return view('units.index', compact('units'));
    }

    public function create()
    {
        return view('units.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'symbol' => 'required|string',
            'description' => 'required|string',

        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request) {
                $unit = new Unit();
                $unit->name = $request->name;
                $unit->symbol = $request->symbol;
                $unit->description = $request->description;
                $unit->save();
            });

            Alert::success('Hore!', 'unit Created Successfully');
            return redirect()->route('units.index');
        } catch (\Throwable $th) {
            Alert::error('unit Creation Failed', $th->getMessage());
            return redirect()->route('units.index');
        }
    }

    public function edit(Unit $unit)
    {
        return view('units.edit', compact('unit'));
    }

    public function update(Request $request, Unit $unit)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'symbol' => 'required|string',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request, $unit) {
                $unit->name = $request->name;
                $unit->symbol = $request->symbol;
                $unit->description = $request->description;
                $unit->save();
            });

            Alert::success('Hore!', 'Unit Updated Successfully');
            return redirect()->route('units.index');
        } catch (\Throwable $th) {
            Alert::error('Unit Update Failed', $th->getMessage());
            return redirect()->route('units.index');
        }
    }

    public function destroy(Unit $unit)
    {
        try {
            DB::transaction(function () use ($unit) {
                $unit->delete();
            });

            Alert::success('Hore!', 'Unit Deletion Successfully');

            return back();
        } catch (\Throwable $th) {
            Alert::error('Unit deletion failed', $th->getMessage());
            return redirect()->route('units.index');
        }
    }
}
