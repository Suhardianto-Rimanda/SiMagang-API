<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Intern;
use App\Models\Supervisor;

class Dashboard extends Component
{
    public $totalInterns;
    public $totalSupervisors;

    public function mount()
    {
        // Method mount() dijalankan saat komponen pertama kali di-load
        $this->totalInterns = Intern::count();
        $this->totalSupervisors = Supervisor::count();
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}