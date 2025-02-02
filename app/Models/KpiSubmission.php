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
        'admin_comment',
        'inspector_id',
        'apilation_message'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function inspector()
    {
        return $this->belongsTo(User::class);
    }


     // KPI mezon bilan bog'lanish
     public function criteria()
     {
         return $this->belongsTo(KpiCriteria::class, 'criteria_id');
     }

     public function getCategories()
     {
         return response()->json(\App\Models\KpiCriteria::categories());
     }

}
