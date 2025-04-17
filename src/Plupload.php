<?php

namespace Fsuuaas\LaravelPlupload;

use Closure;
use Illuminate\Contracts\Foundation\Application;
use Fsuuaas\LaravelPlupload\Contracts\Plupload as Contract;

class Plupload implements Contract
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected Application $app;

    /**
     * Create a new Plupload instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * File upload handler.
     *
     * @param  string  $name
     * @param  \Closure  $closure
     * @return mixed
     */
    public function file(string $name, Closure $closure): mixed
    {
        $fileHandler = $this->app->make(File::class);

        return $fileHandler->process($name, $closure);
    }

    /**
     * Html template handler.
     *
     * @param  string  $id
     * @param  string  $url
     * @return \Fsuuaas\LaravelPlupload\Html
     */
    public function make(string $id, string $url): Html
    {
        return $this->app->make(Html::class, compact('id', 'url'));
    }
}
