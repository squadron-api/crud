<?php

namespace Squadron\CRUD\Tests\Unit;

use Illuminate\Support\Facades\Route;
use Squadron\CRUD\Exceptions\SquadronCrudException;
use Squadron\CRUD\Services\RouteBuilder;
use Squadron\CRUD\Tests\TestCase;

class RoutesGenerationTest extends TestCase
{
    public function testRoutesGeneration(): void
    {
        $this->buildTestRoutes();

        $routes = Route::getRoutes()->getRoutes();

        $checks = [
            ['api/model/list', 'GET'],
            ['api/model/\{model\}', 'GET'],
            ['api/model/new', 'POST'],
            ['api/model/\{model\}', 'POST'],
            ['api/model/\{model\}', 'DELETE'],
        ];

        // check overall count
        $this->assertCount(5, $routes);

        // check each route
        foreach ($routes as $key => $route)
        {
            [$expectedUri, $expectedMethod] = $checks[$key];

            $this->assertEquals($expectedUri, $route->uri, "Check {$route->action['as']} route's URI");
            $this->assertContains($expectedMethod, $route->methods, "Check {$route->action['as']} route's method");
        }
    }

    public function testBadRoutesConfig(): void
    {
        app('config')->set('squadron.crud.routes', [[
            'method' => 'abracadabra',
            'badKey' => '/#modelName#/list',
            'controllerMethod' => 'getList',
            'policy' => 'getList,#modelClass#',
        ]]);

        $this->expectException(SquadronCrudException::class);
        $this->buildTestRoutes();
    }

    public function testBadMethodInRoutesConfig(): void
    {
        app('config')->set('squadron.crud.routes', [[
            'method' => 'abracadabra',
            'urlTemplate' => '/#modelName#/list',
            'controllerMethod' => 'getList',
            'policy' => 'getList,#modelClass#',
        ]]);

        $this->expectException(SquadronCrudException::class);
        $this->buildTestRoutes();
    }

    private function buildTestRoutes(): void
    {
        app('config')->set('squadron.crud.models', ['model']);

        (new RouteBuilder(
            '\\Squadron\\CRUD\\Tests\\Support\\Http\\Controllers',
            '\\Squadron\\CRUD\\Tests\\Support\\Models'
        ))->build('api');
    }
}
