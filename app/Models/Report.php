<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = "reports";
    protected $fillable = [
        'title',
        'fact',
        'date',
        'location',
        'description',
        'action',
        'recommendation',
        'status',
        'category_id'
    ];

    public function category(){
        return $this->belongsTo(ReportCategory::class, 'category_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
