<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PointUserDeportament;

class PointUserInformations extends Component
{
    use WithPagination;

    public $modalContent;

    public function render()
    {
        $pointUserInformations = PointUserDeportament::paginate(10);
        return view('livewire.point-user-informations', compact('pointUserInformations'));
    }

    public function showDetails($id)
    {
        $item = PointUserDeportament::findOrFail($id);
        $this->modalContent = view('livewire.partials.item-details', ['item' => $item])->render();
    }

    public function closeModal()
    {
        $this->modalContent = null;
    }
}
