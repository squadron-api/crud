<?php

namespace Squadron\CRUD\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Squadron\CRUD\Services\CRUDApi;

trait ProvidesCRUD
{
    /** @var CRUDApi */
    protected $apiCrud;
    protected $apiCrudModelClass;

    public function initializeProvidesCRUD(Request $request): void
    {
        if ($this->apiCrudModelClass === null)
        {
            $this->apiCrudModelClass = strtr(class_basename($this), [
                '\\' => '',
                'Controller' => '',
            ]);
        }

        // get base (package?) namespace
        $class = get_class($this);
        $classNamespaceCutPosition = strpos($class, '\\Http\\Controllers');
        $namespace = $classNamespaceCutPosition > 0
                        ? '\\'.substr($class, 0, $classNamespaceCutPosition)
                        : '\\App';

        $this->apiCrud = new CRUDApi($this->apiCrudModelClass, $request, $namespace);
    }
}
