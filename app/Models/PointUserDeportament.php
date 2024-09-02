<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;


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
        'table_1_5_1_id',
        'table_1_5_1_a_id',
        'table_1_6_1_id',
        'table_1_6_1_a_id',
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
        'departament_id',
        'year',
        'arizaga_javob',
        'is_admin'


    ];




    protected $relationships = [
        'table_1_1',
        'table_1_2',
        'table_1_3_1',
        'table_1_3_2',
        'table_1_4',
        'table_1_5_1',
        'table_1_5_1_a',
        'table_1_6_1',
        'table_1_6_1_a',
        'table_1_6_2',
        'table_1_7_1',
        'table_1_7_2',
        'table_1_7_3',
        'table_1_9_1',
        'table_1_9_2',
        'table_1_9_3',
        'table_2_2_1',
        'table_2_2_2',
        'table_2_3_1',
        'table_2_3_2',
        'table_2_4_1',
        'table_2_4_2',
        'table_2_4_2_b',
        'table_2_5',
        'table_3_4_1',
        'table_3_4_2',
        'table_4_1',
    ];

    public function getRelationships()
    {
        return $this->relationships;
    }

    public function employee()
{
    return $this->belongsTo(\App\Models\Employee::class, 'user_id');
}

    public function user_ponts()
    {
        return $this->belongsTo(\App\Models\Department::class);
    }

    public function table_1_1()
    {
        return $this->hasMany(\App\Models\Tables\Table_1_1_::class, 'id');
    }

    public function table_1_2()
    {
        return $this->hasMany(\App\Models\Tables\Table_1_2_::class, 'id');
    }


    public function table_1_3_1()
    {
        return $this->hasMany(\App\Models\Tables\Table_1_3_1_::class, 'id');
    }


    public function table_1_3_2()
    {
        return $this->hasMany(\App\Models\Tables\Table_1_3_2_::class, 'id');
    }


    public function table_1_4()
    {
        return $this->hasMany(\App\Models\Tables\Table_1_4_::class, 'id');
    }


    public function table_1_5_1()
    {
        return $this->hasMany(\App\Models\Tables\Table_1_5_1_::class, 'id');
    }

    public function table_1_5_1_a()
    {
        return $this->hasMany(\App\Models\Tables\Table_1_5_1_a_::class, 'id');
    }

    public function table_1_6_1()
    {
        return $this->hasMany(\App\Models\Tables\Table_1_6_1_::class, 'id');
    }

    public function table_1_6_1_a()
    {
        return $this->hasMany(\App\Models\Tables\Table_1_6_1_a_::class, 'id');
    }

    public function table_1_6_2()
    {
        return $this->hasMany(\App\Models\Tables\Table_1_6_2_::class, 'id');
    }


    public function table_1_7_1()
    {
        return $this->hasMany(\App\Models\Tables\Table_1_7_1_::class, 'id');
    }


    public function table_1_7_2()
    {
        return $this->hasMany(\App\Models\Tables\Table_1_7_2_::class, 'id');
    }


    public function table_1_7_3()
    {
        return $this->hasMany(\App\Models\Tables\Table_1_7_3_::class, 'id');
    }


    public function table_1_9_1()
    {
        return $this->hasMany(\App\Models\Tables\Table_1_9_1_::class, 'id');
    }


    public function table_1_9_2()
    {
        return $this->hasMany(\App\Models\Tables\Table_1_9_2_::class, 'id');
    }

    public function table_1_9_3()
    {
        return $this->hasMany(\App\Models\Tables\Table_1_9_3_::class, 'id');
    }

    public function table_2_2_1()
    {
        return $this->hasMany(\App\Models\Tables\Table_2_2_1_::class, 'id');
    }

    public function table_2_2_2()
    {
        return $this->hasMany(\App\Models\Tables\Table_2_2_2_::class, 'id');
    }

    public function table_2_3_1()
    {
        return $this->hasMany(\App\Models\Tables\Table_2_3_1_::class, 'id');
    }

    public function table_2_3_2()
    {
        return $this->hasMany(\App\Models\Tables\Table_2_3_2_::class, 'id');
    }

    public function table_2_4_1()
    {
        return $this->hasMany(\App\Models\Tables\Table_2_4_1_::class, 'id');
    }

    public function table_2_4_2()
    {
        return $this->hasMany(\App\Models\Tables\Table_2_4_2_::class, 'id');
    }

    public function table_2_4_2_b()
    {
        return $this->hasMany(\App\Models\Tables\Table_2_4_2_b_::class, 'id');
    }

    public function table_2_5()
    {
        return $this->hasMany(\App\Models\Tables\Table_2_5_::class, 'id');
    }

    public function table_3_4_1()
    {
        return $this->hasMany(\App\Models\Tables\Table_3_4_1_::class, 'id');
    }

    public function table_3_4_2()
    {
        return $this->hasMany(\App\Models\Tables\Table_3_4_2_::class, 'id');
    }

    public function table_4_1()
    {
        return $this->hasMany(\App\Models\Tables\Table_4_1_::class, 'id');
    }



}
