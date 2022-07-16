<?php

namespace Fsuuaas\LaravelPlupload\Contracts;

use Closure;

interface Plupload
{
    /**
     * Create Plupload builder.
     *
     * @param string $id
     * @param string $url
     * @return \Fsuuaas\LaravelPlupload\Html
     */
    public function make(string $id, string $url): \Fsuuaas\LaravelPlupload\Html;

    /**
     * Plupload file upload handler.
     *
     * @param string $name
     * @param  closure $closure
     * @return void
     */
    public function file(string $name, Closure $closure): void;
}
