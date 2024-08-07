<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Faculty;
use App\Models\User;
use App\Models\Employee;
use App\Models\PointUserDeportament;


class Department extends Model
{
    use HasFactory;

    // Ruxsat berilgan ustunlar ro'yxati
    protected $fillable = [
        'name',
        'slug',
        'logo',
        'description',
        'status',
        'custom_points',
        'faculty_id',
    ];

    // Relation orqali bog'lanish
    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function user()
    {
        return $this->hasMany(User::class);
    }

    public function employee()
    {
        return $this->hasMany(Employee::class);
    }

    public function point_user_deportaments()
    {
        return $this->hasMany(PointUserDeportament::class, 'departament_id');
    }

}
