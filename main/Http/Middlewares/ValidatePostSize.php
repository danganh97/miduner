<?php

namespace Main\Http\Middlewares;

use Closure;
use Main\Http\Exceptions\AppException;

class ValidatePostSize
{
    /**
     * The application implementation.
     *
     * @var \Main\Container
     */
    protected $app;

    /**
     * Create a new middleware instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->app = app();
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Main\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Main\Http\Exceptions\AppException
     */
    public function handle($request, Closure $next)
    {
        $max = $this->getPostMaxSize();

        if ($max > 0 && $_SERVER['CONTENT_LENGTH'] > $max) {
            throw new AppException("Post Body too large, body is {$_SERVER['CONTENT_LENGTH']}");
        }

        return $next($request);
    }

    /**
     * Determine the server 'post_max_size' as bytes.
     *
     * @return int
     */
    protected function getPostMaxSize()
    {
        if (is_numeric($postMaxSize = ini_get('post_max_size'))) {
            return (int) $postMaxSize;
        }

        $metric = strtoupper(substr($postMaxSize, -1));

        switch ($metric) {
            case 'K':
                return (int) $postMaxSize * 1024;
            case 'M':
                return (int) $postMaxSize * 1048576;
            case 'G':
                return (int) $postMaxSize * 1073741824;
            default:
                return (int) $postMaxSize;
        }
    }
}
