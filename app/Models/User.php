<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    // Ruxsat berilgan ustunlar ro'yxati
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
        'status',
        'kpi_review_categories',
        'is_kpi_reviewer',

    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'kpi_review_categories' => 'array',
        'is_kpi_reviewer' => 'boolean',
    ];

    // Relation orqali bog'lanish

    public function getFullNameAttribute()
    {
        return "{$this->second_name} {$this->first_name} {$this->third_name}";
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function isAdmin()
    {
        return $this->is_admin === 1;
    }

    public function kpiSubmissions()
    {
        return $this->hasMany(KpiSubmission::class);
    }

    public function canReviewKpiCategory($category)
    {
        if ($this->is_admin) {
            return true;
        }

        return $this->is_kpi_reviewer &&
            is_array($this->kpi_review_categories) &&
            in_array($category, $this->kpi_review_categories);
    }
}
