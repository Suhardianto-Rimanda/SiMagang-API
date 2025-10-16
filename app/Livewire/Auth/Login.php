<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

class Login extends Component
{
    public string $email = '';
    public string $password = '';

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    public function authenticate()
    {
        $this->validate();

        $credentials = [
            'email' => $this->email,
            'password' => $this->password,
        ];

        if (!Auth::attempt($credentials)) {
            $this->addError('email', 'Email atau password yang Anda masukkan salah.');
            return;
        }

        request()->session()->regenerate();

        $user = Auth::user();

        if ($user->role === 'admin') {
            return $this->redirect(route('admin.dashboard'), navigate: true);
        } elseif ($user->role === 'supervisor') {
            // return $this->redirect(route('supervisor.dashboard'), navigate: true);
        }

        return $this->redirect('/', navigate: true);
    }

    // 3. Sederhanakan method render()
    public function render()
    {
        return view('livewire.auth.login');
    }
}