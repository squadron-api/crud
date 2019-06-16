<?php

namespace Squadron\CRUD\Services;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Arr;
use Squadron\Base\Helpers\Route as RouteHelper;
use Squadron\CRUD\Exceptions\SquadronCrudException;

class RouteBuilder
{
    private const ALLOWED_METHODS = ['get', 'post', 'put', 'delete'];
    private const ROUTE_CONFIG_KEYS = ['method', 'urlTemplate', 'controllerMethod', 'policy'];

    private $controllersNamespace;
    private $modelsNamespace;

    private $userPackageInstalled;

    public function __construct(string $controllersNamespace, string $modelsNamespace)
    {
        $this->controllersNamespace = $controllersNamespace;
        $this->modelsNamespace = $modelsNamespace;

        $this->userPackageInstalled = class_exists('\Squadron\User\Models\User');
    }

    public function build(string $prefix): void
    {
        $middleware = $this->userPackageInstalled ? ['auth:api'] : ['api'];

        Route::namespace($this->controllersNamespace)
            ->prefix($prefix)
            ->middleware($middleware)
            ->group(function () {
                $crudModels = config('squadron.crud.models', []);
                $routes = config('squadron.crud.routes', []);

                foreach ($crudModels as $crudModel)
                {
                    $upperCrudModel = ucfirst($crudModel);

                    foreach ($routes as $routeData)
                    {
                        // check route config
                        if (! Arr::has($routeData, self::ROUTE_CONFIG_KEYS))
                        {
                            throw SquadronCrudException::badRouteConfig();
                        }

                        $url = strtr($routeData['urlTemplate'], [
                            '#modelName#' => $crudModel,
                            '#modelKey#' => "\{$crudModel\}",
                        ]);

                        $action = sprintf(
                            '%s\\%sController@%s',
                            $this->controllersNamespace,
                            $upperCrudModel,
                            $routeData['controllerMethod']
                        );

                        if (RouteHelper::actionExists($action))
                        {
                            $method = strtolower($routeData['method']);

                            if (! in_array($method, self::ALLOWED_METHODS, true))
                            {
                                throw SquadronCrudException::badRouteMethod($method);
                            }

                            $route = Route::$method($url, $action)->name("{$crudModel}.{$routeData['controllerMethod']}");

                            $this->attachWhere($route, $routeData, $crudModel);
                            $this->attachPolicy($route, $routeData, $crudModel);
                        }
                    }
                }
            }
        );
    }

    private function attachWhere(\Illuminate\Routing\Route $route, $routeData, $crudModel): void
    {
        $useWhere = strpos($routeData['urlTemplate'], '#modelKey#') !== false;

        if ($useWhere)
        {
            $route->where([$crudModel => '[a-f0-9-]+']);
        }
    }

    private function attachPolicy(\Illuminate\Routing\Route $route, $routeData, $crudModel): void
    {
        // we have squadron-api/user installed - activate policies
        if ($this->userPackageInstalled)
        {
            $upperCrudModel = ucfirst($crudModel);

            $policy = strtr($routeData['urlTemplate'], [
                '#modelClass#' => "{$this->modelsNamespace}\\{$upperCrudModel}",
                '#modelObject#' => $crudModel,
            ]);

            $route->middleware("can:{$policy}");
        }
    }
}
