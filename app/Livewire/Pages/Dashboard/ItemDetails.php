<?php

namespace App\Livewire\Pages\Dashboard;

use Livewire\Component;
use App\Models\PointUserDeportament;

class ItemDetails extends Component
{
    public $item;

    public function mount($itemId)
    {
        $this->item = PointUserDeportament::find($itemId);
    }

    public function render()
    {
        return view('livewire.pages.dashboard.item-details');
    }

    public function closeModal()
    {
        $this->dispatchBrowserEvent('closeModal');
    }
}
