<?php

namespace Midun\Routing;

use Midun\View\ViewException;
use Midun\Hashing\HashException;
use Midun\Logger\LoggerException;
use Midun\Bus\DispatcherException;
use Midun\Console\ConsoleException;
use Midun\Storage\StorageException;
use Midun\Eloquent\EloquentException;
use Midun\Auth\AuthenticationException;
use Midun\Http\Exceptions\AppException;
use Midun\FileSystem\FileSystemException;
use Midun\Translator\TranslationException;
use Midun\Http\Exceptions\RuntimeException;
use Midun\Http\Exceptions\UnknownException;
use Midun\Http\Validation\ValidationException;
use Midun\Configuration\ConfigurationException;
use Midun\Database\QueryBuilder\QueryException;
use Midun\Http\Middlewares\MiddlewareException;
use Midun\Http\Exceptions\UnauthorizedException;
use Midun\Database\DatabaseBuilder\DatabaseBuilderException;
use Midun\Database\Connections\Mysql\MysqlConnectionException;
use Midun\Database\Connections\PostgreSQL\PostgreConnectionException;

class Compile
{
    /**
     * Method execute
     * 
     * @var string
     */
    private $method;

    /**
     * Controller execute
     * 
     * @var string
     */
    private $controller;

    /**
     * Instance of matched routing
     * 
     * @var \Midun\Routing\RouteCollection
     */
    private $route;

    /**
     * List of parameters
     * 
     * @var array
     */
    private $params = [];

    /**
     * Specific character
     * 
     * @var string
     */
    const DEFAULT_SPECIFIC = '@';

    /**
     * Invoke method name
     * 
     * @var string
     */
    const __INVOKE = '__invoke';

    /**
     * Initial constructor
     * 
     * @param \Midun\Routing\RouteCollection $route
     * @param array $params
     */
    public function __construct(RouteCollection $route, array $params)
    {
        $this->makeRoute($route);
        $this->makeParams($params);

        $this->findingTarget(
            $this->getAction()
        );

        $this->app = \Midun\Container::getInstance();
    }

    /**
     * Handle route action
     *
     * @return void
     * 
     * @throws RouteException
     * @throws RuntimeException
     */
    public function handle()
    {
        $controller = $this->getFullNameSpace(
            $this->getController()
        );
        $method = $this->getMethod();

        if (!class_exists($controller) || !method_exists($controller, $method)) {
            throw new RouteException("Endpoint target `{$controller}@{$method}` doesn't exists");
        }
        try {
            $object = $this->app->build($controller);
            $params = $this->app->resolveMethodDependencyWithParameters(
                $controller,
                $method,
                $this->getParams()
            );

            return call_user_func_array([$object, $method], $params);
        } catch (\Exception $e) {
            switch (true) {
                case $e instanceof AppException:
                case $e instanceof HashException:
                case $e instanceof ViewException:
                case $e instanceof RouteException:
                case $e instanceof QueryException:
                case $e instanceof LoggerException:
                case $e instanceof RuntimeException:
                case $e instanceof ConsoleException:
                case $e instanceof StorageException:
                case $e instanceof EloquentException:
                case $e instanceof DispatcherException:
                case $e instanceof FileSystemException:
                case $e instanceof MiddlewareException:
                case $e instanceof ValidationException:
                case $e instanceof TranslationException:
                case $e instanceof UnauthorizedException:
                case $e instanceof UnauthorizedException:
                case $e instanceof ConfigurationException:
                case $e instanceof AuthenticationException:
                case $e instanceof DatabaseBuilderException:
                case $e instanceof MysqlConnectionException:
                case $e instanceof PostgreConnectionException:
                    throw $e;
                    break;
                default:
                    throw new UnknownException($e->getMessage());
                    break;
            }
        } catch (\Error $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * Find the target controller and method
     * 
     * @param string|array $action
     * 
     * @return void
     */
    private function findingTarget($action)
    {
        list($controller, $method) = is_array($action)
            ? (count($action) === 1
                ? [array_shift($action), Compile::__INVOKE]
                : $action)
            : (count(explode(Compile::DEFAULT_SPECIFIC, $action)) === 1
                ? [$action, Compile::__INVOKE]
                : explode(Compile::DEFAULT_SPECIFIC, $action));

        $this->setMethod($method);
        $this->setController($controller);
    }

    /**
     * Set method name
     * 
     * @param string $method
     * 
     * @return void
     */
    private function setMethod(string $method)
    {
        $this->method = $method;
    }

    /**
     * Get method name
     * 
     * @return string
     */
    private function getMethod()
    {
        return $this->method;
    }

    /**
     * Set controller name
     * 
     * @param string $controller
     * 
     * @return void
     */
    private function setController(string $controller)
    {
        $this->controller = $controller;
    }

    /**
     * Get controller name
     * 
     * @return string
     */
    private function getController()
    {
        return $this->controller;
    }

    /**
     * Make route
     * 
     * @param RouteCollection $route
     * 
     * @return void
     */
    private function makeRoute(RouteCollection $route)
    {
        $this->route = $route;
    }

    /**
     * Get params value
     * 
     * @return array|null
     */
    private function getParams()
    {
        return $this->params;
    }

    /**
     * Get route instance
     * 
     * @return RouteCollection
     */
    private function getRoute()
    {
        return $this->route;
    }

    /**
     * Get action value
     * 
     * @return array|string
     */
    private function getAction()
    {
        $action = $this->getRoute()->{__FUNCTION__}();
        if (empty($action)) {
            $name = $this->getRoute()->getName();
            if (empty($name) || is_null($name)) {
                $name = $this->getRoute()->getUri();
            }
            throw new RouteException("Routing is matched ! But missing action. Please set the action.");
        }

        return $action;
    }

    /**
     * Get namespace value
     * 
     * @param string $controller
     * 
     * @return string
     */
    private function getFullNamespace(string $controller)
    {
        $namespace = $this->getRoute()->getNamespace();

        return !empty($namespace)
            ? implode("\\", $namespace) . "\\" . $controller
            : $controller;
    }

    /**
     * Make params
     * 
     * @param array $params
     * 
     * @return void
     */
    private function makeParams(array $params)
    {
        $this->params = $params;
    }
}
