<?php

namespace App\Http\Controllers;

use App\Models\Inbound;
use App\Models\Project;
use App\Models\Outbound;
use App\Models\InboundItem;
use Illuminate\Http\Request;
use App\Exports\ProjectExcel;
use App\Serverces\GenerateCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function index()
    {
        if (Auth::user()->roles[0]->name == 'Admin Engineer') {
            $projects = Project::where('user_id', Auth::user()->id)->latest()->get();
        } else {
            $projects = Project::latest()->get();
        }

        return view('projects.index', compact('projects'));
    }

    public function showOutbound(Outbound $outbound)
    {
        $inboundItems = $outbound->inbound()->where('is_return', 0)->get()->flatMap->items;

        $inboundItemsProblems = $outbound->inbound()->where('is_return', 1)->get()->flatMap->items;

        $inboundProblem = $outbound->inbound()->where('is_return', 1)->first();
        // return response()->json($outbound->inbound);
        $outbounItemsdResend = $inboundProblem ? Outbound::where('code_inbound', $inboundProblem->code)
            ->where('is_resend', 1)
            ->get()
            ->flatMap->items : [];

        // dd($inboundItemsProblems);
        return view('projects.outbound.show', compact('outbound', 'inboundItemsProblems', 'outbounItemsdResend', 'inboundItems'));
    }

    public function export(Project $project)
    {
        $outboundGoods = [];

        foreach ($project->outbounds as $outbound) {
            foreach ($outbound->items as $item) {
                $key = $item->goods->code;
                if (array_key_exists($key, $outboundGoods)) {
                    $outboundGoods[$key]['qty'] += $item->qty;
                } else {
                    $outboundGoods[$key] = [
                        'code' => $item->goods->code,
                        'name' => $item->goods->name,
                        'qty' => $item->qty,
                        'symbol' => $item->goods->unit->symbol,
                        'type' => $item->goods->type
                    ];
                }
            }
        }

        return Excel::download(new ProjectExcel($project, $outboundGoods), 'Project Detail '.$project->code.'.xlsx');
    }

    public function print(Project $project)
    {
        $outboundGoods = [];

        foreach ($project->outbounds as $outbound) {
            foreach ($outbound->items as $item) {
                $key = $item->goods->code;
                if (array_key_exists($key, $outboundGoods)) {
                    $outboundGoods[$key]['qty'] += $item->qty;
                } else {
                    $outboundGoods[$key] = [
                        'code' => $item->goods->code,
                        'name' => $item->goods->name,
                        'qty' => $item->qty,
                        'symbol' => $item->goods->unit->symbol,
                        'type' => $item->goods->type
                    ];
                }
            }
        }
        $pdf = Pdf::loadView('projects.print.pdf', ['project' => $project, 'outboundGoods' => $outboundGoods]);

        return $pdf->stream('Project Detail '.$project->code.'.pdf');
    }

    public function printOutbound(Outbound $outbound)
    {
        $inboundItems = $outbound->inbound()->where('is_return', 0)->get()->flatMap->items;

        $inboundItemsProblems = $outbound->inbound()->where('is_return', 1)->get()->flatMap->items;

        $inboundProblem = $outbound->inbound()->where('is_return', 1)->first();
        // return response()->json($outbound->inbound);
        $outbounItemsdResend = $inboundProblem ? Outbound::where('code_inbound', $inboundProblem->code)
            ->where('is_resend', 1)
            ->get()
            ->flatMap->items : array();

        $pdf = Pdf::loadView('projects.print.pdf-outbound', ['outbound' => $outbound, 'inboundItemsProblems' => $inboundItemsProblems, 'outbounItemsdResend' => $outbounItemsdResend, 'inboundItems' => $inboundItems]);

        return $pdf->stream('Outbound Detail'.$outbound->code.'.pdf');
    }

    public function resend(Outbound $outbound)
    {
        $items = Inbound::where('outbound_id', $outbound->id)->where('is_return', 1)->get()->flatMap->items;
        $generateCOde = new GenerateCode();
        try {
            DB::transaction(function () use ($outbound, $items, $generateCOde) {
                $new_outbound = Outbound::create([
                    'code' => 'OUT-R-' . date('Ymd') . str_pad($outbound->outbounds()->count() + 1, 4, '0', STR_PAD_LEFT),
                    'project_id' => $outbound->project_id,
                    'user_id' => Auth::user()->id,
                    'date' => now(),

                ]);
            });
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function show(Project $project)
    {
        // $outboundGoods = $project->outbounds->flatMap(function ($outbound) {
        //     return $outbound->items->map(function ($item) {
        //         return [
        //             'code' => $item->goods->code,
        //             'name' => $item->goods->name,
        //             'qty' => $item->qty,
        //             'symbol' => $item->goods->unit->symbol,
        //             'type'=> $item->goods->type
        //         ];
        //     });
        // })->values();

        $outboundGoods = [];

        foreach ($project->outbounds as $outbound) {
            foreach ($outbound->items as $item) {
                $key = $item->goods->code;
                if (array_key_exists($key, $outboundGoods)) {
                    $outboundGoods[$key]['qty'] += $item->qty;
                } else {
                    $outboundGoods[$key] = [
                        'code' => $item->goods->code,
                        'name' => $item->goods->name,
                        'qty' => $item->qty,
                        'symbol' => $item->goods->unit->symbol,
                        'type' => $item->goods->type
                    ];
                }
            }
        }

        // return response()->json($outboundGoods);
        return view('projects.show', compact('project', 'outboundGoods'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|unique:projects,code',
            'name' => 'required|string',
            'address' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request) {
                $project = new Project();
                $project->code = $request->code;
                $project->name = $request->name;
                $project->address = $request->address;
                $project->user_id = Auth::user()->id;
                $project->save();
            });

            Alert::success('Hore!', 'Project created successfully!');
            return redirect()->route('projects.index');
        } catch (\Throwable $th) {
            Alert::error('Oops!', $th->getMessage());
            return redirect()->route('projects.index');
        }
    }

    public function return(Project $project)
    {
        $generateCode = new GenerateCode();
        $code = $generateCode->make(Inbound::count(), 'IN');
        return view('projects.return', compact('project', 'code'));
    }

    public function storeReturn(Request $request, Project $project)
    {
        // dd($request->all());
        try {
            DB::transaction(function () use ($request, $project) {
                $inbound = new Inbound();
                $inbound->date = $request->date;
                $inbound->code = $request->code;
                $inbound->project_id = $project->id;
                $inbound->outbound_id = $request->outbound_id;
                // $inbound->vendor_id = $request->vendor_id;
                $inbound->user_id = Auth::user()->id;
                // $inbound->sender_name = $request->sender_name;
                // $inbound->vehicle_number = $request->vehicle_number;
                $inbound->description = $request->description;
                $inbound->status = 'Pending';
                $inbound->save();

                $outbound = Outbound::where('id',$request->outbound_id)->with('items')->first();

                // dd($outbound);

                foreach ($outbound->items as $item) {
                    if ($item->goods->type == 'Rentable') {
                        $inboundItem = new InboundItem();
                        $inboundItem->inbound_id = $inbound->id;
                        $inboundItem->goods_id = $item->goods_id;
                        $inboundItem->qty = $item->qty;
                        $inboundItem->save();
                    }
                }
            });

            Alert::success('Hore!', 'Return Created Successfully');
            return redirect()->route('projects.show', $project);
        } catch (\Throwable $th) {
            Alert::error('Oops!', $th->getMessage());
            return redirect()->route('projects.show', $project);
        }
    }
}
