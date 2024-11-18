<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TemporaryFile;
use App\Models\Department;
use App\Models\PointUserDeportament;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'first_name',
        'second_name',
        'third_name',
        'gender_code',
        'gender_name',
        'birth_date',
        'image',
        'year_of_enter',
        'academicDegree_code',
        'academicDegree_name',
        'academicRank_code',
        'academicRank_name',
        'department_id',
        'login',
        'uuid',
        'employee_id',
        'user_type',
        'phone',
        'employee_id_number',
        'status'

    ];


    public function file()
    {
        return $this->hasMany(TemporaryFile::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function getFullNameAttribute()
    {
        return "{$this->second_name} {$this->first_name} {$this->third_name}";
    }

    public function point_user_deportaments()
    {
        return $this->hasMany(PointUserDeportament::class, 'user_id', 'user_id');
    }


}
