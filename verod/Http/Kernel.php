<?php

namespace Midun\Http;

use Midun\Application;
use Midun\Container;
use Midun\Pipeline\Pipeline;
use Route;

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
    public function __construct()
    {
        $this->app = Container::getInstance();

        $this->bindingMiddlewares();

        $this->app->singleton(Application::class, function ($app) {
            return new Application($app);
        });

        $application = $this->app->make(Application::class);

        $application->run();
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
            $route = new Route;

            return $route->run();
        };
    }

    public function getApplication()
    {
        return $this->app;
    }
}
