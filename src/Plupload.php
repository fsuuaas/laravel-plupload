<?php

namespace Fsuuaas\LaravelPlupload;

use Closure;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Foundation\Application;
use Fsuuaas\LaravelPlupload\Contracts\Plupload as Contract;

class Plupload implements Contract
{
    /**
     * @var Illuminate\Contracts\Foundation\Application
     */
    protected Application|Illuminate\Contracts\Foundation\Application $app;

    /**
     * Class constructor.
     *
     * @param  \Illuminate\Contracts\Foundation\Application $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * File upload handler.
     *
     * @param string $name
     * @param closure $closure
     * @return void
     * @throws BindingResolutionException
     */
    public function file(string $name, Closure $closure): void
    {
        $fileHandler = $this->app->make(File::class);

        return;
    }

    /**
     * Html template handler.
     *
     * @param string $id
     * @param string $url
     * @return \Fsuuaas\LaravelPlupload\Html
     * @throws BindingResolutionException
     */
    public function make(string $id, string $url): Html
    {
        return $this->app->make(Html::class, compact('id', 'url'));
    }
}
