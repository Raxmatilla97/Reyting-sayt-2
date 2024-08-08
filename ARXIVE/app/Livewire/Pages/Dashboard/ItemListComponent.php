<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\PointUserDeportament;
use Illuminate\Support\Facades\Config;
use Livewire\WithPagination;

class MySubmittedInformation extends Component
{

    public function mount()
{
    if (request()->is('my-submitted-information')) {
        $this->redirect(request()->fullUrl());
    }
}
}
