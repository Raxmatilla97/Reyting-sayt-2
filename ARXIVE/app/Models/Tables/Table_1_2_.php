<?php

namespace App\Models\Tables;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table_1_2_ extends Model
{
    use HasFactory;

    protected $table = 'table_1_2_';

    public function points()
    {
        return $this->belongsTo(\App\Models\PointUserDeportament::class, 'table_1_2_id');
    }
}
