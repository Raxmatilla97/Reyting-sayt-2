<?php

namespace App\Models\Tables;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table_11_2_ extends Model
{
    use HasFactory;

    protected $fillable = [
        'jurnal_nomi',
        'nashr_yili',
        'maqola_nomi',
        'tili',
        'index_url',
        'iqtiboslar_soni'
    ];
}
