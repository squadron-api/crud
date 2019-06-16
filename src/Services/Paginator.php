<?php

namespace Squadron\CRUD\Services;

use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as LaravelLengthAwarePaginator;
use Illuminate\Pagination\Paginator as LaravelPaginator;
use Illuminate\Support\Facades\DB;

class Paginator
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function paginate(Builder $query): LaravelLengthAwarePaginator
    {
        $currentPage = LaravelPaginator::resolveCurrentPage();
        $perPage = (int) $this->request->get('perPage', 20);

        $total = DB::table(DB::raw("({$query->toSql()}) as result"))->mergeBindings($query->getQuery())->count();
        $items = $total ? $query->forPage($currentPage, $perPage)->get(['*']) : $query->getModel()->newCollection();

        $options = [
            'path' => LaravelPaginator::resolveCurrentPath(),
            'pageName' => 'page',
        ];

        return Container::getInstance()->makeWith(LaravelLengthAwarePaginator::class, compact(
            'items', 'total', 'perPage', 'currentPage', 'options'
        ));
    }
}
