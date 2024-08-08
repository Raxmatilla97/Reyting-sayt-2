<?php

namespace App\Models\Tables;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table_1_7_1_ extends Model
{
    use HasFactory;

    public function points()
    {
        return $this->belongsTo(\App\Models\PointUserDeportament::class, 'table_1_7_1_id');
    }
}
