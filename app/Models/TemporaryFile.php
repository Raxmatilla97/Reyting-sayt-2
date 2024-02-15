<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class TemporaryFile extends Model
{
    use HasFactory;

    // Ruxsat berilgan ustunlar ro'yxati
    protected $fillable = [
        'folder',
        'filename',
        'site_url',
        'ariza_holati',
        'date_created',
        'arizaga_javob',
        'malumot_haqida',
        'points',
        'is_active',
        'user_id',
        'category_id',
    ];

    // Relation orqali bog'lanish
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
