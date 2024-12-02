<?php

namespace App\Http\Controllers;

use App\Models\ProblemItem;
// use Illuminate\Container\Attributes\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class ProblemItemController extends Controller
{
    public function index()
    {
        $problems = ProblemItem::all();
        return view('goods.problems.index', compact('problems'));
    }

    public function updateWorthy(Request $request, ProblemItem $problemItem)
    {
        // dd($request->all());

        try {
            DB::transaction(function () use ($request, $problemItem) {
                $problemItem->worthy = $request->qty + $problemItem->worthy;
                $problemItem->qty = $problemItem->qty - $request->qty;
                $problemItem->save();

                $problemItem->goods->qty = $problemItem->goods->qty + $request->qty;
                $problemItem->goods->save();
            });

            Alert::success('Hore!', 'Qty Updated Successfully');
            return back();
        } catch (\Throwable $th) {
            Alert::error('Opps!', $th->getMessage());
            return back();
        }
    }
}
