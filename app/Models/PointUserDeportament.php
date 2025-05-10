<?php

namespace App\Models;

use App\Models\Department;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PointUserDeportament extends Model
{
    use HasFactory;

    // Ruxsat etilgan columnlar ro'yxati
    protected $fillable =
    [
        'user_id',
        'table_1_1_id',
        'table_1_2_id',
        'table_1_3_1_a_id',
        'table_1_3_1_b_id',
        'table_1_3_2_a_id',
        'table_1_3_2_b_id',
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
        'departament_id',
        'year',
        'arizaga_javob',
        'is_admin'


    ];

    // Relationlarni nomli ro'yxati
    protected $relationships = [
        'table_1_1',
        'table_1_2',
        'table_1_3_1_a',
        'table_1_3_1_b',
        'table_1_3_2_a',
        'table_1_3_2_b',
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

    // Bu modeldan biron item o'chirilganda unga tegishli bosha tabledagi item ham o'chadi!
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($pointUserDeportament) {
            foreach ($pointUserDeportament->getRelationships() as $relationship) {
                $relatedModels = $pointUserDeportament->$relationship;
                if ($relatedModels instanceof Model) {
                    $relatedModels->delete();
                } elseif ($relatedModels instanceof \Illuminate\Database\Eloquent\Collection) {
                    foreach ($relatedModels as $model) {
                        $model->delete();
                    }
                }
            }
        });
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'departament_id');
    }

    public function getRelationships()
    {
        return $this->relationships;
    }

    public function departPoint()
    {
        return $this->hasOne(\App\Models\DepartPoints::class, 'point_user_deport_id');
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
        return $this->hasOne(\App\Models\Tables\table_2_::class, 'id', 'table_1_1_id');
    }

    public function table_1_2()
    {
        return $this->hasOne(\App\Models\Tables\table_3_::class, 'id', 'table_1_2_id');
    }

    public function table_1_3_1_a()
    {
        return $this->hasOne(\App\Models\Tables\table_4_::class, 'id', 'table_1_3_1_a_id');
    }

    public function table_1_3_1_b()
    {
        return $this->hasOne(\App\Models\Tables\table_5_::class, 'id', 'table_1_3_1_b_id');
    }

    public function table_1_3_2_a()
    {
        return $this->hasOne(\App\Models\Tables\table_6_::class, 'id', 'table_1_3_2_a_id');
    }

    public function table_1_3_2_b()
    {
        return $this->hasOne(\App\Models\Tables\table_7_::class, 'id', 'table_1_3_2_b_id');

    }

    public function table_1_4()
    {
        return $this->hasOne(\App\Models\Tables\table_8_1_::class, 'id', 'table_1_4_id');
    }


    public function table_1_5_1()
    {
        return $this->hasOne(\App\Models\Tables\table_8_2_::class, 'id', 'table_1_5_1_id');
    }

    public function table_1_5_1_a()
    {
        return $this->hasOne(\App\Models\Tables\table_9_1_::class, 'id', 'table_1_5_1_a_id');
    }

    public function table_1_6_1()
    {
        return $this->hasOne(\App\Models\Tables\table_9_2_::class, 'id', 'table_1_6_1_id');
    }

    public function table_1_6_1_a()
    {
        return $this->hasOne(\App\Models\Tables\table_10_1_::class, 'id', 'table_1_6_1_a_id');
    }

    public function table_1_6_2()    {

        return $this->hasOne(\App\Models\Tables\table_10_2_::class, 'id', 'table_1_6_2_id');

    }

    public function table_1_7_1()
    {
        return $this->hasOne(\App\Models\Tables\table_22_::class, 'id', 'table_1_7_1_id');
    }


    public function table_1_7_2()
    {
        return $this->hasOne(\App\Models\Tables\table_23_::class, 'id', 'table_1_7_2_id');
    }


    public function table_1_7_3()
    {
        return $this->hasOne(\App\Models\Tables\table_24_::class, 'id', 'table_1_7_3_id');
    }


    public function table_1_9_1()
    {
        return $this->hasOne(\App\Models\Tables\table_10_3_::class, 'id', 'table_1_9_1_id');
    }


    public function table_1_9_2()
    {
        return $this->hasOne(\App\Models\Tables\table_11_1_::class, 'id', 'table_1_9_2_id');
    }

    public function table_1_9_3()
    {
        return $this->hasOne(\App\Models\Tables\table_11_2_::class, 'id', 'table_1_9_3_id');
    }

    public function table_2_2_1()
    {
        return $this->hasOne(\App\Models\Tables\table_11_2_a_::class, 'id', 'table_2_2_1_id');
    }

    public function table_2_2_2()
    {
        return $this->hasOne(\App\Models\Tables\table_12_::class, 'id', 'table_2_2_2_id');
    }

    public function table_2_3_1()
    {
        return $this->hasOne(\App\Models\Tables\table_14_1_::class, 'id', 'table_2_3_1_id');
    }

    public function table_2_3_2()
    {
        return $this->hasOne(\App\Models\Tables\table_14_2_::class, 'id', 'table_2_3_2_id');
    }

    public function table_2_4_1()
    {
        return $this->hasOne(\App\Models\Tables\table_14_3_::class, 'id', 'table_2_4_1_id');
    }

    public function table_2_4_2()
    {
        return $this->hasOne(\App\Models\Tables\table_13_::class, 'id', 'table_2_4_2_id');
    }

    public function table_2_4_2_b()
    {
        return $this->hasOne(\App\Models\Tables\table_15_1_::class, 'id', 'table_2_4_2_b_id');
    }

    public function table_2_5()
    {
        return $this->hasOne(\App\Models\Tables\table_15_2_::class, 'id', 'table_2_5_id');
    }

    public function table_3_4_1()
    {
        return $this->hasOne(\App\Models\Tables\table_16_::class, 'id', 'table_3_4_1_id');
    }

    public function table_3_4_2()
    {
        return $this->hasOne(\App\Models\Tables\table_17_1_::class, 'id', 'table_3_4_2_id');
    }

    public function table_4_1()
    {
        return $this->hasOne(\App\Models\Tables\table_17_2_::class, 'id', 'table_4_1_id');
    }
}
