<?php

namespace App\Models;

use Carbon\Carbon;
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

    private $statuses = [
        0 => 'Belum diproses',
        1 => 'Sedang diproses',
        2 => 'Selesai',
        3 => 'Ditolak'
    ];

    public function getDateAttribute($value){
        return Carbon::parse($value)->format('d F Y');
    }

    public function getStatusAttribute($value){
        return $this->statuses[$value];
    }

    public function category(){
        return $this->belongsTo(ReportCategory::class, 'category_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
