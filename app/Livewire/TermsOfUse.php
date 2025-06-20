<?php

namespace App\Livewire;

use Livewire\Component;

class TermsOfUse extends Component
{
    public function render()
    {
        return view('livewire.terms-of-use')
            ->layout('layouts.app', ['title' => '使用條款 - 美甲預約系統']);
    }
}