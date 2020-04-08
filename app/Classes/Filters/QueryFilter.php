<?php


namespace App\Classes\Filters;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class QueryFilter
{

    protected $builder;
    protected $request;

    public function __construct(Builder $builder, Request $request)
    {
        $this->builder = $builder;
        $this->request = $request;
    }

    public function apply()
    {
        // применяем все полученные фильтры из запроса
        foreach ($this->filters() as $filterName => $filterValue)
            // проверяем, есть ли в классе метод фильтра и применяем его или их, если описано несколько
            if ((method_exists($this, $filterName)) && ($filterValue)) {
                $this->$filterName($filterValue);
            }

        return $this->builder;
    }

    protected function filters()
    {
        // извлекаем все фильтры из запроса
        return $this->request->all();
    }

}
