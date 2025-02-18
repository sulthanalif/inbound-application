<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Inbound;
use App\Models\Project;
use App\Models\Outbound;
use App\Models\InboundItem;
use Illuminate\Http\Request;
use App\Exports\ProjectExcel;
use App\Models\Goods;
use App\Serverces\GenerateCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

        // confirmDelete('End Project?', 'Are you sure you want to end this project?');

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

        return Excel::download(new ProjectExcel($project, $outboundGoods), 'Project Detail ' . str_replace(['/', '\\'], '-', $project->code) . '.xlsx');
    }

    public function print(Project $project)
    {
        $outboundGoods = [];

        foreach ($project->outbounds as $outbound) {
            foreach ($outbound->items as $item) {
                $key = $item->goods->code;
                if (array_key_exists($key, $outboundGoods)) {
                    $outboundGoods[$key]['qty'] += $item->qty;
                    $outboundGoods[$key]['req'] += $item->qty;
                } else {
                    $outboundGoods[$key] = [
                        'code' => $item->goods->code,
                        'name' => $item->goods->name,
                        'req' => $item->qty,
                        'qty' => $item->qty,
                        'symbol' => $item->goods->unit->symbol,
                        'type' => $item->goods->type
                    ];
                }
            }
        }
        foreach ($project->inbounds as $inbound) {
            foreach ($inbound->items as $item) {
                $key = $item->goods->code;
                if (array_key_exists($key, $outboundGoods)) {
                    $outboundGoods[$key]['qty'] -= $item->qty;
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

        $filename = 'Project Detail ' . str_replace(['/', '\\'], '-', $project->code) . '.pdf';

        return $pdf->stream($filename);
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

        return $pdf->stream('Outbound Detail' . $outbound->code . '.pdf');
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
                    'user_id' => $outbound->user_id,
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

        $outbounds = $project->outbounds()->where('status', 'Success')->get();
        foreach ($outbounds as $outbound) {
            if ($outbound->is_resend == 0) {
                foreach ($outbound->items as $item) {
                    $key = $item->goods->code;
                    if (array_key_exists($key, $outboundGoods)) {
                        $outboundGoods[$key]['qty'] += $item->qty;
                        $outboundGoods[$key]['req'] += $item->qty;
                    } else {
                        $outboundGoods[$key] = [
                            'code' => $item->goods->code,
                            'name' => $item->goods->name,
                            'req' => $item->qty,
                            'qty' => $item->qty,
                            'symbol' => $item->goods->unit->symbol,
                            'type' => $item->goods->type
                        ];
                    }
                }
            } else {
                foreach ($outbound->items as $item) {
                    $key = $item->goods->code;
                    if (array_key_exists($key, $outboundGoods)) {
                        $outboundGoods[$key]['qty'] += $item->qty;
                        // $outboundGoods[$key]['req'] += $item->qty;
                    }
                }
            }
        }

        foreach ($project->inbounds->where('status', 'Success') as $inbound) {
            foreach ($inbound->items as $item) {
                $key = $item->goods->code;
                if (array_key_exists($key, $outboundGoods)) {
                    $outboundGoods[$key]['qty'] -= $item->qty;
                }
            }
        }

        $end = false;
        $next = false;

        $cek = '';

        $dataInbounds = $project->inbounds();
        $dataOutbounds = $project->outbounds();

        $isReturnable = $dataOutbounds->get()->flatMap->items->pluck('goods.type')->contains('Rentable');

        $isReturnableSuccess = $dataOutbounds->get()->contains(function ($outbound) {
            return $outbound->items->pluck('goods.type')->contains('Rentable') && $outbound->inbound && $outbound->inbound->status === 'Success';
        });

        $isReturnableWaiting = $dataOutbounds->get()->contains(function ($outbound) {
            return $outbound->inbound()->where('is_return', 1)->exists();
        });

        $isProblem = $dataInbounds->get()->flatMap->pluck('is_return')->contains(1);

        $isProblemSuccess = $dataInbounds->where('is_return', 1)->get()->every(function ($inbound) use ($project) {
            return $project->outbounds()->where('code_inbound', $inbound->code)->first() ? $project->outbounds()->where('code_inbound', $inbound->code)->first()->status === 'Success' : false;
        });


        $isOutboundSuccess = $project->inbounds()->get()->every(function ($outbound) {
            return $outbound->status === 'Success';
        });

        $isInboundSuccess = $project->inbounds()->get()->every(function ($inbound) {
            return $inbound->status === 'Success';
        });

        if ($isOutboundSuccess && $isInboundSuccess) {
            if ($isReturnable) {
                if ($isReturnableSuccess) {
                    $end = true;
                    $cek = 'ok 1';
                } else {
                    $next = true;
                    $cek = 'ok 2';
                }
                if ($isProblem) {
                    if ($isProblemSuccess) {
                        $end = ($isReturnableSuccess ? true : false);
                        $cek = 'ok 3';
                    } else {
                        $end = false;
                        $cek = 'ok 4';
                    }
                }
            } else {
                if ($isProblem) {
                    if ($isProblemSuccess) {
                        $end = true;
                        $cek = 'ok 5';
                    } else {
                        $end = false;
                        $cek = 'ok 6';
                    }
                }

            }
        }


        if (Auth::user()->roles[0]->name == 'Admin Engineer') {
            $projects = Project::where('user_id', Auth::user()->id)
                ->where('status', '!=', 'Finished')
                ->where('id', '!=', $project->id)->latest()->get();
        } else {
            $projects = Project::where('status', '!=', 'Finished')
                ->where('id', '!=', $project->id)->latest()->get();
        }

        // return response()->json($isReturnableSuccess);

        return view('projects.show', compact('isReturnable', 'project', 'outboundGoods', 'end', 'next', 'projects'));
    }

    public function create()
    {
        if (Auth::user()->roles[0]->name == 'Super Admin') {
            $users = User::with('roles')->whereHas('roles', function ($query) {
                $query->where('name', 'Admin Engineer');
            })->get();
            return view('projects.create', compact('users'));
        }
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|unique:projects,code',
            'name' => 'required|string',
            'address' => 'required|string',
        ]);

        if (Auth::user()->roles[0]->name == 'Super Admin') {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer|exists:users,id',
            ]);
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request) {
                $project = new Project();
                $project->code = $request->code;
                $project->name = $request->name;
                $project->address = $request->address;
                $project->user_id = $request->user_id ?? Auth::user()->id;
                $project->save();
            });

            Alert::success('Hore!', 'Project created successfully!');
            return redirect()->route('projects.index');
        } catch (\Throwable $th) {
            Log::channel('debug')->error("message: '{$th->getMessage()}',  file: '{$th->getFile()}',  line: {$th->getLine()}");
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

                $outbound = Outbound::where('id', $request->outbound_id)->with('items')->first();
                $outbound->is_return = true;
                $outbound->save();

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
            Log::channel('debug')->error("message: '{$th->getMessage()}',  file: '{$th->getFile()}',  line: {$th->getLine()}");
            return redirect()->route('projects.show', $project);
        }
    }

    public function endProject(Project $project)
    {
        $end = true;

        $isReturnable = $project->outbounds->flatMap->items->pluck('goods.type')->contains('Rentable')
            && $project->outbounds()->where('is_return', true)->get()->every(function ($outbound) {
                return $outbound->inbound->status === 'Success';
            });

        if ($project->outbounds->flatMap->items->pluck('goods.type')->contains('Rentable')) {
            if(!$isReturnable){
                $end = false;
            }
        }

        if ($end) {
            try {
                DB::beginTransaction();
                $project->status = 'Finished';
                $project->end_date = now();
                $project->save();
                DB::commit();

                Alert::success('Hore!', 'Project Finished Successfully');
                return redirect()->route('projects.show', $project);
            } catch (\Throwable $th) {
                DB::rollBack();
                Log::channel('debug')->error("message: '{$th->getMessage()}',  file: '{$th->getFile()}',  line: {$th->getLine()}");
                return redirect()->route('projects.show', $project);
            }
        } else {
            Alert::error('Oh no!', 'Project Finished Failed');
            return redirect()->route('projects.show', $project);
        }
    }

    public function nextProject(Project $project, Request $request)
    {

        // return response()->json($request->all());

        $outboundGoods = [];

        $outbounds = $project->outbounds()->where('status', 'Success')->get();
        foreach ($outbounds as $outbound) {
            if ($outbound->is_resend == 0) {
                foreach ($outbound->items as $item) {
                    $key = $item->goods->code;
                    if (array_key_exists($key, $outboundGoods)) {
                        $outboundGoods[$key]['qty'] += $item->qty;
                        $outboundGoods[$key]['req'] += $item->qty;
                    } else {
                        $outboundGoods[$key] = [
                            'code' => $item->goods->code,
                            'name' => $item->goods->name,
                            'req' => $item->qty,
                            'qty' => $item->qty,
                            'symbol' => $item->goods->unit->symbol,
                            'type' => $item->goods->type
                        ];
                    }
                }
            } else {
                foreach ($outbound->items as $item) {
                    $key = $item->goods->code;
                    if (array_key_exists($key, $outboundGoods)) {
                        $outboundGoods[$key]['qty'] += $item->qty;
                        // $outboundGoods[$key]['req'] += $item->qty;
                    }
                }
            }
        }

        foreach ($project->inbounds->where('status', 'Success') as $inbound) {
            foreach ($inbound->items as $item) {
                $key = $item->goods->code;
                if (array_key_exists($key, $outboundGoods)) {
                    $outboundGoods[$key]['qty'] -= $item->qty;
                }
            }
        }

        $items = [];

        foreach ($outboundGoods as $key => $value) {
            if ($value['type'] == 'Rentable' && $value['qty'] > 0) {
                $items[] = [
                    'code' => $value['code'],
                    'name' => $value['name'],
                    'qty' => $value['qty'],
                    'symbol' => $value['symbol'],
                    'type' => $value['type']
                ];
            }
        }

        try{
            DB::beginTransaction();

            $project->status = 'Finished';
            $project->end_date = $request->date;
            $project->save();

            if ($request->project_id == 'new') {
                $project_new = new Project();
                $project_new->user_id = $project->user_id;
                $project_new->name = $request->name_new;
                $project_new->code = $request->code_new;
                $project_new->start_date = now();
                $project_new->address = $request->address_new;
                $project_new->save();
            } else {
                $project_new = Project::find($request->project_id);
            }

            $generateCode = new GenerateCode();
            $code_outbound = $generateCode->make(Outbound::count(), 'OUT');

            $outbound = $project_new->outbounds()->create([
                'project_id' => $project_new->id,
                'user_id' => Auth::user()->id,
                'date' => $request->date,
                'code' => $code_outbound,
                'description' => $request->description_new,
                'move_from' => $project->code,
                'status' => 'Pending',
            ]);

            foreach ($items as $item) {
                $i = Goods::where('code', $item['code'])->first();
                $outbound->items()->create([
                    'goods_id' => $i->id,
                    'qty' => $item['qty'],
                ]);
            }

            DB::commit();

            Alert::success('Hore!', 'Project Finished Successfully');
            return redirect()->route('projects.show', $project_new);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::channel('debug')->error("message: '{$th->getMessage()}',  file: '{$th->getFile()}',  line: {$th->getLine()}");
            return redirect()->route('projects.show', $project);

        }

    }
}
