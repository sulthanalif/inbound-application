<?php

namespace App\Livewire;

use App\Models\Project;
use Livewire\Component;
use App\Models\Category;
use App\Models\Goods;
use App\Models\Outbound;
use Illuminate\Support\Facades\Auth;

class CreateRequest extends Component
{
    public $date;
    public $code_outbound;
    public $categories = [];
    public $goods = [];

    public $selectedCategory = null;
    public $selectedGoods = null;
    // public $selectGoods = false;
    public $project_id;
    public $payment;

    public function mount()
    {
        // $this->categories = Category::all();

        $this->code_outbound = 'OUT' . date('Ymd') . Outbound::count() . rand(1000, 9999);
        $this->date = date('Y-m-d');
    }

    // public function updatedSelectedCategory($category)
    // {
    //     $this->reset('selectGoods');
    //     $this->goods = Goods::where('category_id', $category)->get();
    //     $this->selectGoods = true;
    // }

    public function render()
    {
        $projects = Auth::user()->roles[0]->name == 'Super Admin' ? Project::all() : Project::where('user_id', Auth::user()->id)->get();
        $categories = Category::all();

        return view('livewire.create-request', compact('projects', 'categories'));
    }

    public function store()
    {
        // Implement your store logic here
    }
}

