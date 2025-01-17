<?php

namespace App\Models\ModelFilters;

use EloquentFilter\ModelFilter;

class ReportCategoryFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    public function dateStart($date){
        return $this->whereHas('reports', function ($query) use ($date) {
            $query->whereDate('date', '>=', $date);
        });
    }

    public function dateEnd($date){
        return $this->whereHas('reports', function ($query) use ($date) {
            $query->whereDate('date', '<=', $date);
        });
    }
}
