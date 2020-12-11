<?php

namespace App\Models\ModelFilters;

use EloquentFilter\ModelFilter;

class ReportFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    public function dateStart($date){
        return $this->whereDate('date', '>=', $date);
    }

    public function dateEnd($date){
        return $this->whereDate('date', '<=', $date);
    }

    public function status($status){
        return $this->where('status', $status);
    }

    public function category($category){
        return $this->where('category_id', $category);
    }

    public function village($village){
        return $this->where('village_id', $village);
    }

    public function search($search){
        return $this->where('title', 'LIKE', '%'.$search.'%');
    }
}
