<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class departPoints extends Model
{
    use HasFactory;
    protected $fillable = ['point_user_deport_id', 'point', 'status'];

    public function department()
    {
        return $this->belongsTo(Department::class, 'point_user_deport_id', 'id', 'departments')
            ->withDefault();
    }
    public function pointUserDeportament()
    {
        return $this->belongsTo(PointUserDeportament::class, 'point_user_deport_id', 'id');
    }
}
