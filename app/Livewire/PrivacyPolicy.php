<?php

namespace App\Livewire;

use Livewire\Component;

class PrivacyPolicy extends Component
{
    public function render()
    {
        return view('livewire.privacy-policy')
            ->layout('layouts.app', ['title' => '隱私權政策 - 美甲預約系統']);
    }
}