<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CustomLogin extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;

    public function render()
    {
        return view('livewire.custom-login');
    }

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();
            
            $user = Auth::user();
            
            if ($user && $user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } else if ($user && $user->role === 'staff') {
                return redirect()->route('staff.dashboard');
            } else {
                return redirect()->route('dashboard');
            }
        }

        return redirect()->route('login');
    }
}
