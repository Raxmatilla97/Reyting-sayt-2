<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KpiSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category',
        'criteria_id',
        'description',
        'proof_file',
        'status',
        'points',
        'admin_comment'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
