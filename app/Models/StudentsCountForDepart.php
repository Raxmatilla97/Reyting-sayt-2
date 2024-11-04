<?php

namespace App\Models;

use App\Models\Department;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentsCountForDepart extends Model
{
    use HasFactory;

    protected $fillable =
    [
        'number',
        'status',
        'departament_id'
    ];



    public function department()
    {
        return $this->belongsTo(Department::class, 'departament_id', 'id');
    }
}
