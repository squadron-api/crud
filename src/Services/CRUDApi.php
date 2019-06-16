<?php

namespace Squadron\CRUD\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Squadron\Base\Helpers\ApiResponse;

class CRUDApi
{
    private $class;
    private $resourceClass;

    private $request;

    public function __construct($class, Request $request, string $baseNamespace = '\\App')
    {
        $className = str_replace('\\', '', class_basename($class));

        $this->class = "{$baseNamespace}\\Models\\{$className}'";
        $this->resourceClass = "{$baseNamespace}\\Http\\Resources\\{$className}Resource'";

        $this->request = $request;
    }

    public function getList(Builder $query = null, $filter = [], $count = 1000)
    {
        if ($query === null)
        {
            $query = (new $this->class)->newQuery();
        }

        if (method_exists($this->class, 'scopeFilter'))
        {
            $filter = is_array($filter) ? $filter : [];
            $filter = array_merge(
                $filter,
                $this->request->except(['paginate', 'perPage', 'page'])
            );

            $query->filter($filter);
        }

        if (method_exists($this->class, 'scopeSort'))
        {
            $query->sort($this->request->get('sort'));
        }

        $paginate = (bool) $this->request->get('paginate', false);
        $models = ! $paginate ? $query->take($count)->get() : resolve(Paginator::class)->paginate($query);

        return $this->resourceClass::collection($models);
    }

    public function getSingle($model)
    {
        return new $this->resourceClass($model);
    }

    public function create(array $attributes, ?\Closure $afterFill = null, ?\Closure $afterSave = null)
    {
        $newModel = new $this->class();
        $newModel->fill($attributes);

        if ($afterFill !== null)
        {
            $afterFill($newModel);
        }

        $newModel->save();

        if ($afterSave !== null)
        {
            $afterSave($newModel);
        }

        return new $this->resourceClass($newModel);
    }

    public function update($updateModel, array $attributes, ?\Closure $afterFill = null, ?\Closure $afterSave = null)
    {
        $updateModel->fill($attributes);

        if ($afterFill !== null)
        {
            $afterFill($updateModel);
        }

        $updateModel->save();

        if ($afterSave !== null)
        {
            $afterSave($updateModel);
        }

        return new $this->resourceClass($updateModel);
    }

    public function delete($deleteModel)
    {
        return $deleteModel->delete()
                ? ApiResponse::success(__('squadron.crud::messages.objectDeleteSuccess'), ['uuid' => $deleteModel->getKey()])
                : ApiResponse::error(__('squadron.crud::messages.objectDeleteError'));
    }
}
