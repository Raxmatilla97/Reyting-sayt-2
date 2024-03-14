<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FormsController extends Controller
{
    public function showForm($tableName)
    {
        $fields = config("forms.{$tableName}");
    
        if (!$fields) {
            abort(404, 'Jadval topilmadi.');
        }
    
        return view('livewire.pages.frontend.forms', compact('fields', 'tableName'));
    }
}
