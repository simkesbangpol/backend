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
}
