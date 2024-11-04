<?php

namespace App\Models;

use App\Models\User;
use App\Models\Faculty;
use App\Models\Employee;
use App\Models\PointUserDeportament;
use App\Models\StudentsCountForDepart;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


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

     // Yangi relationship qo'shamiz
     public function students_count()
     {
         return $this->hasOne(StudentsCountForDepart::class, 'departament_id');
     }

}
