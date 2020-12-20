<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class ReportCategory extends Model
{
    use Filterable;

    protected $table = 'report_categories';
    protected $fillable = [
        'icon',
        'name',
        'color'
    ];

    protected $appends = [
        'report_count'
    ];

    public function getReportCountAttribute(){
        if(request()->has('date_start') && request()->has('date_end')){
            return $this
                ->reports()
                ->whereDate('date', '>=', request()->get('date_start'))
                ->whereDate('date', '<=', request()->get('date_end'))
                ->count();
        }
        return $this->reports()->count();
    }

    public function reports() {
        return $this->hasMany(Report::class, 'category_id');
    }
}
