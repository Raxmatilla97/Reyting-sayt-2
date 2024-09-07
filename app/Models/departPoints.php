<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class departPoints extends Model
{
    use HasFactory;

    public function department()
{
    return $this->belongsTo(Department::class, 'point_user_deport_id', 'id', 'departments')
                ->withDefault();
}
}
