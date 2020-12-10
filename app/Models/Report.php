<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use SoftDeletes;
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
        'category_id',
        'user_id',
    ];
    protected $with = ['category'];

    public function category(){
        return $this->belongsTo(ReportCategory::class, 'category_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
