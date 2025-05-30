<?php

namespace App\Models;

use App\Models\Department;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PointUserDeportament extends Model
{
    use HasFactory;

    // Ruxsat etilgan columnlar ro'yxati - migratsiyaga moslashtirilgan
    protected $fillable = [
        'user_id',
        'table_2_id',
        'table_3_id',
        'table_4_id',
        'table_5_id',
        'table_6_id',
        'table_7_id',
        'table_8_1_id',
        'table_8_2_id',
        'table_9_1_id',
        'table_9_2_id',
        'table_10_1_id',
        'table_10_2_id',
        'table_10_3_id',
        'table_11_1_id',
        'table_11_2_id',
        'table_11_3_id',
        'table_12_id',
        'table_13_id',
        'table_14_1_id',
        'table_14_2_id',
        'table_14_3_id',
        'table_15_1_id',
        'table_15_2_id',
        'table_16_id',
        'table_17_1_id',
        'table_17_2_id',
        'table_18_1_id',
        'table_18_2_id',
        'table_18_3_id',
        'table_18_3_a_id',
        'table_19_id',
        'table_20_1_id',
        'table_20_2_id',
        'table_20_3_id',
        'table_21_1_id',
        'table_21_2_id',
        'table_22_id',
        'table_23_id',
        'table_24_id',
        'departament_info',
        'status',
        'departament_id',
        'year',
        'arizaga_javob',
        'is_admin',
        'point',
        'is_active'
    ];

    // Relationlarni nomli ro'yxati - migratsiyaga moslashtirilgan
    protected $relationships = [
        'table_2',
        'table_3',
        'table_4',
        'table_5',
        'table_6',
        'table_7',
        'table_8_1',
        'table_8_2',
        'table_9_1',
        'table_9_2',
        'table_10_1',
        'table_10_2',
        'table_10_3',
        'table_11_1',
        'table_11_2',
        'table_11_3',
        'table_12',
        'table_13',
        'table_14_1',
        'table_14_2',
        'table_14_3',
        'table_15_1',
        'table_15_2',
        'table_16',
        'table_17_1',
        'table_17_2',
        'table_18_1',
        'table_18_2',
        'table_18_3',
        'table_18_3_a',
        'table_19',
        'table_20_1',
        'table_20_2',
        'table_20_3',
        'table_21_1',
        'table_21_2',
        'table_22',
        'table_23',
        'table_24'
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

    public function table_2()
    {
        return $this->hasOne(\App\Models\Tables\Table_2_::class, 'id', 'table_2_id');
    }

    public function table_3()
    {
        return $this->hasOne(\App\Models\Tables\Table_3_::class, 'id', 'table_3_id');
    }

    public function table_4()
    {
        return $this->hasOne(\App\Models\Tables\Table_4_::class, 'id', 'table_4_id');
    }

    public function table_5()
    {
        return $this->hasOne(\App\Models\Tables\Table_5_::class, 'id', 'table_5_id');
    }

    public function table_6()
    {
        return $this->hasOne(\App\Models\Tables\Table_6_::class, 'id', 'table_6_id');
    }

    public function table_7()
    {
        return $this->hasOne(\App\Models\Tables\Table_7_::class, 'id', 'table_7_id');
    }

    public function table_8_1()
    {
        return $this->hasOne(\App\Models\Tables\Table_8_1_::class, 'id', 'table_8_1_id');
    }

    public function table_8_2()
    {
        return $this->hasOne(\App\Models\Tables\Table_8_2_::class, 'id', 'table_8_2_id');
    }

    public function table_9_1()
    {
        return $this->hasOne(\App\Models\Tables\Table_9_1_::class, 'id', 'table_9_1_id');
    }

    public function table_9_2()
    {
        return $this->hasOne(\App\Models\Tables\Table_9_2_::class, 'id', 'table_9_2_id');
    }

    public function table_10_1()
    {
        return $this->hasOne(\App\Models\Tables\Table_10_1_::class, 'id', 'table_10_1_id');
    }

    public function table_10_2()
    {
        return $this->hasOne(\App\Models\Tables\Table_10_2_::class, 'id', 'table_10_2_id');
    }

    public function table_10_3()
    {
        return $this->hasOne(\App\Models\Tables\Table_10_3_::class, 'id', 'table_10_3_id');
    }

    public function table_11_1()
    {
        return $this->hasOne(\App\Models\Tables\Table_11_1_::class, 'id', 'table_11_1_id');
    }

    public function table_11_2()
    {
        return $this->hasOne(\App\Models\Tables\Table_11_2_::class, 'id', 'table_11_2_id');
    }

    public function table_11_3()
    {
        return $this->hasOne(\App\Models\Tables\Table_11_3_::class, 'id', 'table_11_3_id');
    }

    public function table_12()
    {
        return $this->hasOne(\App\Models\Tables\Table_12_::class, 'id', 'table_12_id');
    }

    public function table_13()
    {
        return $this->hasOne(\App\Models\Tables\Table_13_::class, 'id', 'table_13_id');
    }

    public function table_14_1()
    {
        return $this->hasOne(\App\Models\Tables\Table_14_1_::class, 'id', 'table_14_1_id');
    }

    public function table_14_2()
    {
        return $this->hasOne(\App\Models\Tables\Table_14_2_::class, 'id', 'table_14_2_id');
    }

    public function table_14_3()
    {
        return $this->hasOne(\App\Models\Tables\Table_14_3_::class, 'id', 'table_14_3_id');
    }

    public function table_15_1()
    {
        return $this->hasOne(\App\Models\Tables\Table_15_1_::class, 'id', 'table_15_1_id');
    }

    public function table_15_2()
    {
        return $this->hasOne(\App\Models\Tables\Table_15_2_::class, 'id', 'table_15_2_id');
    }

    public function table_16()
    {
        return $this->hasOne(\App\Models\Tables\Table_16_::class, 'id', 'table_16_id');
    }

    public function table_17_1()
    {
        return $this->hasOne(\App\Models\Tables\Table_17_1_::class, 'id', 'table_17_1_id');
    }

    public function table_17_2()
    {
        return $this->hasOne(\App\Models\Tables\Table_17_2_::class, 'id', 'table_17_2_id');
    }

    public function table_18_1()
    {
        return $this->hasOne(\App\Models\Tables\Table_18_1_::class, 'id', 'table_18_1_id');
    }

    public function table_18_2()
    {
        return $this->hasOne(\App\Models\Tables\Table_18_2_::class, 'id', 'table_18_2_id');
    }

    public function table_18_3()
    {
        return $this->hasOne(\App\Models\Tables\Table_18_3_::class, 'id', 'table_18_3_id');
    }

    public function table_18_3_a()
    {
        return $this->hasOne(\App\Models\Tables\Table_18_3_a_::class, 'id', 'table_18_3_a_id');
    }

    public function table_19()
    {
        return $this->hasOne(\App\Models\Tables\Table_19_::class, 'id', 'table_19_id');
    }

    public function table_20_1()
    {
        return $this->hasOne(\App\Models\Tables\Table_20_1_::class, 'id', 'table_20_1_id');
    }

    public function table_20_2()
    {
        return $this->hasOne(\App\Models\Tables\Table_20_2_::class, 'id', 'table_20_2_id');
    }

    public function table_20_3()
    {
        return $this->hasOne(\App\Models\Tables\Table_20_3_::class, 'id', 'table_20_3_id');
    }

    public function table_21_1()
    {
        return $this->hasOne(\App\Models\Tables\Table_21_1_::class, 'id', 'table_21_1_id');
    }

    public function table_21_2()
    {
        return $this->hasOne(\App\Models\Tables\Table_21_2_::class, 'id', 'table_21_2_id');
    }

    public function table_22()
    {
        return $this->hasOne(\App\Models\Tables\Table_22_::class, 'id', 'table_22_id');
    }

    public function table_23()
    {
        return $this->hasOne(\App\Models\Tables\Table_23_::class, 'id', 'table_23_id');
    }

    public function table_24()
    {
        return $this->hasOne(\App\Models\Tables\Table_24_::class, 'id', 'table_24_id');
    }
}