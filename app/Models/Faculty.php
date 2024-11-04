<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Department;

class Faculty extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $keyType = 'integer';
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
    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    protected $appends = ['totalPoints'];
    protected $totalPoints = 0;

    public function setTotalPointsAttribute($value)
    {
        $this->totalPoints = $value;
    }


    public function getTotalPointsAttribute($value)
    {
        return $value ?? 0;
    }

}
