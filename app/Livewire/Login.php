<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use RealRashid\SweetAlert\Facades\Alert;

class Login extends Component
{
    public $email;
    public $password;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    public function submit()
    {
        $this->validate();

        Auth::attempt([
            'email' => $this->email,
            'password' => $this->password,
        ]);

        if (Auth::check()) {
            return $this->redirect(route('dashboard'));
        }

        Alert::error('Oops!', 'Wrong email or password');
        return $this->redirect(route('login'));
    }

    public function render()
    {
        return view('livewire.login');
    }
}
