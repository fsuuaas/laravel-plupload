<?php

namespace Fsuuaas\LaravelPlupload\Facades;

use Illuminate\Support\Facades\Facade;
use Fsuuaas\LaravelPlupload\Contracts\Plupload as PluploadContract;

class Plupload extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return PluploadContract::class;
    }
}
