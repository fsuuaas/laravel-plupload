<?php

use Fsuuaas\LaravelPlupload\Contracts\Plupload;

if (! function_exists('plupload')) {
    /**
     * @param string|null $id
     * @param string|null $url
     * @return \Fsuuaas\LaravelPlupload\Contracts\Plupload|\Fsuuaas\LaravelPlupload\Html
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    function plupload(string $id = null, string $url = null): \Fsuuaas\LaravelPlupload\Html|Plupload
    {
        $factory = app(Plupload::class);

        if (func_num_args() === 0) {
            return $factory;
        }

        return $factory->make($id, $url);
    }
}
