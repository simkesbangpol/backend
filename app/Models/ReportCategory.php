<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportCategory extends Model
{
    protected $table = 'report_categories';
    protected $fillable = [
        'icon',
        'name',
        'color'
    ];

    protected $appends = [
        'unprocessed_count'
    ];

    public function getUnprocessedCountAttribute(){
        return $this->reports()->where('status', 0)->count();
    }

    public function reports() {
        return $this->hasMany(Report::class, 'category_id');
    }
}
