<?php

namespace App\Exports;

use App\Models\Project;
use Maatwebsite\Excel\Concerns\FromView;

// use Maatwebsite\Excel\Concerns\FromCollection;

class ProjectExcel implements FromView
{
    protected $project;
    protected $outboundGoods;

    public function __construct($project, $outboundGoods)
    {
        $this->project = $project;
        $this->outboundGoods = $outboundGoods;
    }
    public function view(): \Illuminate\Contracts\View\View
    {
        return view('projects.print.export', [
            'project' => $this->project,
            'outboundGoods' => $this->outboundGoods
        ]);
    }
}
