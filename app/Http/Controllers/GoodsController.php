<?php

namespace App\Http\Controllers;

use App\Models\Goods;
use App\Models\Category;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class GoodsController extends Controller
{
    public function index(Request $request)
    {
        $goods = Goods::latest()->paginate(10);

        confirmDelete('Delete Data!', 'Are you sure you want to delete?');

        return view('goods.index', compact('goods'));
    }

    public function create()
    {
        $warehouses = Warehouse::all();
        $categories = Category::all();

        return view('goods.create', compact('warehouses', 'categories'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $validator = Validator::make($request->all(), [
           'name' => 'required|string',
           'sk' => 'required|string',
           'category_id' => 'required',
           'warehouse_id' => 'required',
           'price' => 'required|numeric',
           'qty' => 'required|numeric',
           'description' => 'nullable|string',
           'condition' => 'nullable|string',
           'length' => 'nullable',
           'width' => 'nullable',
           'height' => 'nullable',
           'weight' => 'nullable',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request) {
                $goods = new Goods();
                $goods->name = $request->name;
                $goods->sk = $request->sk;
                $goods->category_id = $request->category_id;
                $goods->warehouse_id = $request->warehouse_id;
                $goods->price = $request->price;
                $goods->qty = $request->qty;
                $goods->description = $request->description;
                $goods->condition = $request->condition;
                $goods->length = $request->length;
                $goods->width = $request->width;
                $goods->height = $request->height;
                $goods->weight = $request->weight;
                $goods->user_id = Auth::user()->id;
                $goods->save();
            });

            Alert::success('Hore!', 'Good Created Successfully');
            return redirect()->route('goods.index');
        } catch (\Throwable $th) {
            Alert::error('Oops!', $th->getMessage());
            return redirect()->route('goods.index');
        }
    }

    public function edit(Goods $goods)
    {
        $warehouses = Warehouse::all();
        $categories = Category::all();

        return view('goods.edit', compact('goods', 'warehouses', 'categories'));
    }

    public function update(Request $request, Goods $goods)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'sk' => 'required|string|unique:goods,sk,' . $goods->id,
            'category_id' => 'required',
            'warehouse_id' => 'required',
            'price' => 'required|numeric',
            'qty' => 'required|numeric',
            'description' => 'nullable|string',
            'condition' => 'nullable|string',
            'length' => 'nullable',
            'width' => 'nullable',
            'height' => 'nullable',
            'weight' => 'nullable',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request, $goods) {
                $goods->name = $request->name;
                $goods->sk = $request->sk;
                $goods->category_id = $request->category_id;
                $goods->warehouse_id = $request->warehouse_id;
                $goods->price = $request->price;
                $goods->qty = $request->qty;
                $goods->description = $request->description;
                $goods->condition = $request->condition;
                $goods->length = $request->length;
                $goods->width = $request->width;
                $goods->height = $request->height;
                $goods->weight = $request->weight;
                $goods->user_id = Auth::user()->id;
                $goods->save();
            });

            Alert::success('Hore!', 'Good Updated Successfully');
            return redirect()->route('goods.index');
        } catch (\Throwable $th) {
            Alert::error('Oops!', $th->getMessage());
            return redirect()->route('goods.index');
        }

    }

    public function destroy(Goods $goods)
    {
        try {
            DB::transaction(function () use ($goods) {
                $goods->delete();
            });

            Alert::success('Hore!', 'Good Deleted Successfully');
            return redirect()->route('goods.index');
        } catch (\Throwable $th) {
            Alert::error('Oops!', $th->getMessage());
            return redirect()->route('goods.index');
        }
    }
}
