<?php

namespace Squadron\CRUD\Tests\Unit;

use Squadron\CRUD\Tests\TestCase;

class ConfigDefaultsTest extends TestCase
{
    public function testConfig(): void
    {
        // models defaults - empty array
        $models = config('squadron.crud.models');

        $this->assertIsArray($models);
        $this->assertCount(0, $models);

        // routes defaults
        $routes = config('squadron.crud.routes');

        $this->assertIsArray($routes);
        $this->assertCount(6, $routes);

        foreach ($routes as $route)
        {
            $this->assertArrayHasKey('method', $route);
            $this->assertArrayHasKey('urlTemplate', $route);
            $this->assertArrayHasKey('controllerMethod', $route);
            $this->assertArrayHasKey('policy', $route);
        }
    }
}
