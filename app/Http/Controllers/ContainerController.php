<?php

namespace App\Http\Controllers;

use App\Models\Container;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class ContainerController extends Controller
{
    public function index()
    {
        return view('containers.index');
    }

    public function show(Container $container)
    {
        return view('containers.show', compact('container'));
    }

    public function create(Warehouse $warehouse)
    {
        return view('containers.create', compact('warehouse'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:containers,code',
            'name' => 'required|string',
            'description' => 'required|string',
            'warehouse_id' => 'required|exists:warehouses,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request) {
                $container = new Container();
                $container->code = $request->code;
                $container->name = $request->name;
                $container->description = $request->description;
                $container->warehouse_id = $request->warehouse_id;
                $container->save();
            });

            Alert::success('Hore!', 'Container Created Successfully');
            return redirect()->route('warehouses.show', $request->warehouse_id);
        } catch (\Throwable $th) {
            Alert::error('Oops!', $th->getMessage());
            return redirect()->route('warehouses.show', $request->warehouse_id);
        }
    }
}
