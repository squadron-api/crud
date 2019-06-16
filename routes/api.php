<?php

(new \Squadron\CRUD\Services\RouteBuilder(
    '\\App\\Http\\Controllers\\Api',
    '\\App\\Models'
))->build('api');
