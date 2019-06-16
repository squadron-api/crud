<?php

namespace Squadron\CRUD\Tests\Support\Http\Controllers;

use Squadron\Base\Http\Controllers\BaseController;
use Squadron\CRUD\Http\Controllers\Traits\ProvidesCRUD;
use Squadron\CRUD\Tests\Support\Models\Model;

class ModelController extends BaseController
{
    use ProvidesCRUD;

    public function getList()
    {
        return $this->apiCrud->getList();
    }

    public function getSingle(Model $model)
    {
        return $this->apiCrud->getSingle($model);
    }

    public function create()
    {
        return $this->apiCrud->create([]);
    }

    public function update(Model $model)
    {
        return $this->apiCrud->update($model, []);
    }

    public function delete(Model $model)
    {
        return $this->apiCrud->delete($model);
    }
}
