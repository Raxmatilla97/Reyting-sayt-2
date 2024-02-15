<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Department;

class Faculty extends Model
{
    use HasFactory;

    // Ruxsat berilgan ustunlar ro'yxati
    protected $fillable = [
        'name',
        'slug',
        'logo',
        'description',
        'status',
        'custom_points'
    ];

    // Relation orqali bog'lanish
    public function department()
    {
        return $this->hasMany(Department::class);
    }


}
