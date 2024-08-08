<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ItemDetails extends Component
{
    public $showModal = false;
    public $itemDetails;

    protected $listeners = ['showItemDetails'];

    public function showItemDetails($id)
    {
        $this->itemDetails = $this->getItemDetails($id);
        $this->showModal = true;
    }

    private function getItemDetails($id)
    {
        // Bu yerda ma'lumotlar bazasidan yoki boshqa manbadan
        // element ma'lumotlarini olish mantiqini yozing
        // Misol uchun:
        // return Item::find($id);
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.pages.dashboard.item-details');
    }
}
