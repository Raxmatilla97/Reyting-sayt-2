<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KpiCriteria extends Model
{
    use HasFactory;

    protected $table = 'kpi_criteria';

    protected $fillable = [
        'category',
        'name',
        'description',
        'max_points',
        'calculation_method',
        'evaluation_period', // bir yil, semestr va h.k.
        'required_proof', // qanday hujjatlar kerakligi
        'sort_order' // tartibi, PDFdagi tartib bo'yicha
    ];

    // KPI yuborishlar bilan bog'lanish
    public function submissions()
    {
        return $this->hasMany(KpiSubmission::class, 'criteria_id');
    }

    // Kategoriya bo'yicha filtrlash scope
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Kategoriyalar ro'yxati
    public static function categories()
    {
        return [
            'teaching' => 'O\'quv va o\'quv-uslubiy ishlar',
            'research' => 'Ilmiy va innovatsiyalarga oid ishlar',
            'international' => 'Xalqaro hamkorlikka oid ishlar',
            'spiritual' => 'Ma\'naviy-ma\'rifiy ishlar'
        ];
    }
}
