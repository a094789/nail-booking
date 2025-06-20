<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class TimeSlotManagement extends Component
{
    public function render()
    {
        return view('livewire.admin.time-slot-management')
            ->layout('layouts.app');
    }
}
