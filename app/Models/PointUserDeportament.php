<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointUserDeportament extends Model
{
    use HasFactory;
    protected $fillable = 
    [
        'user_id',
        'table_1_1_id',
        'table_1_2_id',
        'table_1_3_1_id',
        'table_1_3_2_id',
        'table_1_4_id',
        'table_1_5_id',
        'table_1_6_1_id',
        'table_1_6_2_id',
        'table_1_9_1_id',
        'table_1_9_2_id',
        'table_1_9_3_id',
        'table_2_2_1_id',
        'table_2_2_2_id',
        'table_2_4_2_id',
        'table_1_7_1_id',
        'table_1_7_2_id',
        'table_1_7_3_id',
        'table_2_3_1_id',
        'table_2_3_2_id',
        'table_2_4_1_id',
        'table_2_4_2_b_id',
        'table_2_5_id',
        'table_3_4_1_id',
        'table_3_4_2_id',
        'table_4_1_id',
        'departament_info',
        'status',
        'temporary_files_id',
        'departament_id'
        

    ];
}
