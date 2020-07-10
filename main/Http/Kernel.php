<?php

namespace Main\Http;

use Main\Application;
use Main\Container;
use Main\Pipeline\Pipeline;
use Main\Routing\Route;

class Kernel
{
    /**
     * Instance of application
     * @var Container
     */
    private $app;

    /**
     * List of route middlewares
     * @var array
     */
    public $routeMiddlewares = [];

    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middlewares = [];

    /**
     * Initial instance of kernel
     * @param Container $app
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
        $this->route = new Route;
        $this->bindingMiddlewares();
    }

    /**
     * Bindings all middlewares to container
     */
    private function bindingMiddlewares()
    {
        foreach ($this->routeMiddlewares as $key => $middleware) {
            $this->app->bind($key, $middleware);
        }
    }

    /**
     * Handle execute pipeline request
     * @param Request $request
     *
     * @return void
     */
    public function handle(Request $request)
    {
        return (new Pipeline($this->app))
            ->send($request)
            ->through($this->middlewares)
            ->then($this->dispatchToRouter());
    }

    /**
     * Dispatch router of application
     * @return \Closure
     */
    protected function dispatchToRouter()
    {
        return function () {
            new Application();
            return $this->route->run();
        };
    }

    public function getApplication()
    {
        return $this->app;
    }
}
