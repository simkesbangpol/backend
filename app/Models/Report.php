<?php

namespace App\Models;

use Carbon\Carbon;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use SoftDeletes, Filterable;

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
    protected $with = ['category', 'village', 'user'];
    protected $appends = ['parsed_status', 'parsed_date', 'parsed_created_at'];

    private $statuses = [
        0 => 'Belum diproses',
        1 => 'Sedang diproses',
        2 => 'Selesai',
        3 => 'Ditolak'
    ];

    public function getParsedStatusAttribute(){
        return $this->statuses[$this->status];
    }

    public function getParsedDateAttribute(){
        return Carbon::parse($this->date)->format('d F Y');
    }

    public function getParsedCreatedAtAttribute(){
        return Carbon::parse($this->created_at)->format('H:i, d F Y');
    }

    public function category(){
        return $this->belongsTo(ReportCategory::class, 'category_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function village(){
        return $this->belongsTo(Village::class, 'village_id', 'id');
    }
}
