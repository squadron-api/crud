<?php

namespace Squadron\CRUD\Exceptions;

class SquadronCrudException extends \Exception
{
    public static function badRouteMethod(string $method)
    {
        return new static("Bad route method: `{$method}`");
    }

    public static function badRouteConfig()
    {
        return new static('CRUD routes config is invalid');
    }
}
