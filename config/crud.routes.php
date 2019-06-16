<?php

return [
    'routes' => [[
        'method' => 'get',
        'urlTemplate' => '/#modelName#/list',
        'controllerMethod' => 'getList',
        'policy' => 'getList,#modelClass#',
    ], [
        'method' => 'get',
        'urlTemplate' => '/#modelName#/list/filter/{filterName}',
        'controllerMethod' => 'getListFiltered',
        'policy' => 'getList,#modelClass#',
    ], [
        'method' => 'get',
        'urlTemplate' => '/#modelName#/#modelKey#',
        'controllerMethod' => 'getSingle',
        'policy' => 'create,#modelObject#',
    ], [
        'method' => 'post',
        'urlTemplate' => '/#modelName#/new',
        'controllerMethod' => 'create',
        'policy' => 'create,#modelClass#',
    ], [
        'method' => 'post',
        'urlTemplate' => '/#modelName#/#modelKey#',
        'controllerMethod' => 'update',
        'policy' => 'update,#modelObject#',
    ], [
        'method' => 'delete',
        'urlTemplate' => '/#modelName#/#modelKey#',
        'controllerMethod' => 'delete',
        'policy' => 'delete,#modelObject#',
    ]],
];
