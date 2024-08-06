<?php

namespace App\Livewire;

use Livewire\Component;

class Layout extends Component
{
    public function mount()
    {
        if (request()->is('my-submitted-information')) {
            $this->dispatch('refresh-page');
        }
    }

    public function render()
    {
        return view('livewire.layout');
    }
}


