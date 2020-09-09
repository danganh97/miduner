<?php

namespace Midun\Http\Middlewares;

use Closure;
use Midun\Container;

class LimitRequest
{
    /**
     * The application implementation.
     *
     * @var \Midun\Container
     */
    protected $app;

    /**
     * Name of attempts
     * 
     * @var string
     */
    const ATTEMPTS = 'attempts';

    /**
     * Create a new middleware instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->app = Container::getInstance();
        $this->session = new \Midun\Session\Session();

        $id = session_id();

        if (!$this->session->isset($id)) {
            $this->createNewClient($id);
        }
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Midun\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws MiddlewareException
     */
    public function handle($request, Closure $next, $max = 60, $seconds = 60, $waits = 10)
    {
        $id = session_id();
        $session = $this->session->get($id);

        if (time() - $session['start_time'] > $seconds) {
            $this->session->unset($id);
            $this->createNewClient($id);
            $session = $this->session->get($id);
        }

        if ($session[LimitRequest::ATTEMPTS] > $max) {
            if (time() - $session['last_time'] > $waits) {
                $this->session->unset($id);
                $this->createNewClient($id);
                $session = $this->session->get($id);
            } else {
                throw new MiddlewareException("You're request too many times. Please wait.");
            }
        }

        $this->session->set($id, [
            LimitRequest::ATTEMPTS => $session[LimitRequest::ATTEMPTS] + 1,
            'start_time' => $session['start_time'],
            'last_time' => time()
        ]);

        return $next($request);
    }

    /**
     * Clear new client with session id
     * 
     * @param string $id
     * 
     * @return void
     */
    private function createNewClient(string $id)
    {
        $this->session->set($id, [
            LimitRequest::ATTEMPTS => 0,
            'start_time' => time(),
            'last_time' => time()
        ]);
    }
}
