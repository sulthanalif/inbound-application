<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\UserWarehouse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

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

    public function create()
    {
        $admins = User::role('Admin Warehouse')->get();

        return view('werehouse.create', compact('admins'));
    }

    public function store(Request $request)
    {
        $data = json_decode($request->input('data'), true);

        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:warehouses,code',
            'name' => 'required|string',
            'address' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request, $data) {
                $warehouse = new Warehouse();
                $warehouse->code = $request->code;
                $warehouse->name = $request->name;
                $warehouse->address = $request->address;
                $warehouse->save();

                if ($data) {
                    foreach ($data as $admin) {
                        $addAdmin = new UserWarehouse();
                        $addAdmin->user_id = $admin['admin_id'];
                        $addAdmin->warehouse_id = $warehouse->id;
                        $addAdmin->save();
                    }

                    activity('user_warehouse')
                        ->causedBy(Auth::user())
                        ->withProperties([
                            'warehouse' => $warehouse->name,
                            'admin_id' => UserWarehouse::where('warehouse_id', $warehouse->id)->pluck('user_id')->toArray(),
                        ])
                        ->log('created');
                }
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
        $admins = User::role('Admin Warehouse')->get();
        return view('werehouse.edit', compact('warehouse', 'admins'));
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $data = json_decode($request->input('data'), true);
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:warehouses,code,' . $warehouse->id,
            'name' => 'required|string',
            'address' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request, $warehouse, $data) {
                $warehouse->code = $request->code;
                $warehouse->name = $request->name;
                $warehouse->address = $request->address;
                $warehouse->save();


                if ($data) {
                    foreach ($data as $admin) {
                        $userWarehouse = UserWarehouse::where('warehouse_id', $warehouse->id)->where('user_id', $admin['admin_id'])->first();
                        if (!$userWarehouse) {
                            $addAdmin = new UserWarehouse();
                            $addAdmin->user_id = $admin['admin_id'];
                            $addAdmin->warehouse_id = $warehouse->id;
                            $addAdmin->save();
                        }
                    }

                    $existingAdmins = UserWarehouse::where('warehouse_id', $warehouse->id)->pluck('user_id')->toArray();
                    $incomingAdmins = array_column($data, 'admin_id');

                    if (array_diff_assoc($existingAdmins, $incomingAdmins) || array_diff_assoc($incomingAdmins, $existingAdmins)) {
                        activity('user_warehouse')
                            ->causedBy(Auth::user())
                            ->withProperties([
                                'warehouse' => $warehouse->name,
                                'admin_id' => UserWarehouse::where('warehouse_id', $warehouse->id)->whereIn('user_id', $data)->pluck('user_id')->toArray(),
                                'admin_id_remove' => UserWarehouse::where('warehouse_id', $warehouse->id)->whereNotIn('user_id', $data)->pluck('user_id')->toArray(),
                                ])
                            ->log('updated');
                    }

                    $deleteAdmin = UserWarehouse::where('warehouse_id', $warehouse->id)->whereNotIn('user_id', $data);
                    if ($deleteAdmin) {
                        $deleteAdmin->delete();
                    }
                }
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

