<?php

namespace App\Livewire\Components;

use App\Models\Category;
use App\Models\Goods;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class Select2 extends Component
{
    #[Modelable]
    public $value = null;

    public $name;
    #[Reactive]
    public $options;

    public function mount($name, $options)
    {
        $this->name = $name;
        $this->options = $options;
        // $this->options->ensure([Category::class, Goods::class]);
    }

    public function render()
    {
        return view('livewire.components.select2');
    }
}

