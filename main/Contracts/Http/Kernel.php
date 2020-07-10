<?php

namespace Main\Contracts\Http;

interface Kernel
{
    /**
     * Handle an incoming HTTP request.
     *
     * @param  \Main\Http\Request  $request
     * @return mixed
     */
    public function handle($request);

    /**
     * Get the Laravel application instance.
     *
     * @return \Main\Container
     */
    public function getApplication();
}
